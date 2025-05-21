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

    // ADMIN METHODS
    public function adminIndex()
    {
        $data = [
            'rooms' => $this->roomModel->orderBy('name', 'ASC')->limit(10)->findAll()
        ];
        return view('admin/rooms/list', $data);
    }

    public function adminNew()
    { 
        $data = [
            'errors' => [],
            'current_room' => null
        ];
        return view('admin/rooms/form', $data);
    }

    public function adminCreate()
    {
        $session = session();
        $validation = \Config\Services::validation();

        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'capacity'    => 'permit_empty|integer|greater_than[0]',
            'floor'       => 'permit_empty|max_length[20]',
            'price_hour'  => 'permit_empty|decimal',
            'is_active'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            // Pass validation errors and input back to the form view
            $data = [
                'errors' => $validation->getErrors(),
                'current_room' => null
            ];
            // Conservar datos de input anterior
            session()->setFlashdata('old_input', $this->request->getPost());
            return view('admin/rooms/form', $data);
        }

        $imageFile = $this->request->getFile('image');
        $imageName = 'default_room.png'; // Default image

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Generate a new secure name
            $imageName = $imageFile->getRandomName();
            // Move the file to the designated directory
            try {
                $imageFile->move(ROOTPATH . 'public/uploads/rooms', $imageName);
            } catch (\Exception $e) {
                $session->setFlashdata('error', 'Error uploading image: ' . $e->getMessage());
                return redirect()->to('admin/rooms/new');
            }
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'capacity'    => $this->request->getPost('capacity'),
            'floor'       => $this->request->getPost('floor'),
            'equipment'   => $this->request->getPost('equipment'),
            'price_hour'  => $this->request->getPost('price_hour'),
            'is_active'   => $this->request->getPost('is_active'),
            'image'       => $imageName,
        ];

        if ($this->roomModel->save($data)) {
            $session->setFlashdata('success', 'Sala creada exitosamente.');
            return redirect()->to('admin/rooms');
        } else {
            $session->setFlashdata('error', 'Error al crear la sala. Por favor, inténtelo de nuevo.');
            $data = [
                'errors' => $this->roomModel->errors(),
                'current_room' => null
            ];
            session()->setFlashdata('old_input', $this->request->getPost());
            return view('admin/rooms/form', $data);
        }
    }

    public function adminEdit($id = null)
    {
        $session = session();
        $room = $this->roomModel->find($id);

        if (!$room) {
            $session->setFlashdata('error', 'Sala no encontrada.');
            return redirect()->to('admin/rooms');
        }

        $data = [
            'current_room' => $room,
            'errors' => session()->getFlashdata('errors')
        ];

        // Si hay datos de input anterior, usarlos
        $oldInput = session()->getFlashdata('old_input');
        if ($oldInput) {
            $data['old_input'] = $oldInput;
        }

        return view('admin/rooms/form', $data);
    }

    public function adminUpdate($id = null)
    {
        $session = session();
        $room = $this->roomModel->find($id);

        if (!$room) {
            $session->setFlashdata('error', 'Sala no encontrada para actualizar.');
            return redirect()->to('admin/rooms');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'capacity'    => 'permit_empty|integer|greater_than[0]',
            'floor'       => 'permit_empty|max_length[20]',
            'price_hour'  => 'permit_empty|decimal',
            'is_active'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            $session->setFlashdata('error', 'Por favor, corrija los errores del formulario.');
            // Almacenar errores y datos de entrada para la vista
            session()->setFlashdata('errors', $validation->getErrors());
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->to('admin/rooms/edit/' . $id);
        }

        $imageFile = $this->request->getFile('image');
        $imageName = $room['image']; // Keep old image by default

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // A new image has been uploaded
            $newImageName = $imageFile->getRandomName();
            try {
                $imageFile->move(ROOTPATH . 'public/uploads/rooms', $newImageName);
                // If move is successful, delete the old image if it's not the default
                if ($imageName && $imageName !== 'default_room.png' && file_exists(ROOTPATH . 'public/uploads/rooms/' . $imageName)) {
                    unlink(ROOTPATH . 'public/uploads/rooms/' . $imageName);
                }
                $imageName = $newImageName; // Update to new image name
            } catch (\Exception $e) {
                $session->setFlashdata('error', 'Error al subir la nueva imagen: ' . $e->getMessage());
                return redirect()->to('admin/rooms/edit/' . $id);
            }
        }

        $dataToUpdate = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'capacity'    => $this->request->getPost('capacity'),
            'floor'       => $this->request->getPost('floor'),
            'equipment'   => $this->request->getPost('equipment'),
            'price_hour'  => $this->request->getPost('price_hour'),
            'is_active'   => $this->request->getPost('is_active'),
            'image'       => $imageName,
        ];

        if ($this->roomModel->update($id, $dataToUpdate)) {
            $session->setFlashdata('success', 'Sala actualizada exitosamente.');
            return redirect()->to('admin/rooms');
        } else {
            $session->setFlashdata('error', 'Error al actualizar la sala. Por favor, inténtelo de nuevo.');
            session()->setFlashdata('errors', $this->roomModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->to('admin/rooms/edit/' . $id);
        }
    }

    public function adminDelete($id = null)
    {
        $session = session();
        $room = $this->roomModel->find($id);

        if (!$room) {
            $session->setFlashdata('error', 'Sala no encontrada para eliminar.');
            return redirect()->to('admin/rooms');
        }

        // Delete the associated image file if it's not the default one
        $imageName = $room['image'];
        if ($imageName && $imageName !== 'default_room.png') {
            $imagePath = ROOTPATH . 'public/uploads/rooms/' . $imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->roomModel->delete($id)) {
            $session->setFlashdata('success', 'Sala eliminada exitosamente.');
        } else {
            $session->setFlashdata('error', 'Error al eliminar la sala. Por favor, inténtelo de nuevo.');
        }
        return redirect()->to('admin/rooms');
    }
} 