<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;
    protected $rulesRegister;
    protected $rulesLogin;
    protected $messagesRegister;
    protected $messagesLogin;

    protected $emailController;

    public function __construct()
    {
        helper("userRules");
        helper("userMesseges");

        $this->userModel = new UserModel();

        $this->rulesRegister = get_user_register_rules();

        $this->messagesRegister = get_user_register_messege();

        $this->rulesLogin = get_user_login_rules();
        $this->messagesLogin = get_user_login_messege();

        $this->emailController = new EmailController();
    }

    public function login()
    {

        // 1) Si no es POST, solo muestro la vista
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/login');
        }

            if (!$this->validate($this->rulesLogin, $this->messagesLogin)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        // Intento autenticar
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login exitoso
            $sessionData = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);
            return redirect()->to('/dashboard')
                           ->with('success', 'Bienvenido ' . $user['full_name']);
        }

        // Si llegamos aquí, falló la autenticación
        return redirect()->back()
                        ->with('error', 'Email o contraseña incorrectos')
                        ->withInput();
    }

    public function logout(){
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Has cerrado sesión correctamente');
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
        
        // Creo el usuario llamando al modelo
        $userId = $this->userModel->createUser($this->request->getPost());

        
        
        if ($userId) {
            $user = $this->userModel->find($userId);

            return $this->emailController->sendEmailToRegister($user['email'], 'Registro Exitoso', $user);
            
            // // Si el usuario se creó correctamente, redirijo al login
            // return redirect()->to('/login')
            //                 ->with('success', 'Registro exitoso. Por favor inicia sesión.');

        } else {
            // Si hubo un error al crear el usuario, redirijo de nuevo al registro
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error al crear la cuenta. Inténtalo de nuevo.');
        }
    }

} 