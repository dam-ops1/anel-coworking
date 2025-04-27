<?php 

function get_user_login_messege(): array
{
    return [
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe proporcionar un email válido'
        ],
        'password' => [
            'required' => 'La contraseña completo es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
    ];
}

function get_user_register_messege(): array
{
    return [

        'username' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'max_length' => 'El nombre de usuario no puede exceder los 50 caracteres',
            'is_unique' => 'Este nombre de usuario ya está en uso'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe proporcionar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ],
        'phone' => [
            'regex_match' => 'El número de teléfono debe tener un formato válido'
        ],
        'full_name' => [
            'required' => 'El nombre completo es obligatorio',
            'min_length' => 'El nombre completo debe tener al menos 3 caracteres',
            'max_length' => 'El nombre completo no puede exceder los 100 caracteres'
        ],
        'password' => [
            'required' => 'La contraseña completo es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'password_confirm' => [
            'required' => 'La contraseña completo es obligatoria',
            'matches' => 'La contraseña y la confirmación no coinciden'
        ],
        'company_name' => [
            'max_length' => 'El nombre de la compañia no puede exceder los 100 caracteres'
        ]
    ];
}



?>