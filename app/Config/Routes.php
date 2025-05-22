<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 

// Rutas de autenticación
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

// static page
$routes->get('en-construccion', 'PagesConstructionController::construction');

//booking routes
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

//admin routes 


$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    
    $routes->get('rooms', 'RoomController::adminIndex'); 
    
    $routes->get('rooms/new', 'RoomController::adminNew'); 
    
    $routes->post('rooms/create', 'RoomController::adminCreate'); 
    
    $routes->get('rooms/edit/(:num)', 'RoomController::adminEdit/$1');
    $routes->put('rooms/update/(:num)', 'RoomController::adminUpdate/$1');
    
    $routes->delete('rooms/delete/(:num)', 'RoomController::adminDelete/$1');
});


$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('users', 'UserController::adminIndex');
    $routes->get('users/new', 'UserController::adminNew');
    $routes->post('users/create', 'UserController::adminCreate');
    $routes->get('users/edit/(:num)', 'UserController::adminEdit/$1');
    $routes->put('users/update/(:num)', 'UserController::adminUpdate/$1');
    $routes->delete('users/delete/(:num)', 'UserController::adminDelete/$1');
});