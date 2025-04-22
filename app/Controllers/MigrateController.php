<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class MigrateController extends Controller
{
    public function index()
    {
        // Cargar la clase Migration
        $migrate = Services::migrations();

        try {
            // Ejecutar todas las migraciones pendientes
            $migrate->latest();
            return 'Migraciones ejecutadas correctamente.';
        } catch (\Exception $e) {
            return 'Error al ejecutar las migraciones: ' . $e->getMessage();
        }
    }

    public function rollback($batch = 0)
    {
        $migrate = Services::migrations();

        try {
            $migrate->regress($batch);
            return 'Rollback ejecutado correctamente.';
        } catch (\Exception $e) {
            return 'Error al ejecutar el rollback: ' . $e->getMessage();
        }
    }
} 