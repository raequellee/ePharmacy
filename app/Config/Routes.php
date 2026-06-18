<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('login',          'AuthController::index');
$routes->post('login',         'AuthController::authenticate');
$routes->get('logout',         'AuthController::logout');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                  'GudangController::index');
    $routes->get('obat',               'GudangController::index');
    $routes->post('obat/pesan',        'GudangController::pesan');

    $routes->get('pesanan/konfirmasi',  'KasirController::index');
    $routes->post('pesanan/cek-ongkir', 'KasirController::cekOngkir');
    $routes->post('pesanan/bayar',      'KasirController::bayar');
    $routes->get('pesanan/sukses', 'KasirController::sukses');

    $routes->get('pengiriman',                'PengirimanController::index');
    $routes->get('pengiriman/(:alphanum)',     'PengirimanController::lacak/$1');
    $routes->get('pesanan/search-kota', 'KasirController::searchKota');
});