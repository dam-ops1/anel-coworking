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
        'company_name',
        'bio',
        'profile_image',
        'services_offered',
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
    if (isset($userData['password'])) {
        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        unset($userData['password'], $userData['password_confirm']);
    }

    // Intentar guardar
    if (! $this->save($userData)) {
        // Si la operación no es exitosa, capturamos el error
        $errors = $this->errors();  // Obtiene los errores
        log_message('error', 'Error al guardar el usuario: ' . json_encode($errors));
        return false;
    }

    return true;
}


    public function getRules()
    {
        return $this->validationRules;
    }

    public function getMessages()
    {
        return $this->validationMessages;
    }
}