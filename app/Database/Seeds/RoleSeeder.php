<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'role_id' => 1,
                'role_name' => 'Admin',
                'role_description' => 'Administrador del sistema',
                'permissions' => '{"all":1}'
            ],
            [
                'role_id' => 2,
                'role_name' => 'User',
                'role_description' => 'Usuario estándar',
                'permissions' => '{"bookings":1}'
            ],
        ];

        // Insertar los datos
        $this->db->table('roles')->insertBatch($data);
    }
} 