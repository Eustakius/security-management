<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::loginView');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::registerView');
$routes->post('/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// API Routes
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('users/search', 'UserController::search');
    $routes->get('messages', 'MessageController::getInbox');
    $routes->get('messages/sent', 'MessageController::getSent');
    $routes->post('messages/send', 'MessageController::send');
});
