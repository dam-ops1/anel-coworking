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

    public function createUser($userData)
    {
        
        // Genera el hash de la contraseña
        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password'], $userData['password_confirm']);
        }

        // agregamos el role_id por defecto
        $userData['role_id'] = 2;
        
        // Establecer email_verified a 0 explícitamente
        $userData['email_verified'] = 0;
        
        // Establecer is_active a 0 explícitamente
        $userData['is_active'] = 0;

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

    public function verifyUser($email, $pass)
    {
        $user = $this->where('email', $email)->first();

        if (!$user || !password_verify($pass, $user['password_hash'])) return null;

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