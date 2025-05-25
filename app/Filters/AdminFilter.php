<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'Por favor inicie sesión para continuar');
        }
        
        
        $userRole = session()->get('role');
        if ($userRole != 1) {
            return redirect()->to('dashboard')->with('error', 'No tienes permisos para acceder a esta sección. Solo los administradores pueden acceder.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
} 