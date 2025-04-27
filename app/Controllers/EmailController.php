<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    public function sendEmailToRegister($to, $subject, $user)
    {
        // Cargar la librería de email
        $email = \Config\Services::email();

        // Configuración del correo
        $email->setFrom('anelcoworking@gmail.com', 'Anel Coworking');
        $email->setTo($to);
        $email->setSubject($subject);
        $message = $this->getBogyRegister($user);
        $email->setMessage($message);
        // Enviar correo
        if ($email->send()) {
            $title = 'Registro Exitoso';
            $message = 'Tu cuenta ha sido creada correctamente. Revisa tu correo para iniciar sesión.';
            // Mostrar mensaje de éxito
            return $this->showMessage($title, $message);
        } else {
            // Mostrar errores en caso de fallo
            echo $email->printDebugger(['headers']);
        }
    }

    private function showMessage($title, $message)
    {
        $data = [
            'title' => $title,
            'message' => $message
        ];
        
        return view('message', $data);
    }

    private function getBogyRegister($user)
    {
        $url = base_url('auth/activate/' . $user['activation_token']);

        $body = '<h1>Hola '. $user['username']. ', Bienvenido a Anel Coworking</h1>';
        $body .= '<p>Gracias por registrarte. Tu cuenta ha sido creada exitosamente.</p>';
        $body .= "<p>Por favor, verifica tu correo electrónico para activar tu cuenta. <a href='$url'>Activa tu Cuenta</a> </p>";
        $body .= '<p>Si tienes alguna pregunta, no dudes en contactarnos.</p>';
        $body .= '<p>Saludos,<br>Anel Coworking</p>';

        return $body;
    }
}