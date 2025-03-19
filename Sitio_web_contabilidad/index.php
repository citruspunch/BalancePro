<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once './src/config/database.php';
require_once './src/controllers/CuentasController.php';
require_once './src/controllers/PolizasController.php';
require_once './src/controllers/ReportesController.php';


$cuentasController = new CuentasController($conn);
$polizasController = new PolizasController($conn);
$reportesController = new ReportesController($conn);

// Map de Rutas a controladores
$routes = [
    '/' => './src/views/home.php',
    '/Sitio_web_contabilidad' => './src/views/home.php',
    '/Sitio_web_contabilidad/cuentas' => [$cuentasController, 'handleRequest'],
    '/Sitio_web_contabilidad/cuentas/add' => [$cuentasController, 'addCuenta'],
    '/Sitio_web_contabilidad/cuentas/edit' => [$cuentasController, 'editCuenta'],
    '/Sitio_web_contabilidad/cuentas/delete' => [$cuentasController, 'deleteCuenta'],
    '/Sitio_web_contabilidad/polizas' => [$polizasController, 'handlePolizaRequest'],
    '/Sitio_web_contabilidad/polizas/add' => [$polizasController, 'addPoliza'],
    '/Sitio_web_contabilidad/polizas/edit' => [$polizasController, 'editPoliza'],
    '/Sitio_web_contabilidad/polizas/delete' => [$polizasController, 'deletePoliza'],
    '/Sitio_web_contabilidad/reportes/diario' => [$reportesController, 'reporteDiario'],
    '/Sitio_web_contabilidad/reportes/mayor' => [$reportesController, 'reporteMayor'],
    '/Sitio_web_contabilidad/reportes/balance' => [$reportesController, 'reporteBalance'],
];

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestUri = strtok($requestUri, '?'); 
$requestUri = rtrim($requestUri, '/'); 

if (array_key_exists($requestUri, $routes)) {
    $handler = $routes[$requestUri];
    if (is_callable($handler)) {
        call_user_func($handler);
    } elseif (is_string($handler)) {
        include $handler;
    }
} else {
    // Debug de la ruta Imprimir la ruta y el método
    echo $requestUri . ' ' . $requestMethod;
    http_response_code(404);
    echo "404 Not Founddddd";
}
?>