<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'space_reservations';
    protected $primaryKey = 'reservation_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'space_id',
        'start_time',
        'end_time',
        'status',
        'purpose',
        'attendees',
        'notes'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'user_id' => 'required|integer',
        'space_id' => 'required|integer',
        'start_time' => 'required|valid_date',
        'end_time' => 'required|valid_date',
        'status' => 'required|in_list[pendiente,confirmada,cancelada]'
    ];

    public function getBookingsByDate($date)
    {
        return $this->select('space_reservations.*, spaces.space_name as room_name, users.full_name as user_name')
                    ->join('spaces', 'spaces.space_id = space_reservations.space_id')
                    ->join('users', 'users.user_id = space_reservations.user_id')
                    ->where('DATE(start_time)', $date)
                    ->findAll();
    }

    public function getUserBookings($userId)
    {
        return $this->select('space_reservations.*, spaces.space_name as room_name')
                    ->join('spaces', 'spaces.space_id = space_reservations.space_id')
                    ->where('space_reservations.user_id', $userId)
                    ->orderBy('start_time', 'DESC')
                    ->findAll();
    }

    public function checkAvailability($spaceId, $startTime, $endTime)
    {
        $existingBooking = $this->where('space_id', $spaceId)
                               ->where('status', 'confirmada')
                               ->where('start_time <', $endTime)
                               ->where('end_time >', $startTime)
                               ->first();
        
        return empty($existingBooking);
    }

    public function createBookingWithResources($bookingData, $resources)
    {
        $this->db->transStart();

        // Insertar la reserva
        $this->insert($bookingData);
        $bookingId = $this->insertID();

        // Insertar los recursos asociados
        $resourceModel = new ResourceModel();
        $bookingResourceModel = new \App\Models\BookingResourceModel();

        foreach ($resources as $resource) {
            $bookingResourceModel->insert([
                'reservation_id' => $bookingId,
                'resource_id' => $resource['id'],
                'quantity' => $resource['quantity']
            ]);

            // Actualizar la cantidad disponible del recurso
            $resourceModel->updateQuantity($resource['id'], -$resource['quantity']);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
} 