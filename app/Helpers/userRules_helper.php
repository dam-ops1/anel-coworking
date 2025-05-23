<?php

function get_user_login_rules(): array
{
    return [
        'email' => 'required|valid_email',
        'password' => 'required|min_length[6]',
    ];
}

// Funciones para las reglas de validación de usuario
function get_user_forgot_password_rules(): array
{
    return [
        'email' => 'required|valid_email',
    ];
}

function get_user_reset_password_rules(): array
{
    return [
        'password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]',
    ];
}

function get_user_profile_rules($userId = null) {
    $uniqueEmailRule = 'is_unique[users.email]';

    // Si se está actualizando el perfil, evita el conflicto consigo mismo
    if ($userId !== null) {
        $uniqueEmailRule = "is_unique[users.email, user_id,{$userId}]";
    }

    return [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email'     => "required|valid_email|max_length[100]|{$uniqueEmailRule}",
        'phone'     => 'permit_empty|regex_match[/^[0-9\-\+\s\(\)]*$/]|max_length[20]',
    ];
}

function get_user_register_rules() {
    return [
        'role_id'     => 'required|integer',
        'username'    => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'email'       => 'required|valid_email|max_length[255]|is_unique[users.email,user_id,{user_id}]',
        'full_name'   => 'permit_empty|max_length[100]',
        'password'    => 'permit_empty|min_length[6]|max_length[255]', 
    ];
}



?>