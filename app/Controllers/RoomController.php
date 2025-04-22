<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\BookingModel;
use CodeIgniter\RESTful\ResourceController;

class RoomController extends ResourceController
{
    protected $roomModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $data['rooms'] = $this->roomModel->findAll();
        return view('spaces/rooms/index', $data);
    }

    public function calendar()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $data['rooms'] = $this->roomModel->findAll();
        $data['bookings'] = $this->bookingModel->getBookingsByDate($date);
        $data['date'] = $date;
        
        return view('spaces/rooms/calendar', $data);
    }

    public function details($id = null)
    {
        if ($id === null) {
            return redirect()->to('/rooms');
        }

        $data['room'] = $this->roomModel->find($id);
        if ($data['room'] === null) {
            return redirect()->to('/rooms')->with('error', 'Sala no encontrada');
        }

        // Obtener las reservas futuras para esta sala
        $data['upcoming_bookings'] = $this->bookingModel->where('room_id', $id)
                                                       ->where('start_time >', date('Y-m-d H:i:s'))
                                                       ->orderBy('start_time', 'ASC')
                                                       ->findAll();

        return view('spaces/rooms/details', $data);
    }

    public function checkAvailability()
    {
        $roomId = $this->request->getPost('room_id');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        $isAvailable = $this->bookingModel->checkAvailability($roomId, $startTime, $endTime);

        return $this->response->setJSON([
            'available' => $isAvailable
        ]);
    }

    public function updateStatus($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['disponible', 'ocupado', 'mantenimiento'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Estado no válido']);
        }

        $success = $this->roomModel->update($id, ['status' => $status]);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Estado actualizado correctamente' : 'Error al actualizar el estado'
        ]);
    }
} 