<?php 

function get_room_messages() {
    return [
        'name' => [
            'required'    => 'El nombre de la sala es obligatorio.',
            'min_length'  => 'El nombre debe tener al menos 3 caracteres.',
            'max_length'  => 'El nombre no puede superar los 100 caracteres.',
        ],
        'description' => [
            'max_length'  => 'La descripción no puede superar los 500 caracteres.',
        ],
        'capacity' => [
            'integer'        => 'La capacidad debe ser un número entero.',
            'greater_than'   => 'La capacidad debe ser mayor que 0.',
        ],
        'floor' => [
            'max_length'  => 'El piso no puede superar los 20 caracteres.',
        ],
        'price_hour' => [
            'decimal'     => 'El precio por hora debe ser un número decimal.',
        ],
        'is_active' => [
            'in_list'     => 'El estado debe ser Activo o Inactivo.',
        ],
    ];
}

?>