<?php

namespace App\Controllers;

use App\Models\RoomModel;

class RoomController extends BaseController
{
    protected $roomModel;
    
    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }
    
    /**
     * Pantalla principal con calendario generado 100 % en servidor.
     * Se admite query string ?offset=N (meses desplazados) y ?date=YYYY-MM-DD
     * para permitir la navegación y la selección de fechas sin JavaScript.
     */
    public function index()
    {
        // Cargar fechas guardadas en sesión (opcional para pre-rellenar el formulario)
        $data = [
            'startSelected' => session('cal_start'),
            'endSelected'   => session('cal_end'),
        ];

        return view('rooms/index', $data);
    }
    
    /**
     * Mostrar todas las salas
     */
    public function listRooms()
    {
        $data['rooms'] = $this->roomModel->getActiveRooms();
        
        return view('rooms/list', $data);
    }
    
    /**
     * Comprobar disponibilidad y mostrar salas disponibles
     */
    public function checkAvailability()
    {
        $startDate = $this->request->getPost('start_date');
        $startTime = $this->request->getPost('start_time');
        $endDate = $this->request->getPost('end_date');
        $endTime = $this->request->getPost('end_time');
        
        // Formatear fechas para la base de datos
        $startDateTime = date('Y-m-d H:i:s', strtotime("$startDate $startTime"));
        $endDateTime = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));
        
        // Validar fechas
        if (strtotime($startDateTime) >= strtotime($endDateTime)) {
            return redirect()->back()
                           ->with('error', 'La fecha de inicio debe ser anterior a la fecha de fin.');
        }
        
        // Obtener salas disponibles
        $data['rooms'] = $this->roomModel->getAvailableRooms($startDateTime, $endDateTime);
        $data['start_datetime'] = $startDateTime;
        $data['end_datetime'] = $endDateTime;
        
        // Guardar en sesión para uso posterior
        session()->set('booking_start_time', $startDateTime);
        session()->set('booking_end_time', $endDateTime);
        
        return view('rooms/available', $data);
    }
    
    /**
     * Mostrar detalles de una sala específica
     */
    public function details($id)
    {
        $data['room'] = $this->roomModel->getRoom($id);
        
        if (empty($data['room'])) {
            return redirect()->back()
                           ->with('error', 'Sala no encontrada.');
        }
        
        // Verificar si hay fechas seleccionadas en la sesión
        $data['start_datetime'] = session()->get('booking_start_time');
        $data['end_datetime'] = session()->get('booking_end_time');
        
        // Si hay fechas, verificar disponibilidad
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
} 