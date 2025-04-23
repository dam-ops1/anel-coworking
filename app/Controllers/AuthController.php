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
        // 1) Si no es POST, solo muestro la vista
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/login');
        }

        // 2) Validación
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        // 3) Intento autenticar
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        // ¡DEBUG! — verás en pantalla lo que hay en $user y si password_verify devuelve true/false
        dd([
            'user'    => $user,
            'verify'  => $user
                ? password_verify($password, $user['password_hash'])
                : null,
            'raw_pwd' => $password,
        ]);

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

        // 4) Si llegamos aquí, falló la autenticación
        return redirect()->back()
                        ->with('error', 'Email o contraseña incorrectos')
                        ->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Has cerrado sesión correctamente');
    }

    public function register()
{
    helper(['form']);

    // Sólo muestro el formulario si no es POST
    if ($this->request->getMethod() !== 'post') {
        return view('auth/register');
    }

    // 1) Reglas de validación
    $rules = [
        'username'         => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'email'            => 'required|valid_email|is_unique[users.email]',
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
        'full_name'        => 'required|min_length[3]|max_length[50]',
        'phone'            => 'permit_empty|regex_match[/^\d{9,10}$/]',
        'company_name'     => 'permit_empty|max_length[100]',
    ];

    if (! $this->validate($rules)) {
        return view('auth/register', [
            'validation' => $this->validator,
        ]);
    }

    // 2) ¡Ojo! Capturo **todos** los campos que vienen, incluyendo password y password_confirm
    $post = $this->request->getPost([
        'username',
        'email',
        'password',
        'password_confirm',
        'full_name',
        'phone',
        'company_name',
    ]);

    // 3) Le paso ese array a save(), que:
    //    a) valida otra vez según $validationRules en el modelo
    //    b) dispara beforeInsert::hashPassword(), detecta $data['password'] y genera password_hash
    //    c) inserta el usuario
    if (! $this->userModel->save($post)) {
        return view('auth/register', [
            'validation' => $this->userModel->errors(),
        ]);
    }

    // 4) Todo OK, redirijo al login
    return redirect()->to('/login')
                     ->with('success', 'Registro exitoso. Por favor inicia sesión.');
}

} 