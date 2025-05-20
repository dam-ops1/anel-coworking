<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'role_id',
        'username',
        'email',
        'password_hash',
        'full_name',
        'phone',
        'profile_image',
        'created_at',
        'updated_at',
        'last_login',
        'is_active',
        'email_verified',
        'activation_token',
        'reset_token',
        'reset_token_expires',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation Rules for Admin CRUD
    protected $validationRules = [
        'role_id'     => 'required|integer',
        'username'    => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'email'       => 'required|valid_email|max_length[255]|is_unique[users.email,user_id,{user_id}]',
        'full_name'   => 'permit_empty|max_length[100]',
        'password'    => 'permit_empty|min_length[8]|max_length[255]', // Required on create, optional on update
        'is_active'   => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'El nombre de usuario ya existe.'
        ],
        'email' => [
            'is_unique' => 'El correo electrónico ya está registrado.',
            'valid_email' => 'Por favor, introduce un correo electrónico válido.'
        ],
        'password' => [
            'min_length' => 'La contraseña debe tener al menos 8 caracteres.'
        ]
    ];

    public function createUser($userData)
    {
        
        // Genera el hash de la contraseña
        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password'], $userData['password_confirm']);
        }

        // agregamos el role_id por defecto
        $userData['role_id'] = 2;

        // Generar un token de activación
        $this->addTokentoUser($userData);


        // Generar la fecha de creación
        $userData['created_at'] = date('Y-m-d H:i:s');


        // Intentar guardar y si falla retornar false
        if (! $this->save($userData)) return null;

        return $this->getInsertID();
    }

    private function addTokentoUser(&$userData)
    {
        // Generar un token único para la activación de la cuenta
        $token = bin2hex(random_bytes(20));

        $userData['activation_token'] = $token;
    }

    private function getResetToken(&$userData)
    {
        // Generar un token único para el restablecimiento de la contraseña
        $token = bin2hex(random_bytes(20));
        $userData['reset_token'] = $token;
        $userData['reset_token_expires'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
    }
    
    private function getActivationToken($userData)
    {
        // Generar un token único para la activación de la cuenta
        $token = bin2hex(random_bytes(20));
        $userData['activation_token'] = $token;
    }

    /**
     * Verifica si el usuario existe y si la contraseña es correcta, 
     * además verifica si el usuario esta activo
     * Por último actualiza el estado del usuario a activo
     * 
     * @param string $email
     * @param string $pass
     * @return array|null
     * 
     * */

    public function verifyUser($email, $pass)
    {

        
        $user = $this->where(['email' => $email, 'email_verified' => 1])->first();

        if (!$user || !password_verify($pass, $user['password_hash'])) return null;
        if ($user['email_verified'] == 0) return null;

        $this->update($user['user_id'], [
            'is_active' => 1,
            'last_login' => date('Y-m-d H:i:s')
        ]);

        return $user;
    }

    private function getUserById($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    // actualiza los datos del incio de sesion del usuario
    public function logoutUser($userId)
    {
        $this->update($userId, [
            'is_active' => 0,
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    // Recibir todos los usuarios
    public function getAllUsers()
    {
        return $this->orderBy('created_at', 'DESC')->findAll(); 
    }

}