<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'role_name' => 'admin',
            ],
            [
                'role_name' => 'user',
            ],
            [
                'role_name' => 'guest',
            ],
        ];

        // Using Query Builder
        $this->db->table('roles')->insertBatch($data);
    }
}
