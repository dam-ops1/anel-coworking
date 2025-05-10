<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class MessageController extends Controller{

    public function showMessage($title, $message, $view, $text)
    {

        session()->setFlashdata([
            'title' => $title,
            'message' => $message,
            'view' => $view,
            'text' => $text
        ]);

        return redirect()->to('/message');
    }

    public function index()
    {
        $data = [
            'title'     => session()->getFlashdata('title'),
            'message'   => session()->getFlashdata('message'),
            'view'      => session()->getFlashdata('view'),
            'text'      => session()->getFlashdata('text'),
        ];

        if (empty($data['title'])) {
            return redirect()->to('/');
        }

        return view('message', $data);
    }

}