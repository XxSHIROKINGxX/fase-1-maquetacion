<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('CatalogController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Enrutamiento explícito; POST protegido contra CSRF.
$routes->get('/', 'CatalogController::index');
$routes->get('productos', 'CatalogController::index');
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login', ['filter'=>'csrf']);
$routes->get('registro', 'AuthController::registerForm');
$routes->post('registro', 'AuthController::register', ['filter'=>'csrf']);
$routes->post('logout', 'AuthController::logout', ['filter'=>'csrf']);
$routes->get('perfil', 'OrderController::profile');
$routes->get('checkout/(:num)', 'OrderController::checkout/$1');
$routes->post('pedidos', 'OrderController::create', ['filter'=>'csrf']);
$routes->get('pedidos/(:num)', 'OrderController::show/$1');
$routes->get('pedidos/(:num)/comprobante', 'OrderController::receipt/$1');
$routes->post('pedidos/(:num)/comprobante', 'OrderController::uploadReceipt/$1', ['filter'=>'csrf']);

// Las acciones del administrador verifican además su rol dentro de cada controlador.
$routes->get('admin', 'AdminController::index');
$routes->get('admin/productos', 'CatalogController::admin');
$routes->post('admin/productos', 'CatalogController::store', ['filter'=>'csrf']);
$routes->get('admin/productos/(:num)/editar', 'CatalogController::edit/$1');
$routes->post('admin/productos/(:num)', 'CatalogController::update/$1', ['filter'=>'csrf']);
$routes->post('admin/productos/(:num)/eliminar', 'CatalogController::remove/$1', ['filter'=>'csrf']);
$routes->get('admin/pedidos', 'AdminController::orders');
$routes->get('admin/pedidos/(:num)', 'AdminController::order/$1');
$routes->post('admin/pedidos/(:num)/estado', 'AdminController::setStatus/$1', ['filter'=>'csrf']);
$routes->post('admin/pedidos/(:num)/pago', 'AdminController::setPayment/$1', ['filter'=>'csrf']);
$routes->get('admin/usuarios', 'AdminController::users');
$routes->post('admin/usuarios/(:num)/rol', 'AdminController::setRole/$1', ['filter'=>'csrf']);
