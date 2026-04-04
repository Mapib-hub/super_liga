<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('auth/check', 'AuthController::redirectByRole', ['filter' => 'session']);
// Rutas de Shield (Login, Registro, etc.)
service('auth')->routes($routes);




// Grupo Admin: Solo usuarios logueados pueden entrar aquí
$routes->group('admin', ['filter' => 'session'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('instituciones', 'Admin\InstitucionController::index');
    $routes->get('instituciones/crear', 'Admin\InstitucionController::crear');
    $routes->post('instituciones/guardar', 'Admin\InstitucionController::guardar');
    $routes->get('instituciones/editar/(:num)', 'Admin\InstitucionController::editar/$1');
    $routes->post('instituciones/actualizar/(:num)', 'Admin\InstitucionController::actualizar/$1');
    $routes->get('instituciones/eliminar/(:num)', 'Admin\InstitucionController::eliminar/$1');


    // Aquí iremos agregando Instituciones, Jugadores, etc.
});

// Ruta por defecto: si el usuario entra a la raíz y está logueado, mándalo al dashboard
$routes->get('/', function () {
    if (auth()->loggedIn()) {
        return redirect()->to('/admin/dashboard');
    }
    return redirect()->to('/login');
});