<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {

        echo "Método: " . $this->request->getMethod();
        echo "<pre>";
        print_r($this->request->getPost());
        echo "</pre>";
        
        if ($this->request->getMethod() === 'POST') {
            echo "<pre>";
            echo "entra";
            echo "</pre>";
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password_hash'])) {
                $sessionData = [
                    'user_id' => $user['user_id'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'isLoggedIn' => true
                ];

                session()->set($sessionData);
                return redirect()->to('/rooms/calendar')->with('message', 'Bienvenido ' . $user['full_name']);
            }

            return redirect()->back()->with('error', 'Email o contraseña incorrectos');
        }
        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Has cerrado sesión correctamente');
    }

    // Método temporal para crear un usuario de prueba
    public function createTestUser()
    {
        // Verificar si ya existe el usuario
        $existingUser = $this->userModel->where('email', 'admin@example.com')->first();
        
        if ($existingUser) {
            return 'El usuario de prueba ya existe. Puedes iniciar sesión con admin@example.com y contraseña: admin123';
        }

        $userData = [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin123', // Se hasheará automáticamente
            'full_name' => 'Administrador',
            'role' => 'admin',
            'is_active' => 1
        ];

        if ($this->userModel->createUser($userData)) {
            return 'Usuario de prueba creado exitosamente. Email: admin@example.com, Contraseña: admin123';
        }

        return 'Error al crear el usuario de prueba';
    }
} 