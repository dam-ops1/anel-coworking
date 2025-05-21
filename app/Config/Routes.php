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
$routes->get('auth/reset-password/(:any)', 'AuthController::resetPassword/$1');

$routes->post('auth/reset-password', 'AuthController::resetPasswordPost');

// profile
$routes->get('profile', 'ProfileController::index');
$routes->get('profile/edit', 'ProfileController::edit');
$routes->post('profile/update', 'ProfileController::update');

// dashboard
$routes->get('dashboard', 'DashboardController::index');
// redireccionar dashboard/index a dashboard
$routes->addRedirect('dashboard/index', 'dashboard');

//ruta para agregar una imagen 
$routes->post('profile/upload-image', 'ProfileController::uploadImage');





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
$routes->get('rooms/available', 'RoomController::showAvailableRooms');
$routes->get('rooms/details/(:num)', 'RoomController::details/$1');
$routes->post('bookings/create', 'BookingController::create');
$routes->get('bookings/confirmation/(:num)', 'BookingController::confirmation/$1');
$routes->get('bookings/cancel/(:num)', 'BookingController::cancel/$1');
$routes->get('bookings/list', 'BookingController::index');
$routes->get('my-bookings', 'BookingController::index', ['filter' => 'auth']);

// Admin Routes for Room Management
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    // Route for listing rooms and showing the create/edit form
    $routes->get('rooms', 'RoomController::adminIndex'); 
    // Route to show the creation form explicitly (triggered by "Crear Nueva" button)
    $routes->get('rooms/new', 'RoomController::adminNew'); 
    // Route to handle the submission of the new room form
    $routes->post('rooms/create', 'RoomController::adminCreate'); 
    // Routes for editing and updating a room
    $routes->get('rooms/edit/(:num)', 'RoomController::adminEdit/$1');
    $routes->post('rooms/update/(:num)', 'RoomController::adminUpdate/$1'); // Handles PUT request via _method
    // Route for deleting a room
    $routes->delete('rooms/delete/(:num)', 'RoomController::adminDelete/$1');
});

// Admin Routes for User Management
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('users', 'UserController::adminIndex');
    $routes->get('users/new', 'UserController::adminNew');
    $routes->post('users/create', 'UserController::adminCreate');
    $routes->get('users/edit/(:num)', 'UserController::adminEdit/$1');
    $routes->post('users/update/(:num)', 'UserController::adminUpdate/$1'); // Handles PUT via _method
    $routes->delete('users/delete/(:num)', 'UserController::adminDelete/$1'); // Handles DELETE via _method
});