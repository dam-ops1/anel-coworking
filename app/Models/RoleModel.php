<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'role_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description' // Agregar otros campos según la estructura real de la tabla
    ];

    // Dates
    protected $useTimestamps = false;
    // Si la tabla tiene timestamps, descomentar estas líneas
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name,role_id,{role_id}]',
    ];
    
    protected $validationMessages   = [
        'name' => [
            'required' => 'El nombre del rol es obligatorio.',
            'min_length' => 'El nombre del rol debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre del rol no puede exceder los 50 caracteres.',
            'is_unique' => 'Este nombre de rol ya existe.'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener todos los roles
     */
    public function getAllRoles()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Obtener un rol específico por su ID
     */
    public function getRole($id)
    {
        return $this->find($id);
    }

    /**
     * Obtener un rol por su nombre
     */
    public function getRoleByName($name)
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Verificar si un rol es un rol de administrador
     */
    public function isAdminRole($roleId)
    {
        $role = $this->find($roleId);
        // Asumiendo que el rol de administrador tiene role_id = 1 o name = 'Administrator'
        // Ajustar según la estructura real de tu sistema
        return ($role && ($role['role_id'] == 1 || strtolower($role['name']) == 'administrador'));
    }
} 