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
        'username',
        'email',
        'password_hash',
        'full_name',
        'role',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'email' => 'required|valid_email|is_unique[users.email,user_id,{user_id}]',
        'password_hash' => 'required',
        'full_name' => 'required|min_length[3]|max_length[100]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'max_length' => 'El nombre de usuario no puede exceder los 50 caracteres',
            'is_unique' => 'Este nombre de usuario ya está en uso'
        ],
        'email' => [
            'required' => 'El correo electrónico es obligatorio',
            'valid_email' => 'Debe proporcionar un correo electrónico válido',
            'is_unique' => 'Este correo electrónico ya está registrado'
        ],
        'full_name' => [
            'required' => 'El nombre completo es obligatorio',
            'min_length' => 'El nombre completo debe tener al menos 3 caracteres',
            'max_length' => 'El nombre completo no puede exceder los 100 caracteres'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        unset($data['data']['password']);

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