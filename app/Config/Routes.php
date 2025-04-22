<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rutas de migración (solo para desarrollo)
$routes->get('migrate', 'MigrateController::index');
$routes->get('migrate/rollback/(:num)', 'MigrateController::rollback/$1');

// Rutas de autenticación
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('create-test-user', 'AuthController::createTestUser'); // Ruta temporal

// Redirigir la página principal al calendario de reservas
$routes->get('/', 'RoomController::calendar');

// Rutas para salas
$routes->group('rooms', function($routes) {
    $routes->get('/', 'RoomController::index');
    $routes->get('calendar', 'RoomController::calendar');
    $routes->get('details/(:num)', 'RoomController::details/$1');
    $routes->post('check-availability', 'RoomController::checkAvailability');
    $routes->post('update-status/(:num)', 'RoomController::updateStatus/$1');
});

// Rutas para reservas
$routes->group('bookings', function($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('create', 'BookingController::create');
    $routes->post('store', 'BookingController::store');
    $routes->get('manage/(:num)', 'BookingController::manage/$1');
    $routes->post('cancel/(:num)', 'BookingController::cancel/$1');
});
