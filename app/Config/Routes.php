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

// perfil ruta 
$routes->get('profile', 'ProfileController::index');


// Message route
$routes->get('message', 'MessageController::index');