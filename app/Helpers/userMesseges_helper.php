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

// Funciones para los mensajes de validación de olvido de contraseña

function get_user_forgot_password_messege(): array
{
    return [
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe proporcionar un email válido'
        ],
    ];
}

function get_user_reset_password_messege(): array
{
    return [
        'password' => [
            'required' => 'La contraseña completo es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'confirm_password' => [
            'required' => 'La confirmación de la contraseña es obligatoria',
            'matches' => 'La contraseña y la confirmación no coinciden'
        ],
    ];
}

function get_user_profile_messages() {
    return [
        'full_name' => [
            'required'    => 'El nombre es obligatorio.',
            'min_length'  => 'El nombre debe tener al menos 3 caracteres.',
            'max_length'  => 'El nombre no puede superar los 100 caracteres.',
        ],
        'email' => [
            'required'    => 'El correo es obligatorio.',
            'valid_email' => 'Debes ingresar un correo válido.',
            'max_length'  => 'El correo no puede superar los 100 caracteres.',
            'is_unique' => 'Este correo electrónico ya está registrado.',
        ],
        'phone' => [
            'regex_match' => 'El teléfono solo puede contener números y símbolos válidos.',
            'max_length'  => 'El teléfono no puede superar los 20 caracteres.',
        ],
    ];
}

function get_user_register_messege() {
    return [
        'username' => [
            'is_unique' => 'El nombre de usuario ya existe.',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres.',
        ],
        'email' => [
            'is_unique' => 'El correo electrónico ya está registrado.',
            'valid_email' => 'Por favor, introduce un correo electrónico válido.'
        ],
        'password' => [
            'min_length' => 'La contraseña debe tener al menos 6 caracteres.'
        ]
    ];
}






?>