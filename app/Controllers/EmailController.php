<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    protected $messageController;
    protected $email;

    public function __construct()
    {
        $this->messageController = new MessageController();
        $this->email = \Config\Services::email();

        // Configuración del correo
        $this->email->setFrom('anelcoworking@gmail.com', 'Anel Coworking');
        
    }

    public function sendEmailToRegister($to, $subject, $user)
    {
        

        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $message = $this->getBogyRegister($user);
        $this->email->setMessage($message);
                
        if ($this->email->send()) {
            // Mostrar mensaje de éxito
            return $this->messageController->showMessage("Usuario Verificado", "El usuario ha sido verificado exitosamente.", 'login', 'Iniciar Sesión');
        } else {
            // Mostrar errores en caso de fallo
            $this->messageController->showMessage("Error", "Ha ocurrido un error intentelo de nuevo más tarde.", 'register', 'Registro');
        }


                
            
    }

    public function sendEmailToResetPassword($to, $subject, $user, $token)
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $message = $this->getBodyResetPassword($user, $token);
        $this->email->setMessage($message);

        if ($this->email->send()) {
            // Mostrar mensaje de éxito
            return $this->messageController->showMessage("Correo Enviado", "El correo para cambiar la contraseña ha sido enviado exitosamente.", 'login', 'Iniciar Sesión');
        } else {
            // Mostrar errores en caso de fallo
            $this->messageController->showMessage("Error", "Ha ocurrido un error intentelo de nuevo más tarde.", 'login', 'Iniciar Sesión');
        }
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

    private function getBodyResetPassword($user, $token)
    {
        $url = base_url('auth/forgot-password/' . $token);

        $body = '<h1>Hola '. $user['username']. ', Has solicitado un reinicio de contraseña</h1>';
        $body .= '<p>Si no has solicitado este cambio, ignora este mensaje.</p>';
        $body .= "<p>Para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='$url'>Restablecer Contraseña</a></p>";
        $body .= '<p>Si tienes alguna pregunta, no dudes en contactarnos.</p>';
        $body .= '<p>Saludos,<br>Anel Coworking</p>';

        return $body;
    }
}