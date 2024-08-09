<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'Home::index');
$routes->GET('/', 'Home::detail-informasi');
$routes->get('detail-informasi/(:segment)', 'Home::blog/$1');


//TOKEN CSRF
$routes->get('get-new-csrf-token', 'SecurityController::getNewCSRFToken');

//AUTHENTICATION
$routes->GROUP('authentication', function ($routes) { //catatan : pastikan POST / GET
    $routes->GET('registrasi', 'Authentication::registrasi/$1');
    $routes->POST('cekRegistrasi', 'Authentication::cekRegistrasi/');
    $routes->post('updateStatus', 'Authentication::updateStatus');
    $routes->GET('login', 'Authentication::login/');
    $routes->POST('cekLogin', 'Authentication::cekLogin/$1');
    $routes->GET('logout', 'Authentication::logout/$1');
    $routes->GET('lupaPassword', 'Authentication::lupaPassword/$1');
    $routes->POST('lupaPassword', 'Authentication::lupaPassword/$1');
    $routes->GET('resetPassword', 'Authentication::resetPassword/$1');
    $routes->POST('resetPassword', 'Authentication::resetPassword/$1');
});

//ROLE
$routes->GET('dashboard', 'RoleController::index');

//ROLE ADMIN
$routes->GROUP('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->GET('dashboard', 'Dashboard::index', ['namespace' => 'App\Controllers\Admin']);
});

//ROLE USER
$routes->GROUP('user', ['namespace' => 'App\Controllers\User'], function ($routes) {
    /*=================================== DASHBOARD ====================================*/
    $routes->GET('dashboard', 'Dashboard::index', ['namespace' => 'App\Controllers\User']);

    /*=================================== PROFILE ====================================*/
    $routes->GET('profile', 'ProfileController::index', ['namespace' => 'App\Controllers\User']);
    $routes->GROUP('profile', static function ($routes) {
        $routes->POST('update/(:num)', 'ProfileController::update/$1', ['namespace' => 'App\Controllers\User']);
        $routes->GET('resetpassword', 'ProfileController::resetPassword', ['namespace' => 'App\Controllers\User']);
        $routes->POST('updateSandi/(:num)', 'ProfileController::updateSandi/$1', ['namespace' => 'App\Controllers\User']);
    });

    /*=================================== KATEGORI BUKU ====================================*/
    $routes->GET('kategori', 'KategoriBukuController::index', ['namespace' => 'App\Controllers\User']);
    $routes->GROUP('kategori', static function ($routes) {
        $routes->POST('save', 'KategoriBukuController::save', ['namespace' => 'App\Controllers\User']);
        $routes->PUT('simpan_perubahan', 'KategoriBukuController::simpan_perubahan', ['namespace' => 'App\Controllers\User']);
        $routes->DELETE('delete', 'KategoriBukuController::delete/$1', ['namespace' => 'App\Controllers\User']);
    });

    /*=================================== BUKU ====================================*/
    $routes->GET('buku', 'BukuController::index', ['namespace' => 'App\Controllers\User']);
    $routes->GROUP('buku', static function ($routes) {
        $routes->GET('totalData/(:num)', 'BukuController::totalData/$1', ['namespace' => 'App\Controllers\User']);
        $routes->GET('tambah', 'BukuController::tambah', ['namespace' => 'App\Controllers\User']);
        $routes->POST('save', 'BukuController::save', ['namespace' => 'App\Controllers\User']);
        $routes->GET('cek_data/(:segment)', 'BukuController::cek_data/$1', ['namespace' => 'App\Controllers\User']);
        $routes->GET('edit/(:segment)', 'BukuController::edit/$1', ['namespace' => 'App\Controllers\User']);
        $routes->PUT('update/(:num)', 'BukuController::update/$1', ['namespace' => 'App\Controllers\User']);
        $routes->DELETE('delete2', 'BukuController::delete2', ['namespace' => 'App\Controllers\User']);
        $routes->DELETE('delete', 'BukuController::delete', ['namespace' => 'App\Controllers\User']);
    });
});
