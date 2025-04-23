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
    protected $beforeInsert  = ['hashPassword'];
    protected $beforeUpdate  = ['hashPassword'];

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

    protected $validationRules = [
        'username'         => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'email'            => 'required|valid_email|is_unique[users.email]',
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
        'full_name'        => 'required|min_length[3]|max_length[50]',
        'phone'            => 'permit_empty|regex_match[/^\d{9,10}$/]',
        'company_name'     => 'permit_empty|max_length[100]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'max_length' => 'El nombre de usuario no puede exceder los 50 caracteres',
            'is_unique' => 'Este nombre de usuario ya está en uso'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe proporcionar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ],
        'phone' => [
            'regex_match' => 'El número de teléfono debe tener un formato válido'
        ],
        'full_name' => [
            'required' => 'El nombre completo es obligatorio',
            'min_length' => 'El nombre completo debe tener al menos 3 caracteres',
            'max_length' => 'El nombre completo no puede exceder los 100 caracteres'
        ]
    ];

    protected function hashPassword(array $data)
    {
        if (! empty($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );
            unset($data['data']['password'], $data['data']['password_confirm']);
        }
        return $data;
    }

    public function createUser($userData)
    {
        // Asegurarse de que la contraseña se hashee
        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password']);
        }

        return $this->insert($userData);
    }
} 