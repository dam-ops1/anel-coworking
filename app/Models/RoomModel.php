<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'spaces';
    protected $primaryKey = 'space_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'space_name',
        'space_type',
        'capacity',
        'description',
        'location',
        'has_projector',
        'has_whiteboard',
        'image',
        'floor',
        'is_active'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'space_name' => 'required|min_length[3]|max_length[255]',
        'capacity' => 'permit_empty|integer',
        'space_type' => 'required|in_list[sala,puesto_trabajo,espacio_comun]'
    ];

    protected $validationMessages = [
        'space_name' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 255 caracteres'
        ]
    ];

    public function getAvailableRooms($date, $startTime, $endTime)
    {
        return $this->where('is_active', true)
                    ->whereNotIn('space_id', function($builder) use ($date, $startTime, $endTime) {
                        $builder->select('space_id')
                               ->from('space_reservations')
                               ->where('DATE(start_time)', $date)
                               ->where('start_time <', $endTime)
                               ->where('end_time >', $startTime)
                               ->where('status', 'confirmada');
                    })
                    ->findAll();
    }

    public function updateOccupancy($spaceId, $status)
    {
        return $this->update($spaceId, ['is_active' => ($status === 'disponible')]);
    }
} 