<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\RoomModel;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $roomModel;
    protected $emailController;
    
    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->emailController = new EmailController();
    }
    

    public function index()
    {
        $userId = session()->get('user_id');
        
        // Obtener reservas con información de salas
        $data['bookings'] = $this->bookingModel->getUserBookingsWithRooms($userId);
        
        return view('bookings/index', $data);
    }
    

    public function create()
    {
        $roomId = $this->request->getPost('room_id');
        $startDatetime = session()->get('booking_start_time');
        $endDatetime = session()->get('booking_end_time');
        $notes = $this->request->getPost('notes');
        
        // Verificar que tenemos los datos necesarios
        if (!$roomId || !$startDatetime || !$endDatetime) {
            return redirect()->to('rooms')
                         ->with('error', 'Datos de reserva incompletos. Por favor, seleccione fechas y sala.');
        }
        

        $isAvailable = $this->roomModel->checkAvailability($roomId, $startDatetime, $endDatetime);
        
        if (!$isAvailable) {
            return redirect()->to('rooms/details/'.$roomId)
                         ->with('error', 'Lo sentimos, esta sala ya no está disponible para el horario seleccionado.');
        }
        

        $room = $this->roomModel->getRoom($roomId);
        

        $duration = (strtotime($endDatetime) - strtotime($startDatetime)) / 3600;
        

        $totalPrice = $room['price_hour'] * $duration;
        

        $bookingData = [
            'user_id'     => session()->get('user_id'),
            'room_id'     => $roomId,
            'start_time'  => $startDatetime,
            'end_time'    => $endDatetime,
            'total_price' => $totalPrice,
            'status'      => 'confirmed',
            'notes'       => $notes
        ];
        

        $bookingId = $this->bookingModel->createBooking($bookingData);
        
        if (!$bookingId) {
            return redirect()->to('rooms/details/'.$roomId)
                         ->with('error', 'Error al crear la reserva. Por favor, inténtelo de nuevo.');
        }
        

        $booking = $this->bookingModel->getBooking($bookingId);
        

        $this->sendConfirmationEmail($booking, $room);
        

        session()->remove('booking_start_time');
        session()->remove('booking_end_time');
        

        return redirect()->to('bookings/confirmation/'.$bookingId);
    }
    

    public function confirmation($bookingId)
    {
        $booking = $this->bookingModel->getBooking($bookingId);
        
        if (empty($booking) || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('bookings')
                         ->with('error', 'Reserva no encontrada.');
        }
        
        $data['booking'] = $booking;
        $data['room'] = $this->roomModel->getRoom($booking['room_id']);
        
        return view('bookings/confirmation', $data);
    }
    

    public function cancel($bookingId)
    {
        $booking = $this->bookingModel->getBooking($bookingId);
        
        if (empty($booking) || $booking['user_id'] != session()->get('user_id')) {
            return redirect()->to('bookings')
                         ->with('error', 'Reserva no encontrada.');
        }
        

        $this->bookingModel->cancelBooking($bookingId);
        
        return redirect()->to('bookings')
                       ->with('success', 'Reserva cancelada correctamente.');
    }
    

    private function sendConfirmationEmail($booking, $room)
    {
        $userEmail = session()->get('email');
        $userName = session()->get('full_name');
        

        $startDate = date('d/m/Y', strtotime($booking['start_time']));
        $startTime = date('H:i', strtotime($booking['start_time']));
        $endDate = date('d/m/Y', strtotime($booking['end_time']));
        $endTime = date('H:i', strtotime($booking['end_time']));
        

        $emailData = [
            'booking_id' => $booking['booking_id'],
            'room_name' => $room['name'],
            'start_date' => $startDate,
            'start_time' => $startTime,
            'end_date' => $endDate,
            'end_time' => $endTime,
            'total_price' => $booking['total_price'],
            'user_name' => $userName
        ];
        

        $this->emailController->sendBookingConfirmation(
            $userEmail,
            'Confirmación de reserva - ' . $room['name'],
            $emailData
        );
    }
} 