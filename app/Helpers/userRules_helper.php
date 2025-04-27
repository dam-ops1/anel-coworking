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

?>