<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password_hash' => password_hash('Anel2025*', PASSWORD_DEFAULT),
                'full_name' => 'Administrador',
                'role_id' => 1, // Admin
                'is_active' => 1,
                'email_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'usuario',
                'email' => 'swoppad25@gmail.com',
                'password_hash' => password_hash('aaaaaa', PASSWORD_DEFAULT),
                'full_name' => 'Usuario Prueba',
                'role_id' => 2, // User
                'is_active' => 1,
                'email_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Verificar si el usuario ya existe antes de insertar
        foreach ($data as $user) {
            $existingUser = $this->db->table('users')
                                     ->where('email', $user['email'])
                                     ->get()
                                     ->getRowArray();
            
            if (!$existingUser) {
                $this->db->table('users')->insert($user);
            }
        }
    }
} 