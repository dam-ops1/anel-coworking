<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingResourceModel extends Model
{
    protected $table = 'resource_reservations';
    protected $primaryKey = 'reservation_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'space_reservation_id',
        'resource_id',
        'quantity',
        'start_time',
        'end_time',
        'status'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'space_reservation_id' => 'required|integer',
        'resource_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'start_time' => 'required|valid_date',
        'end_time' => 'required|valid_date'
    ];

    public function getBookingResources($reservationId)
    {
        return $this->select('resource_reservations.*, resources.resource_name as resource_name')
                    ->join('resources', 'resources.resource_id = resource_reservations.resource_id')
                    ->where('space_reservation_id', $reservationId)
                    ->findAll();
    }

    public function returnResources($reservationId)
    {
        $resources = $this->getBookingResources($reservationId);
        $resourceModel = new ResourceModel();
        
        foreach ($resources as $resource) {
            $resourceModel->updateQuantity($resource['resource_id'], $resource['quantity']);
        }

        return $this->where('space_reservation_id', $reservationId)->delete();
    }
} 