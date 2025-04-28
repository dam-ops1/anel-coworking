<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class MessageController extends Controller{

    public function showMessage($title, $message, $view, $text)
    {
        $data = [
            'title' => $title,
            'message' => $message,
            'view' => $view,
            'text' => $text
        ];
        
        return view('message', $data);
    }

}