<?php

namespace App\Controllers;

use App\Database\Seeds\RoleSeeder;
use App\Models\RoleModel;
use App\Models\UserModel;
// Remove if not used elsewhere or replace with specific model if created for roles
// use App\Models\RoleModel; 

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel; 

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }


    public function adminIndex()
    {
            $data = [
                // Modificamos la consulta para evitar el error
                'users' => $this->userModel->findAll(),
                'show_form' => false
            ];

            // Si tenemos roles, los agregamos separadamente
            $data['users'] = $this->addRoleNamesToUsers($data['users']);

            return view('admin/users/index', $data);
    }

    public function adminNew()
    {
        try {
            $data = [
                'users' => $this->userModel->findAll(),
                'roles' => $this->roleModel->findAll(),
                'show_form' => true,
                'current_user' => null, 
                'errors' => session()->getFlashdata('errors'),
                'old_input' => session()->getFlashdata('old_input')
            ];

            // Si tenemos roles, los agregamos separadamente
            $data['users'] = $this->addRoleNamesToUsers($data['users']);

            return view('admin/users/index', $data);
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

    public function adminCreate()
    {
        $session = session();
        $validationRules = $this->userModel->getValidationRules([
            'password' => 'required|min_length[8]|max_length[255]' // Override to make password required for create
        ]);

        if (!$this->validate($validationRules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            return redirect()->to('admin/users/new')
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // Generate activation token
        $activationToken = bin2hex(random_bytes(20));

        $dataToSave = [
            'role_id'     => $this->request->getPost('role_id'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'full_name'   => $this->request->getPost('full_name'),
            'is_active'   => $this->request->getPost('is_active'),
            'profile_image' => 'default.jpg', // As per requirement
            'email_verified' => 0, // Users created by admin are not auto-verified
            'activation_token' => $activationToken
        ];

        // Hash password before saving
        $dataToSave['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        if ($this->userModel->save($dataToSave)) {
            // Get the ID of the newly created user
            $userId = $this->userModel->getInsertID();
            
            // Retrieve the full user data for the email
            $newUser = $this->userModel->find($userId);
            
            // Send activation email
            $emailController = new \App\Controllers\EmailController();
            $emailController->sendEmailToRegister(
                $newUser['email'],
                'Activación de Cuenta - Anel Coworking',
                $newUser
            );
            
            $session->setFlashdata('success', 'Usuario creado exitosamente. Se ha enviado un correo de activación.');
            return redirect()->to('admin/users');
        } else {
            $session->setFlashdata('error', 'Error al crear el usuario. Por favor, inténtelo de nuevo.');
            return redirect()->to('admin/users/new')
                             ->withInput()
                             ->with('errors', $this->userModel->errors());
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
                'users' => $this->userModel->findAll(),
                'roles' => $this->roleModel->findAll(),
                'show_form' => true,
                'current_user' => $user,
                'errors' => session()->getFlashdata('errors'), 
                'old_input' => session()->getFlashdata('old_input') 
            ];

            // Si tenemos roles, los agregamos separadamente
            $data['users'] = $this->addRoleNamesToUsers($data['users']);

            return view('admin/users/index', $data);
    }

    public function adminUpdate($id = null)
    {
        $session = session();
        $user = $this->userModel->find($id);

        if (!$user) {
            $session->setFlashdata('error', 'Usuario no encontrado para actualizar.');
            return redirect()->to('admin/users');
        }
        
        // For unique field validation, we need to pass the user_id to ignore
        $validationRules = $this->userModel->getValidationRules();
        // Adjust rules for is_unique to work with current ID
        $validationRules['username'] = str_replace('{user_id}', $id, $validationRules['username']);
        $validationRules['email']    = str_replace('{user_id}', $id, $validationRules['email']);

        if (!$this->validate($validationRules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            return redirect()->to('admin/users/edit/' . $id)
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $dataToUpdate = [
            'role_id'     => $this->request->getPost('role_id'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'full_name'   => $this->request->getPost('full_name'),
            'is_active'   => $this->request->getPost('is_active'),
            // profile_image is not updated here as per requirement
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 8) {
                 return redirect()->to('admin/users/edit/' . $id)
                             ->withInput()
                             ->with('errors', ['password' => 'La contraseña debe tener al menos 8 caracteres.']);
            }
            $dataToUpdate['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $dataToUpdate)) {
            $session->setFlashdata('success', 'Usuario actualizado exitosamente.');
            return redirect()->to('admin/users');
        } else {
            $session->setFlashdata('error', 'Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
            return redirect()->to('admin/users/edit/' . $id)
                             ->withInput()
                             ->with('errors', $this->userModel->errors());
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
        
        // Prevent deleting own account or superadmin (e.g., user_id 1 or specific role_id)
        if ($user['user_id'] == $session->get('user_id')) {
            $session->setFlashdata('error', 'No puedes eliminar tu propia cuenta.');
            return redirect()->to('admin/users');
        }
        // Add more checks if needed, e.g., prevent deleting user with role_id 1 (super admin)

        if ($this->userModel->delete($id)) {
            $session->setFlashdata('success', 'Usuario eliminado exitosamente.');
        } else {
            $session->setFlashdata('error', 'Error al eliminar el usuario.');
        }
        return redirect()->to('admin/users');
    }
}