<?php

session_start();

require_once './src/config/database.php';

require_once './src/controllers/CuentasController.php';
require_once './src/controllers/PolizasController.php';
require_once './src/controllers/DetallePolizaController.php';

$cuentasController = new CuentasController($conn);
$polizasController = new PolizasController($conn);
$detallePolizaController = new DetallePolizaController($conn);


// Map de Rutas a controladores
$routes = [
    '/' => './src/views/layout.php',
    '/cuentas' => [$cuentasController, 'listCuentas'],
    '/cuentas/add' => [$cuentasController, 'addCuenta'],
    '/cuentas/edit' => [$cuentasController, 'editCuenta'],
    '/cuentas/delete' => [$cuentasController, 'deleteCuenta'],
    '/polizas' => [$polizasController, 'listPolizas'],
    '/polizas/add' => [$polizasController, 'addPoliza'],
    '/polizas/edit' => [$polizasController, 'editPoliza'],
    '/polizas/delete' => [$polizasController, 'deletePoliza'],
    '/detallePoliza' => [$detallePolizaController, 'list'],
    '/detallePoliza/add' => [$detallePolizaController, 'add'],
    '/detallePoliza/edit' => [$detallePolizaController, 'edit'],
    '/detallePoliza/delete' => [$detallePolizaController, 'delete'],
    '/reportes' => './src/views/reportes/' . basename($_SERVER['REQUEST_URI']) . '.php',
];

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (array_key_exists($requestUri, $routes)) {
    $handler = $routes[$requestUri];
    if (is_callable($handler)) {
        call_user_func($handler);
    } elseif (is_string($handler)) {
        include $handler;
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}