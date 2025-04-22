<?php

namespace App\Models;

use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = 'resource_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'resource_name',
        'resource_type',
        'quantity_available',
        'description',
        'image',
        'requires_approval',
        'is_active'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'resource_name' => 'required|min_length[3]|max_length[255]',
        'resource_type' => 'required|max_length[255]',
        'quantity_available' => 'required|integer|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'resource_name' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 255 caracteres'
        ],
        'quantity_available' => [
            'required' => 'La cantidad es obligatoria',
            'integer' => 'La cantidad debe ser un número entero',
            'greater_than_equal_to' => 'La cantidad no puede ser negativa'
        ]
    ];

    public function getAvailableResources()
    {
        return $this->where('is_active', true)
                    ->where('quantity_available >', 0)
                    ->findAll();
    }

    public function updateQuantity($resourceId, $quantity)
    {
        $resource = $this->find($resourceId);
        if ($resource) {
            $newQuantity = $resource['quantity_available'] + $quantity;
            if ($newQuantity < 0) {
                $newQuantity = 0;
            }
            
            return $this->update($resourceId, [
                'quantity_available' => $newQuantity,
                'is_active' => ($newQuantity > 0)
            ]);
        }
        return false;
    }
} 