<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{

    protected $userModel;

    protected $rulesProfile;
    protected $messagesProfile;

    public function __construct()
    {
        helper("userRules");
        helper("userMesseges");

        $this->rulesProfile = get_user_profile_rules();

        $this->messagesProfile = get_user_profile_messages();

        $this->userModel = new UserModel();
    }
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Usuario no encontrado');
        }

        return view('profile/profile', [
            'user' => $user
        ]);
    }

    public function uploadImage()
    {
        $file = $this->request->getFile('profile_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validar tipo y tamaño si lo deseas
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars/', $newName);

            // Actualizar el nombre del archivo en la base de datos del usuario
            $userId = session()->get('user_id');
            $this->userModel->update($userId, ['profile_image' => $newName]);

            session()->set('profile_image', $newName);

            return redirect()->back()->with('success', 'Imagen actualizada correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error al subir la imagen.');
    }

    
    public function edit()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        return view('profile/edit', ['user' => $user]);
    }

    public function update()
    {

        $userId = session()->get('user_id');

        $rules = get_user_profile_rules();
        $messages = get_user_profile_messages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ];

        $this->userModel->update($userId, $data);

        return redirect()->to('profile')->with('success', 'Perfil actualizado correctamente.');
    }

}
