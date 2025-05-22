<?php

namespace App\Controllers;

use App\Database\Seeds\RoleSeeder;
use App\Models\RoleModel;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel; 

    protected $rulesRegister;
    protected $messagesRegister;
    protected $emailController;

    public function __construct()
    {

        helper("userRules");
        helper("userMesseges");


        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();

        $this->rulesRegister = get_user_register_rules();

        $this->messagesRegister = get_user_register_messege();

        $this->emailController = new \App\Controllers\EmailController();

    }


    // Método para mostrar todos los usuarios en el panel de administración,
    // además de agregar los nombres de los roles a cada usuario.

    public function adminIndex()
    {
        $data = [
            'users' => $this->userModel->findAll()
        ];

        $data['users'] = $this->addRoleNamesToUsers($data['users']);

        return view('admin/users/list', $data);
    }

    /**
     * Método para mostrar el formulario de creación de un nuevo usuario
     * en el panel de administración.
     */

    public function adminNew()
    {
        try {
            $data = [
                'roles' => $this->roleModel->findAll(),
                'current_user' => null, 
                'errors' => session()->getFlashdata('errors'),
                'old_input' => session()->getFlashdata('old_input')
            ];

            return view('admin/users/form', $data);
        } catch (\Exception $e) {
            return $this->handleException($e, 'admin/users');
        }
    }

    // Método auxiliar para manejar excepciones
    private function handleException(\Exception $e, $redirectPath)
    {
        log_message('error', '[UserController] Error: ' . $e->getMessage());
        session()->setFlashdata('error', 'Ha ocurrido un error: ' . $e->getMessage());
        return redirect()->to($redirectPath);
    }

    // Método para agregar nombres de roles a los usuarios
    private function addRoleNamesToUsers($users)
    {
        if (empty($users)) {
            return $users;
        }

        // Obtenemos todos los roles
        $roles = $this->roleModel->findAll();
        $rolesById = [];

        foreach ($roles as $role) {
            $rolesById[$role['role_id']] = $role['role_name'];
        }

        // Agregamos el nombre del rol a cada usuario
        foreach ($users as &$user) {
            $user['role_name'] = isset($rolesById[$user['role_id']]) 
                ? $rolesById[$user['role_id']] 
                : 'N/A';
        }

        return $users;
    }

    /**
     * Método para crear un nuevo usuario desde el panel de administración,
     * utiliza el mismo formulario que el de registro, pero con un token de activación
     * además de utilizar las validaciones de registro por si un valor es incorrecto.
     */

    public function adminCreate()
    {
        $session = session();

        if (! $this->validate($this->rulesRegister, $this->messagesRegister)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            
            session()->setFlashdata('old_input', $this->request->getPost());
            session()->setFlashdata('errors', $this->validator->getErrors());

            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator);
        }

        
        $activationToken = bin2hex(random_bytes(20));
        

        $expiresAt = new \DateTime();
        $expiresAt->modify('+24 hours');

        $user = [
            'role_id'     => $this->request->getPost('role_id'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'full_name'   => $this->request->getPost('full_name'),
            'is_active'   => 0, 
            'email_verified' => 0,
            'activation_token' => $activationToken,
            'reset_token' => $activationToken, 
            'reset_token_expires' => $expiresAt->format('Y-m-d H:i:s')
        ];

        $user['password_hash'] = password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT);

        if ($this->userModel->save($user)) {
            $userId = $this->userModel->getInsertID();
            
            
            $newUser = $this->userModel->find($userId);
            
            // email combinado para activar cuenta y establecer contraseña
            $this->emailController->sendEmailToActivateAndSetPassword(
                $newUser['email'],
                'Activación de Cuenta y Establecer Contraseña - Anel Coworking',
                $newUser,
                $activationToken
            );
            
            $session->setFlashdata('success', 'Usuario creado exitosamente. Se ha enviado un correo de activación con instrucciones para establecer la contraseña.');
            return redirect()->to('admin/users');
        } else {
            $session->setFlashdata('error', 'Error al crear el usuario. Por favor, inténtelo de nuevo.');
            
            // Guardar los datos enviados y los errores para el formulario
            session()->setFlashdata('old_input', $this->request->getPost());
            session()->setFlashdata('errors', $this->userModel->errors());
            
            return redirect()->to('admin/users/new');
        }
    }

    public function adminEdit($id = null)
    {
        $session = session();
        $user = $this->userModel->find($id);

        if (!$user) {
            $session->setFlashdata('error', 'Usuario no encontrado.');
            return redirect()->to('admin/users');
        }

        $data = [
            'roles' => $this->roleModel->findAll(),
            'current_user' => $user,
            'errors' => session()->getFlashdata('errors'),
            'old_input' => session()->getFlashdata('old_input')
        ];

        return view('admin/users/form', $data);
    }

    public function adminUpdate($id = null)
    {
        $session = session();
        $user = $this->userModel->find($id);

        if (!$user) {
            $session->setFlashdata('error', 'Usuario no encontrado para actualizar.');
            return redirect()->to('admin/users');
        }
        
        
        $this->rulesRegister['username'] = str_replace('{user_id}', $id, $this->rulesRegister['username']);
        $this->rulesRegister['email']    = str_replace('{user_id}', $id, $this->rulesRegister['email']);

        if (! $this->validate($this->rulesRegister, $this->messagesRegister)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            
            // Guardar los datos enviados y los errores para el formulario
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('old_input', $this->request->getPost());

            return redirect()->back()
                            ->withInput() 
                            ->with('validation', $this->validator);
        }

        $userUpdate = [
            'role_id'     => $this->request->getPost('role_id'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'full_name'   => $this->request->getPost('full_name'),
            'is_active'   => $user['is_active'],
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $session->setFlashdata('errors', ['password' => 'La contraseña debe tener al menos 8 caracteres.']);
                session()->setFlashdata('old_input', $this->request->getPost());
                return redirect()->to('admin/users/edit/' . $id);
            }
            $userUpdate['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Procesar la imagen si se ha subido una
        $file = $this->request->getFile('profile_image');
        $imageUpdated = false;
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validar tipo y tamaño
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars/', $newName);

            // Añadir la imagen al array de datos a actualizar
            $userUpdate['profile_image'] = $newName;
            $imageUpdated = true;
        }

        $currentUserId = session()->get('user_id');
        $isCurrentUser = ($id == $currentUserId);

        if ($this->userModel->update($id, $userUpdate)) {
            if ($imageUpdated && $isCurrentUser) {
                $updatedUser = $this->userModel->find($id);
                
                
                session()->set([
                    'profile_image' => $updatedUser['profile_image'],
                    'full_name' => $updatedUser['full_name'],
                    'email' => $updatedUser['email']
                ]);
                
                
                session()->markAsTempdata('profile_refresh', 1, 300);
            }
            
            $session->setFlashdata('success', 'Usuario actualizado exitosamente.');
            
            
            if ($isCurrentUser) {
                return redirect()->to('admin/users?refresh=' . time());
            } else {
                return redirect()->to('admin/users');
            }
        } else {
            $session->setFlashdata('error', 'Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
            
            
            session()->setFlashdata('errors', $this->userModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
            
            return redirect()->to('admin/users/edit/' . $id);
        }
    }

    public function adminDelete($id = null)
    {
        $session = session();
        $user = $this->userModel->find($id);

        if (!$user) {
            $session->setFlashdata('error', 'Usuario no encontrado para eliminar.');
            return redirect()->to('admin/users');
        }
        
        
        if ($user['user_id'] == $session->get('user_id')) {
            $session->setFlashdata('error', 'No puedes eliminar tu propia cuenta.');
            return redirect()->to('admin/users');
        }
        

        if ($this->userModel->delete($id)) {
            $session->setFlashdata('success', 'Usuario eliminado exitosamente.');
        } else {
            $session->setFlashdata('error', 'Error al eliminar el usuario.');
        }
        return redirect()->to('admin/users');
    }
}