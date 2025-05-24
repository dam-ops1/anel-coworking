<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table      = 'bookings';
    protected $primaryKey = 'booking_id';
    
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    
    protected $allowedFields = [
        'user_id', 'room_id', 'start_time', 'end_time', 
        'total_price', 'status', 'notes'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Crear una nueva reserva
     */
    public function createBooking($data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }
    
    /**
     * Obtener una reserva por ID
     */
    public function getBooking($id)
    {
        return $this->find($id);
    }
    
    /**
     * Obtener reservas de un usuario
     */
    public function getUserBookings($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
    
    /**
     * Obtener reservas de una sala
     */
    public function getRoomBookings($roomId)
    {
        return $this->where('room_id', $roomId)
                   ->where('status !=', 'cancelled')
                   ->orderBy('start_time', 'ASC')
                   ->findAll();
    }
    
    /**
     * Obtener reservas activas (pendientes o confirmadas) de un usuario
     */
    public function getActiveUserBookings($userId)
    {
        return $this->where('user_id', $userId)
                   ->whereIn('status', ['pending', 'confirmed'])
                   ->orderBy('start_time', 'ASC')
                   ->findAll();
    }
    
    /**
     * Obtener reservas con información de salas para un usuario
     */
    public function getUserBookingsWithRooms($userId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('bookings b');
        $builder->select('b.*, r.name as room_name, r.image as room_image, r.capacity, r.floor');
        $builder->join('rooms r', 'r.room_id = b.room_id');
        $builder->where('b.user_id', $userId);
        $builder->orderBy('b.start_time', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Obtener todas las reservas con información de salas y usuarios para administración
     */
    public function getAllBookingsWithDetails()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('bookings b');
        $builder->select('b.*, r.name as room_name, r.image as room_image, r.capacity, r.floor, u.full_name as user_name, u.email as user_email');
        $builder->join('rooms r', 'r.room_id = b.room_id');
        $builder->join('users u', 'u.user_id = b.user_id');
        $builder->orderBy('b.start_time', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Cancelar una reserva
     */
    public function cancelBooking($bookingId)
    {
        return $this->update($bookingId, ['status' => 'cancelled']);
    }
    
    /**
     * Confirmar una reserva
     */
    public function confirmBooking($bookingId)
    {
        return $this->update($bookingId, ['status' => 'confirmed']);
    }
} 