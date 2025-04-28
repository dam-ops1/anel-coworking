<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 

// Rutas de autenticación
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
$routes->get('/', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/activate/(:any)', 'AuthController::activateUser/$1');
$routes->get('auth/forgot-password', 'AuthController::forgotPasswordView');
$routes->post('auth/password-email', 'AuthController::sendResetPasswordEmail');

$routes->get('auth/forgot-password/(:any)', 'AuthController::resetPassword/$1');

$routes->post('auth/reset-password', 'AuthController::resetPasswordPost');


// // Redirigir la página principal al calendario de reservas
// $routes->get('/', 'RoomController::calendar');

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
