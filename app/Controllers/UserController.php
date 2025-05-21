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
            'users' => $this->userModel->limit(10)->findAll()
        ];

        // Si tenemos roles, los agregamos separadamente
        $data['users'] = $this->addRoleNamesToUsers($data['users']);

        return view('admin/users/list', $data);
    }

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

    public function adminCreate()
    {
        $session = session();
        $validationRules = $this->userModel->getValidationRules([
            'password' => 'required|min_length[6]|max_length[255]' // Override to make password required for create
        ]);

        if (!$this->validate($validationRules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            
            // Guardar los datos enviados y los errores para el formulario
            session()->setFlashdata('old_input', $this->request->getPost());
            session()->setFlashdata('errors', $this->validator->getErrors());
            
            return redirect()->to('admin/users/new');
        }

        // Generate tokens for activation and password reset
        $activationToken = bin2hex(random_bytes(20));
        
        // Create reset token expiration (24 hours)
        $expiresAt = new \DateTime();
        $expiresAt->modify('+24 hours');

        $dataToSave = [
            'role_id'     => $this->request->getPost('role_id'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'full_name'   => $this->request->getPost('full_name'),
            'is_active'   => 0, // Set default value to inactive until email verification
            'email_verified' => 0,
            'activation_token' => $activationToken,
            'reset_token' => $activationToken, // Use the same token for simplicity
            'reset_token_expires' => $expiresAt->format('Y-m-d H:i:s')
        ];

        // Hash password before saving - using a placeholder password since the user will set their own
        $dataToSave['password_hash'] = password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT);

        if ($this->userModel->save($dataToSave)) {
            // Get the ID of the newly created user
            $userId = $this->userModel->getInsertID();
            
            // Retrieve the full user data for the email
            $newUser = $this->userModel->find($userId);
            
            // Send combined verification and password reset email
            $emailController = new \App\Controllers\EmailController();
            $emailController->sendEmailToActivateAndSetPassword(
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
        
        // For unique field validation, we need to pass the user_id to ignore
        $validationRules = $this->userModel->getValidationRules();
        // Adjust rules for is_unique to work with current ID
        $validationRules['username'] = str_replace('{user_id}', $id, $validationRules['username']);
        $validationRules['email']    = str_replace('{user_id}', $id, $validationRules['email']);

        if (!$this->validate($validationRules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            
            // Guardar los datos enviados y los errores para el formulario
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('old_input', $this->request->getPost());
            
            return redirect()->to('admin/users/edit/' . $id);
        }

        $dataToUpdate = [
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
            $dataToUpdate['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Procesar la imagen si se ha subido una
        $file = $this->request->getFile('profile_image');
        $imageUpdated = false;
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validar tipo y tamaño
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars/', $newName);

            // Añadir la imagen al array de datos a actualizar
            $dataToUpdate['profile_image'] = $newName;
            $imageUpdated = true;
        }

        $currentUserId = session()->get('user_id');
        $isCurrentUser = ($id == $currentUserId);

        if ($this->userModel->update($id, $dataToUpdate)) {
            // Si se actualizó la imagen y es el usuario actual, actualizar la sesión
            if ($imageUpdated && $isCurrentUser) {
                // Obtener datos actualizados del usuario
                $updatedUser = $this->userModel->find($id);
                
                // Actualizar la sesión
                session()->set([
                    'profile_image' => $updatedUser['profile_image'],
                    'full_name' => $updatedUser['full_name'],
                    'email' => $updatedUser['email']
                ]);
                
                // Forzar que la sesión se guarde inmediatamente
                session()->markAsTempdata('profile_refresh', 1, 300);
            }
            
            $session->setFlashdata('success', 'Usuario actualizado exitosamente.');
            
            // Si es el usuario actual, añadir un parámetro para forzar la recarga completa
            if ($isCurrentUser) {
                return redirect()->to('admin/users?refresh=' . time());
            } else {
                return redirect()->to('admin/users');
            }
        } else {
            $session->setFlashdata('error', 'Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
            
            // Guardar los datos enviados y los errores para el formulario
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