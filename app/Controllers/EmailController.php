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
            return $this->messageController->showMessage("Usuario Registrado", "Usuario registrado exitosamente, verifica tu correo para activar tu cuenta.", 'login', 'Iniciar Sesión');
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

    public function sendEmailToActivateAndSetPassword($to, $subject, $user, $token)
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $message = $this->getBodyActivateAndSetPassword($user, $token);
        $this->email->setMessage($message);

        if ($this->email->send()) {
            // Mostrar mensaje de éxito
            return $this->messageController->showMessage(
                "Usuario Creado", 
                "El usuario ha sido creado exitosamente y se le ha enviado un correo para verificar su cuenta y establecer una contraseña.", 
                'admin/users', 
                'Ver Usuarios'
            );
        } else {
            // Mostrar errores en caso de fallo
            return $this->messageController->showMessage(
                "Error", 
                "El usuario se creó pero hubo un problema al enviar el correo. Por favor, intente enviar el correo nuevamente.", 
                'admin/users', 
                'Ver Usuarios'
            );
        }
    }

    private function getBogyRegister($user)
    {
        $url = base_url('auth/activate/' . $user['activation_token']);

        $body = '<h1>Hola '. $user['username']. ', Bienvenido a Anel Coworking</h1>';
        $body .= '<p>Gracias por registrarte. Tu cuenta ha sido creada exitosamente.</p>';
        $body .= '<p>Para completar tu registro, debes verificar tu correo electrónico y establecer una contraseña segura para tu cuenta.</p>';
        $body .= "<p>Por favor, haz clic en el siguiente enlace para activar tu cuenta y establecer tu contraseña: <a href='$url'>Activar mi cuenta</a></p>";
        $body .= '<p>Este enlace caducará en 24 horas. Si no completas el registro en este tiempo, deberás solicitar un nuevo enlace de activación.</p>';
        $body .= '<p>Si no has solicitado esta cuenta, puedes ignorar este correo.</p>';
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

    private function getBodyActivateAndSetPassword($user, $token)
    {
        $url = base_url('auth/reset-password/' . $token);

        $body = '<h1>Bienvenido/a a Anel Coworking</h1>';
        $body .= '<p>Hola ' . ($user['full_name'] ?: $user['username']) . ',</p>';
        $body .= '<p>Se ha creado una cuenta para ti en Anel Coworking.</p>';
        $body .= '<p><strong>Nombre de usuario:</strong> ' . $user['username'] . '</p>';
        $body .= '<p><strong>Correo electrónico:</strong> ' . $user['email'] . '</p>';
        $body .= '<p>Para activar tu cuenta y establecer tu contraseña, por favor haz clic en el siguiente enlace:</p>';
        $body .= "<p><a href='$url' style='padding: 10px 15px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 5px;'>Activar cuenta y establecer contraseña</a></p>";
        $body .= '<p>Este enlace caducará en 24 horas. Si no completas el proceso en este tiempo, deberás contactar al administrador para solicitar un nuevo enlace.</p>';
        $body .= '<p>Si no esperabas este correo, por favor ignóralo o contacta al administrador.</p>';
        $body .= '<p>Saludos,<br>Anel Coworking</p>';

        return $body;
    }
    
    public function sendBookingConfirmation($to, $subject, $data)
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $message = $this->getBodyBookingConfirmation($data);
        $this->email->setMessage($message);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
    
    private function getBodyBookingConfirmation($data)
    {
        $url = base_url('bookings/confirmation/' . $data['booking_id']);
        
        $body = '<h1>Hola '. $data['user_name']. ', ¡Tu reserva ha sido confirmada!</h1>';
        $body .= '<div style="padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin: 20px 0;">';
        $body .= '<h2 style="color: #dc3545;">Detalles de la reserva</h2>';
        $body .= '<p><strong>Sala:</strong> ' . $data['room_name'] . '</p>';
        $body .= '<p><strong>Fecha de inicio:</strong> ' . $data['start_date'] . ' a las ' . $data['start_time'] . '</p>';
        $body .= '<p><strong>Fecha de fin:</strong> ' . $data['end_date'] . ' a las ' . $data['end_time'] . '</p>';
        $body .= '<p><strong>Precio total:</strong> €' . number_format($data['total_price'], 2) . '</p>';
        $body .= '</div>';
        $body .= "<p>Puedes ver los detalles de tu reserva en tu <a href='$url'>área personal</a>.</p>";
        $body .= '<p>Si necesitas modificar o cancelar tu reserva, por favor ponte en contacto con nosotros lo antes posible.</p>';
        $body .= '<p>Gracias por confiar en nosotros.</p>';
        $body .= '<p>Saludos,<br>Anel Coworking</p>';
        
        return $body;
    }
}