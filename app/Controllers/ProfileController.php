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
    
    public function edit()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        return view('profile/edit', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        $rules = get_user_profile_rules($userId);
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

        // Procesar la imagen si se ha subido una
        $file = $this->request->getFile('profile_image');
        $imageUpdated = false;
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validar tipo y tamaño
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars/', $newName);

            // Añadir la imagen al array de datos a actualizar
            $data['profile_image'] = $newName;
            $imageUpdated = true;
            
            // Actualizar la sesión con la nueva imagen
            session()->set('profile_image', $newName);
        }

        // Actualizar los datos del usuario
        $result = $this->userModel->skipValidation(true)->update($userId, $data);
        
        if ($result) {
            // Si la imagen fue actualizada, necesitamos asegurarnos de que la sesión se actualice correctamente
            if ($imageUpdated) {
                // Volver a cargar los datos del usuario para asegurarnos de tener la información más reciente
                $updatedUser = $this->userModel->find($userId);
                
                // Actualizar la información de la sesión
                session()->set([
                    'profile_image' => $updatedUser['profile_image'],
                    'full_name' => $updatedUser['full_name'],
                    'email' => $updatedUser['email']
                ]);
                
                // Forzar que la sesión se guarde inmediatamente
                session()->markAsTempdata('profile_refresh', 1, 300); // Marcar que hubo un cambio de perfil
            }
            
            // Redirigir a la página de perfil con un parámetro para forzar la recarga completa
            return redirect()->to('profile?refresh=' . time())->with('success', 'Perfil actualizado correctamente.');
        } else {
            return redirect()->back()->with('error', 'Hubo un error al actualizar el perfil.');
        }
    }

}
