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
$routes->get('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/activate/(:any)', 'AuthController::activateUser/$1');
$routes->get('auth/forgot-password', 'AuthController::forgotPasswordView');


$routes->post('auth/password-email', 'AuthController::sendResetPasswordEmail');

$routes->get('auth/forgot-password/(:any)', 'AuthController::resetPassword/$1');

$routes->post('auth/reset-password', 'AuthController::resetPasswordPost');

// profile
$routes->get('profile', 'ProfileController::index');

// dashboard
$routes->get('dashboard', 'DashboardController::index');
// redireccionar dashboard/index a dashboard
$routes->addRedirect('dashboard/index', 'dashboard');





// Message route
$routes->get('message', 'MessageController::index');

// Usuarios ruta
$routes->get('users', 'UserController::index');

// Páginas estáticas
$routes->get('en-construccion', 'PagesConstructionController::construction');

// Booking system routes
$routes->get('bookings', 'RoomController::index');
$routes->post('rooms/check-availability', 'RoomController::checkAvailability');
$routes->get('rooms/list', 'RoomController::listRooms');
$routes->get('rooms/available', 'RoomController::checkAvailability');
$routes->get('rooms/details/(:num)', 'RoomController::details/$1');
$routes->post('bookings/create', 'BookingController::create');
$routes->get('bookings/confirmation/(:num)', 'BookingController::confirmation/$1');
$routes->get('bookings/cancel/(:num)', 'BookingController::cancel/$1');
$routes->get('bookings/list', 'BookingController::index');