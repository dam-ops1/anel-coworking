<?php
function get_user_register_rules(): array
{
    return [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
        'full_name' => 'required|min_length[3]|max_length[50]',
        'phone' => 'permit_empty|regex_match[/^\d{9,10}$/]',
        'company_name' => 'permit_empty|max_length[100]',
    ];
}


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

function get_user_profile_rules() {
    return [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]',
        'phone'     => 'permit_empty|regex_match[/^[0-9\-\+\s\(\)]*$/]|max_length[20]',
    ];
}



?>