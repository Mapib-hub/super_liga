<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('auth/check', 'AuthController::redirectByRole', ['filter' => 'session']);
// Rutas de Shield (Login, Registro, etc.)
service('auth')->routes($routes);


$routes->post('test-toast', 'Admin\TestController::testToast');

// Grupo Admin: Solo usuarios logueados pueden entrar aquí
$routes->group('admin', ['filter' => 'session'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('instituciones', 'Admin\InstitucionController::index');
    $routes->get('instituciones/crear', 'Admin\InstitucionController::crear');
    $routes->post('instituciones/guardar', 'Admin\InstitucionController::guardar');
    $routes->get('instituciones/editar/(:num)', 'Admin\InstitucionController::editar/$1');
    $routes->post('instituciones/actualizar/(:num)', 'Admin\InstitucionController::actualizar/$1');
    $routes->get('instituciones/eliminar/(:num)', 'Admin\InstitucionController::eliminar/$1');

    // CRUD Series
    $routes->get('series', 'Admin\SerieController::index');
    $routes->get('series/crear', 'Admin\SerieController::crear');
    $routes->post('series/guardar', 'Admin\SerieController::guardar');
    $routes->get('series/editar/(:num)', 'Admin\SerieController::editar/$1');
    $routes->post('series/actualizar/(:num)', 'Admin\SerieController::actualizar/$1');
    $routes->delete('series/eliminar/(:num)', 'Admin\SerieController::eliminar/$1');

    // CRUD Temporadas
    $routes->get('temporadas', 'Admin\TemporadasController::index');
    $routes->get('temporadas/activar/(:num)', 'Admin\TemporadasController::activar/$1');
    $routes->get('temporadas/crear', 'Admin\TemporadasController::crear');
    $routes->post('temporadas/guardar', 'Admin\TemporadasController::guardar');
    $routes->get('temporadas/editar/(:num)', 'Admin\TemporadasController::editar/$1');
    $routes->post('temporadas/actualizar/(:num)', 'Admin\TemporadasController::actualizar/$1');
    $routes->delete('temporadas/eliminar/(:num)', 'Admin\TemporadasController::eliminar/$1');
    // CRUD Fechas
    $routes->get('fechas', 'Admin\FechaController::index');
    $routes->get('fechas/crear', 'Admin\FechaController::crear');
    $routes->post('fechas/guardar', 'Admin\FechaController::guardar');
    $routes->get('fechas/editar/(:num)', 'Admin\FechaController::editar/$1');
    $routes->post('fechas/actualizar/(:num)', 'Admin\FechaController::actualizar/$1');
    $routes->delete('fechas/eliminar/(:num)', 'Admin\FechaController::eliminar/$1');

    // CRUD Fechas
    $routes->get('noticias', 'Admin\NoticiaController::index');
    $routes->get('noticias/crear', 'Admin\NoticiaController::crear');
    $routes->post('noticias/guardar', 'Admin\NoticiaController::guardar');
    $routes->get('noticias/editar/(:num)', 'Admin\NoticiaController::editar/$1');
    $routes->post('noticias/actualizar/(:num)', 'Admin\NoticiaController::actualizar/$1');
    $routes->delete('noticias/eliminar/(:num)', 'Admin\NoticiaController::eliminar/$1');
    $routes->post('noticias/subir-galeria/(:num)', 'Admin\NoticiasImagenesController::subir/$1');
    $routes->delete('noticias/eliminar-foto/(:num)/(:num)', 'Admin\NoticiasImagenesController::eliminar/$1/$2');

    // CRUD Usuarios
    $routes->get('usuarios', 'Admin\UsuarioController::index');
    $routes->get('usuarios/crear', 'Admin\UsuarioController::crear');
    $routes->post('usuarios/guardar', 'Admin\UsuarioController::guardar');
    $routes->get('usuarios/editar/(:num)', 'Admin\UsuarioController::editar/$1');
    $routes->post('usuarios/actualizar/(:num)', 'Admin\UsuarioController::actualizar/$1');
    $routes->delete('usuarios/eliminar/(:num)', 'Admin\UsuarioController::eliminar/$1');

    // CRUD Jugadores
    $routes->get('jugadores', 'Admin\JugadorController::index');
    $routes->get('jugadores/crear', 'Admin\JugadorController::crear');
    $routes->post('jugadores/guardar', 'Admin\JugadorController::guardar');
    $routes->get('jugadores/editar/(:num)', 'Admin\JugadorController::editar/$1');
    $routes->post('jugadores/actualizar/(:num)', 'Admin\JugadorController::actualizar/$1');
    $routes->delete('jugadores/eliminar/(:num)', 'Admin\JugadorController::eliminar/$1');

    // --- MÓDULO DE REGISTRO DE PARTIDOS (GOLES Y TARJETAS) ---

    // 1. Vistas de Listado
    $routes->get('goles', 'Admin\RegistroController::listadoGoles');
    $routes->get('tarjetas', 'Admin\RegistroController::listadoTarjetas');

    // 2. Acciones de Guardado (Formularios POST)
    $routes->post('registro/guardarGolesPartido', 'Admin\RegistroController::guardarGolesPartido');
    $routes->post('registro/guardarTarjetasPartido', 'Admin\RegistroController::registrarSancion');
    $routes->post('tarjetas/actualizarTarjeta', 'Admin\RegistroController::actualizarTarjeta');

    // 3. Acciones de Eliminación (HTMX o Link directo)
    // Nota: Si usas HTMX delete, la ruta debe ser delete. Si usas un link <a>, debe ser get.
    // Tu código HTML usa hx-delete, así que la dejamos como delete:
    $routes->delete('registro/borrarGol/(:num)', 'Admin\RegistroController::borrarGol/$1');

    // 4. Rutas AJAX (Para los selects dependientes del Modal)
    $routes->get('goles/getSeriesPorFecha/(:num)', 'Admin\RegistroController::getSeriesPorFecha/$1');
    $routes->get('goles/getPartidos/(:num)/(:num)', 'Admin\RegistroController::getPartidosByFechaSerie/$1/$2');
    $routes->get('fixture/get-jugadores-partido/(:num)', 'Admin\RegistroController::getJugadoresPartido/$1');
});

// Ruta por defecto: si el usuario entra a la raíz y está logueado, mándalo al dashboard
$routes->get('/', function () {
    if (auth()->loggedIn()) {
        return redirect()->to('/admin/dashboard');
    }
    return redirect()->to('/login');
});
