<?php

namespace App\Controllers;

use App\Models\RoomModel;
use DateTime;

class RoomController extends BaseController
{
    protected $roomModel;
    
    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }
    

    public function index()
    {
        $today = date('Y-m-d');
        
        // Asegurarse que la fecha mínima no sea fin de semana
        $dayOfWeek = date('w', strtotime($today));
        if ($dayOfWeek == 0) { // Domingo
            $today = date('Y-m-d', strtotime($today . ' +1 day')); // Lunes
        } elseif ($dayOfWeek == 6) { // Sábado
            $today = date('Y-m-d', strtotime($today . ' +2 days')); // Lunes
        }

        $isModify = $this->request->getGet('modify');

        if (! $isModify) {
            
            session()->remove(['cal_start', 'cal_end', 'booking_start_time', 'booking_end_time']);
            
            $startSelected = '';
            $endSelected = '';
        } else {
            
            $startSelected = session('cal_start') ?: $today;
            $endSelected   = session('cal_end')   ?: $startSelected;
            
            if ($startSelected < $today) {
                $startSelected = $today;
            }
            if ($endSelected < $startSelected) {
                $endSelected = $startSelected;
            }
        }

        return view('rooms/index', [
            'startSelected' => $startSelected,
            'endSelected'   => $endSelected,
            'minDate'       => $today,
        ]);
    }
    
    public function listRooms()
    {
        $data['rooms'] = $this->roomModel->getActiveRooms();
        
        return view('rooms/list', $data);
    }
    
    public function checkAvailability()
    {
        $startDate = $this->request->getPost('start_date');
        $startTime = $this->request->getPost('start_time');
        $endDate = $this->request->getPost('end_date');
        $endTime = $this->request->getPost('end_time');
        
        // Validar campos requeridos
        if (empty($startDate) || empty($endDate)) {
            return redirect()->back()
                ->with('error', 'Debes seleccionar fechas de inicio y fin.');
        }
        
        // Validar que ningún día en el rango sea fin de semana
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
        
        while ($currentDate <= $endDateObj) {
            $dayOfWeek = (int)$currentDate->format('w'); // 0 = domingo, 6 = sábado
            
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                return redirect()->back()
                    ->with('error', 'El período seleccionado incluye fines de semana (sábado o domingo). Solo se permiten reservas en días laborables (lunes a viernes).');
            }
            
            // Avanzar al siguiente día
            $currentDate->modify('+1 day');
        }
        
        $startDateTime = date('Y-m-d H:i:s', strtotime("$startDate $startTime"));
        $endDateTime = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));
        
        if (strtotime($startDateTime) >= strtotime($endDateTime)) {
            return redirect()->back()
                ->with('error', 'La fecha de inicio debe ser anterior a la fecha de fin.');
        }

        session()->set('cal_start', $startDate);
        session()->set('cal_end', $endDate);
        session()->set('booking_start_time', $startDateTime);
        session()->set('booking_end_time', $endDateTime);

        // En lugar de renderizar la vista directamente, redirigir a una ruta GET
        return redirect()->to('rooms/available');
    }
    
    public function details($id)
    {
        $data['room'] = $this->roomModel->getRoom($id);
        
        if (empty($data['room'])) {
            return redirect()->back()
                           ->with('error', 'Sala no encontrada.');
        }
        
        $data['start_datetime'] = session()->get('booking_start_time');
        $data['end_datetime'] = session()->get('booking_end_time');
        
        if ($data['start_datetime'] && $data['end_datetime']) {
            $data['is_available'] = $this->roomModel->checkAvailability(
                $id, 
                $data['start_datetime'], 
                $data['end_datetime']
            );
        } else {
            $data['is_available'] = false;
        }
        
        return view('rooms/details', $data);
    }

    public function showAvailableRooms()
    {

        $startDateTime = session()->get('booking_start_time');
        $endDateTime = session()->get('booking_end_time');
        
        if (!$startDateTime || !$endDateTime) {
            return redirect()->to('bookings')
                           ->with('error', 'Por favor, selecciona primero las fechas y horas.');
        }
        

        $data['rooms'] = $this->roomModel->getAvailableRooms($startDateTime, $endDateTime);
        $data['start_datetime'] = $startDateTime;
        $data['end_datetime'] = $endDateTime;
        
        return view('rooms/available', $data);
    }
} 