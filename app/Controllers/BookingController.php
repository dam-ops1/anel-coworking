<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\RoomModel;
use App\Models\ResourceModel;
use CodeIgniter\RESTful\ResourceController;

class BookingController extends ResourceController
{
    protected $bookingModel;
    protected $roomModel;
    protected $resourceModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->resourceModel = new ResourceModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Debe iniciar sesión para ver sus reservas');
        }

        $data['bookings'] = $this->bookingModel->getUserBookings($userId);
        return view('spaces/bookings/list', $data);
    }

    public function create()
    {
        $data['rooms'] = $this->roomModel->findAll();
        $data['resources'] = $this->resourceModel->getAvailableResources();
        return view('spaces/bookings/create', $data);
    }

    public function store()
    {
        $rules = [
            'room_id' => 'required|integer',
            'start_time' => 'required|valid_date',
            'end_time' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookingData = [
            'user_id' => session()->get('user_id'), // Asumiendo que tienes un sistema de autenticación
            'room_id' => $this->request->getPost('room_id'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'status' => 'pendiente'
        ];

        // Verificar disponibilidad
        if (!$this->bookingModel->checkAvailability($bookingData['room_id'], $bookingData['start_time'], $bookingData['end_time'])) {
            return redirect()->back()->withInput()->with('error', 'La sala no está disponible en el horario seleccionado');
        }

        // Procesar recursos adicionales
        $resources = [];
        $requestedResources = $this->request->getPost('resources');
        if ($requestedResources) {
            foreach ($requestedResources as $resourceId => $quantity) {
                if ($quantity > 0) {
                    $resources[] = [
                        'id' => $resourceId,
                        'quantity' => $quantity
                    ];
                }
            }
        }

        // Crear la reserva con los recursos
        if ($this->bookingModel->createBookingWithResources($bookingData, $resources)) {
            return redirect()->to('/bookings')->with('message', 'Reserva creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la reserva');
    }

    public function manage($id = null)
    {
        if ($id === null) {
            return redirect()->to('/bookings');
        }

        $data['booking'] = $this->bookingModel->find($id);
        if ($data['booking'] === null) {
            return redirect()->to('/bookings')->with('error', 'Reserva no encontrada');
        }

        $data['room'] = $this->roomModel->find($data['booking']['room_id']);
        $data['resources'] = $this->resourceModel->getBookingResources($id);

        return view('spaces/bookings/manage', $data);
    }

    public function cancel($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID de reserva no proporcionado']);
        }

        $booking = $this->bookingModel->find($id);
        if ($booking === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Reserva no encontrada']);
        }

        // Verificar que el usuario actual sea el dueño de la reserva
        if ($booking['user_id'] != session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tiene permiso para cancelar esta reserva']);
        }

        // Liberar los recursos asociados
        $bookingResourceModel = new \App\Models\BookingResourceModel();
        $bookingResourceModel->returnResources($id);

        // Actualizar estado de la reserva
        $success = $this->bookingModel->update($id, ['status' => 'cancelada']);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Reserva cancelada correctamente' : 'Error al cancelar la reserva'
        ]);
    }
} 