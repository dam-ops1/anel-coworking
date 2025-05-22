<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'room_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'capacity',
        'floor',
        'equipment',
        'price_hour',
        'image',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at'; 

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    /**
     * Obtener todas las salas activas
     */
    public function getActiveRooms()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * Obtener una sala por ID
     */
    public function getRoom($id)
    {
        return $this->find($id);
    }
    
    /**
     * Verificar disponibilidad de una sala para un rango de tiempo
     */
    public function checkAvailability($roomId, $startTime, $endTime)
    {
        $db = \Config\Database::connect();
        
        // Consultar si hay reservas que se solapen con el rango solicitado
        $query = $db->table('bookings')
            ->where('room_id', $roomId)
            ->where('status !=', 'cancelled')
            ->where('start_time <', $endTime)
            ->where('end_time >', $startTime)
            ->countAllResults();
            
        // Retorna true si no hay reservas en ese rango (está disponible)
        return ($query === 0);
    }
    
    /**
     * Obtener todas las salas disponibles para un rango de tiempo
     */
    public function getAvailableRooms($startTime, $endTime)
    {
        $db = \Config\Database::connect();
        
        // Subconsulta para obtener las salas con reservas en el rango
        $subQuery = $db->table('bookings')
            ->select('room_id')
            ->where('status !=', 'cancelled')
            ->where('start_time <', $endTime)
            ->where('end_time >', $startTime);
            
        // Consulta principal: todas las salas activas que no estén en la subconsulta
        return $this->where('is_active', 1)
            ->whereNotIn('room_id', $subQuery)
            ->findAll();
    }
} 