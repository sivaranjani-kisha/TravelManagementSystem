<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/', 'Auth::login');

$routes->get('register', 'Auth::register');
$routes->post('register/save', 'Auth::saveRegister');

$routes->get('login', 'Auth::login');
$routes->post('login/check', 'Auth::checkLogin');

$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index');

$routes->get('routes', 'RouteController::index');
$routes->get('routes/list', 'RouteController::list');
$routes->post('routes/save', 'RouteController::save');
$routes->get('routes/edit/(:num)', 'RouteController::edit/$1');
$routes->get('routes/delete/(:num)', 'RouteController::delete/$1');


$routes->get('pickups', 'PickupController::index');
$routes->get('pickups/list', 'PickupController::list');
$routes->post('pickups/save', 'PickupController::save');
$routes->get('pickups/edit/(:num)', 'PickupController::edit/$1');
$routes->get('pickups/delete/(:num)', 'PickupController::delete/$1');


$routes->get('route-pickups', 'RoutePickupController::index');
$routes->get('route-pickups/list', 'RoutePickupController::list');
$routes->post('route-pickups/save', 'RoutePickupController::save');
$routes->get('route-pickups/delete/(:num)', 'RoutePickupController::delete/$1');

$routes->get('vehicles', 'VehicleController::index');
$routes->get('vehicles/list', 'VehicleController::list');
$routes->post('vehicles/save', 'VehicleController::save');
$routes->get('vehicles/delete/(:num)', 'VehicleController::delete/$1');

$routes->get('reports', 'ReportController::index');
$routes->post('reports/fetch', 'ReportController::fetch');


$routes->get('payment', 'Payment::index');
$routes->post('payment/createOrder', 'Payment::createOrder');
$routes->post('payment/verifyPayment', 'Payment::verifyPayment');
