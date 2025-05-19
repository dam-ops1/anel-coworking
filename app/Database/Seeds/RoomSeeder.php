<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Sala Ejecutiva',
                'description' => 'Sala ejecutiva elegante con capacidad para 6 personas, perfecta para reuniones de directorio.',
                'capacity' => 6,
                'floor' => 'Planta 1',
                'equipment' => 'Proyector, Pizarra, Videoconferencia, WiFi',
                'price_hour' => 35.00,
                'image' => 'room_default.jpg',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Sala de Conferencias',
                'description' => 'Amplia sala de conferencias con capacidad para 20 personas, ideal para presentaciones y eventos.',
                'capacity' => 20,
                'floor' => 'Planta 2',
                'equipment' => 'Proyector, Sistema de sonido, Micrófonos, Pizarra, WiFi',
                'price_hour' => 60.00,
                'image' => 'room_default.jpg',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Sala Creativa',
                'description' => 'Espacio dinámico y versátil con capacidad para 10 personas, perfecto para sesiones de brainstorming.',
                'capacity' => 10,
                'floor' => 'Planta 1',
                'equipment' => 'Pizarras, Material de diseño, Mobiliario flexible, WiFi',
                'price_hour' => 40.00,
                'image' => 'room_default.jpg',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Despacho Individual',
                'description' => 'Despacho privado para 1-2 personas, ideal para trabajo concentrado o entrevistas.',
                'capacity' => 2,
                'floor' => 'Planta 3',
                'equipment' => 'Escritorio, Teléfono, WiFi',
                'price_hour' => 20.00,
                'image' => 'room_default.jpg',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Insertar los datos
        $this->db->table('rooms')->insertBatch($data);
    }
} 