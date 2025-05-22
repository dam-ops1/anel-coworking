<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;
    protected $rulesLogin;
    protected $messagesLogin;

    protected $rulesForgotPassword;
    protected $messagesForgotPassword;

    protected $rulesResetPassword;
    protected $messagesResetPassword;

    // controllers
    protected $emailController;
    protected $messageController;


    public function __construct()
    {
        helper("userRules");
        helper("userMesseges");

        $this->userModel = new UserModel();

        $this->rulesLogin = get_user_login_rules();
        $this->messagesLogin = get_user_login_messege();

        $this->rulesForgotPassword = get_user_forgot_password_rules();
        $this->messagesForgotPassword = get_user_forgot_password_messege();

        $this->rulesResetPassword = get_user_reset_password_rules();
        $this->messagesResetPassword = get_user_reset_password_messege();

        $this->emailController = new EmailController();

        $this->messageController = new MessageController();
    }

    public function login()
    {
        // 1) Si no es POST, solo muestro la vista
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/login');
        }

        if (! $this->validate($this->rulesLogin, $this->messagesLogin)) {
            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator); 
        }

        // Intento autenticar
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Verificar si el usuario existe
        $user = $this->userModel->where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Usuario o contraseña incorrectos. Inténtalo de nuevo.');
        }
        
        // Verificar si la cuenta está verificada
        if ($user['email_verified'] == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tu cuenta no está verificada. Por favor, revisa tu correo para activarla.');
        }
        
        // Verificar la contraseña
        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Usuario o contraseña incorrectos. Inténtalo de nuevo.');
        }
        
        // Actualizar estado del usuario a activo
        $this->userModel->update($user['user_id'], [
            'is_active' => 1,
            'last_login' => date('Y-m-d H:i:s')
        ]);
        
        // Si todo está bien, iniciar sesión
        $this->setSession($user);
        return redirect()->to('dashboard');
    }

    public function logout(){
        
        $userId = $this->session->get('user_id');
        $this->userModel->logoutUser($userId);
        
        if($this->session->get('isLoggedIn')) {
            $this->session->destroy();
        }

        return redirect()->to(base_url());
    }

    public function register()
    {
        // Sólo muestro el formulario si no es POST
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/register');
        }

        if (! $this->validate($this->rulesRegister, $this->messagesRegister)) {
            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator); 
        }
        
        // Verificar si el email ya está registrado
        $email = $this->request->getPost('email');
        $existingUser = $this->userModel->where('email', $email)->first();
        
        if ($existingUser) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El correo electrónico ya está registrado. Por favor, utilice otro o inicie sesión.');
        }
        
        // Creo el usuario llamando al modelo
        $userId = $this->userModel->createUser($this->request->getPost());

        if(!$userId) 
        {
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error al crear la cuenta. Inténtalo de nuevo.');
        }

        $user = $this->userModel->find($userId);

        return $this->emailController->sendEmailToRegister($user['email'], 'Registro Exitoso', $user);
    }

    public function activateUser($token)
    {
        $user = $this->userModel->where(['activation_token' => $token, 'email_verified' => 0])->first();

        if (!$user) {
            return $this->messageController->showMessage(
                "Error", 
                "Error al verificar el usuario. El token no es válido o la cuenta ya está verificada.", 
                '/', 
                'Iniciar Sesión'
            );
        }
        
        // Marcar que el usuario ha activado su cuenta, pero requiere crear contraseña
        // Generamos un token de restablecimiento de contraseña
        $resetToken = bin2hex(random_bytes(20));
        
        // Establecemos tiempo de expiración para el token
        $expiresAt = new \DateTime();
        $expiresAt->modify('+24 hours');
        
        // Actualizamos el usuario con el token de restablecimiento
        // Mantenemos is_active en 0 hasta que el usuario establezca su contraseña
        $this->userModel->update(
            $user['user_id'],
            [
                'activation_token' => '', // Limpiamos el token de activación
                'reset_token' => $resetToken,
                'reset_token_expires' => $expiresAt->format('Y-m-d H:i:s'),
                'is_active' => 0 // Aseguramos que el usuario permanece inactivo hasta completar registro
            ]
        );
        
        // Redirigir al restablecimiento de contraseña con un mensaje personalizado
        return $this->emailController->sendEmailToResetPassword(
            $user['email'], 
            'Establece tu contraseña para completar el registro', 
            $user, 
            $resetToken
        );
    }

    private function setSession($userData)
    {
        // Asegurar que el valor de profile_image sea válido
        $profileImage = 'default.png';
        if (!empty($userData['profile_image'])) {
            // Verificar si el archivo existe
            $imagePath = FCPATH . 'uploads/avatars/' . $userData['profile_image'];
            if (file_exists($imagePath)) {
                $profileImage = $userData['profile_image'];
            }
        }

        $data = [
            'isLoggedIn'     => true,
            'user_id'        => $userData['user_id'],
            'email'          => $userData['email'],
            'full_name'      => $userData['full_name'],
            'profile_image'  => $profileImage,
            'role'           => $userData['role_id'], 
        ];

        $this->session->set($data);
        
        // Actualizar el último acceso del usuario
        $this->userModel->update($userData['user_id'], [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    public function forgotPasswordView()
    {
        return view('auth/forgot_password_request');
    }

    public function sendResetPasswordEmail()
    {

        if (! $this->validate($this->rulesForgotPassword, $this->messagesForgotPassword)) {
            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator); 
        }

        $email = $this->request->getPost('email');

        $user = $this->userModel->where('email', $email)->first();

        // Verificamos si el usuario existe
        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No existe una cuenta asociada a este correo electrónico. Por favor, registre una cuenta primero.');
        }
        
        if (!$user['email_verified']) {
            return redirect()->to('login')
                ->with('error', 'Usuario no verificado. Por favor, verifica tu cuenta antes de restablecer la contraseña.');
        }
        
        $token = bin2hex(random_bytes(20));
        
        // le agregamos tiempo de expiracion al token
        $expiresAt = new \DateTime();
        $expiresAt->modify('+1 hour');

        $this->userModel->update($user['user_id'], [
            'reset_token' => $token,
            'reset_token_expires' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        return $this->emailController->sendEmailToResetPassword($user['email'], 'Se ha solicitado un reinicio de contraseña', $user, $token);
    
    }

    public function resetPassword($token)
    {
        $user = $this->userModel->where(['reset_token' => $token])->first();
        
        

        if (!$user) return redirect()->to('login')->with('error', 'Token inválido o expirado.');

        // Verificamos si el token ha expirado
        $expiresAt = new \DateTime($user['reset_token_expires']);
        if ($expiresAt < new \DateTime()) {
            return $this->messageController->showMessage("Error", "El tiempo para restaurar la contraseña ya ha expirado. Por favor, solicita un nuevo enlace para cambiar tu contraseña.", 'login', 'Iniciar Sesión');
        }

        // Aquí puedes mostrar un formulario para que el usuario ingrese su nueva contraseña
        return view('auth/reset_password', ['token' => $token]);
    }

    public function resetPasswordPost()
    {
        if (! $this->validate($this->rulesResetPassword, $this->messagesResetPassword)) {
            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator); 
        }

        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');

        $user = $this->userModel->where(['reset_token' => $token])->first();

        if (!$user) return redirect()->to('login')->with('error', 'Token inválido o expirado.');

        // Verificamos si el token ha expirado
        $expiresAt = new \DateTime($user['reset_token_expires']);
        if ($expiresAt < new \DateTime()) {
            return redirect()->to('login')->with('error', 'El tiempo para restaurar la contraseña ya ha expirado. Por favor, solicita un nuevo enlace para cambiar tu contraseña.');
        }

        // Actualizamos la contraseña, limpiamos el token y establecemos la cuenta como verificada y activa
        $this->userModel->update($user['user_id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            'reset_token' => null,
            'reset_token_expires' => null,
            'activation_token' => null, // Clear activation token too
            'email_verified' => 1, // Marcamos la cuenta como verificada
            'is_active' => 1 // Establecemos la cuenta como activa
        ]);

        return redirect()->to('login')->with('success', 'Contraseña establecida exitosamente. Tu cuenta ha sido verificada y activada. Ya puedes iniciar sesión con tu nueva contraseña.');
    }

    

} 