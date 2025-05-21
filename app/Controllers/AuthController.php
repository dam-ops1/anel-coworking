<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;
    protected $rulesRegister;
    protected $rulesLogin;
    protected $messagesRegister;
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

        $this->rulesRegister = get_user_register_rules();

        $this->messagesRegister = get_user_register_messege();

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


        $user = $this->userModel->verifyUser($email, $password);

        if ($user) {
            $this->setSession($user);
            return redirect()->to('dashboard');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Usuario o contraseña incorrectos. Inténtalo de nuevo.');    
    }

    public function logout(){
        
        $userId = $this->session->get('user_id');
        $this->userModel->logoutUser($userId);
        
        if($this->session->get('isLoggedIn')) {
            $this->session->destroy();
        }

        return redirect()->to(base_url());
    }

    public function register(){

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
        $user = $this->userModel->where(['activation_token' => $token, 'email_verified' => 0])-> first();

        if (!$user) return $this->messageController->showMessage("Error", "Error al verificar el usuario. Por favor, intentelo más tarde.", '/', 'Iniciar Sesión');
        

        $this->userModel->update(
            $user['user_id'],
            [
                'email_verified' => 1,
                'activation_token' => ''
            ]
            );

        return $this->messageController->showMessage("Usuario Verificado", "El usuario ha sido verificado exitosamente.", '/', 'Iniciar Sesión');
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

        // Actualizamos la contraseña y limpiamos el token
        $this->userModel->update($user['user_id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            'reset_token' => null,
            'reset_token_expires' => null
        ]);

        return redirect()->to('login')->with('success', 'Contraseña actualizada exitosamente. Puedes iniciar sesión ahora.');

        
    }

    

} 