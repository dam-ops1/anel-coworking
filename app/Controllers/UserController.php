<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        // $userModel = new UserModel();
        // $user = $userModel->find($userId);

        // if (!$user) {
        //     return redirect()->to('/login')->with('error', 'Usuario no encontrado');
        // }

        $userModel = new UserModel();
        $users = $userModel->findAll();

        return view('users/users', ['users' => $users]);
    }

}