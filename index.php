<?php
require_once __DIR__ . '/config/local.php';

$request = $_SERVER['REQUEST_URI'];

$VISTAS = ROOT_DIR . 'vista/';

$CONTROLADOR_DIR = ROOT_DIR . 'controlador/';



switch ($request) {
    case ROOT_ROUTE:
        echo "Hola munda";
        break;
    case ROOT_ROUTE . 'login':
        require $CONTROLADOR_DIR . 'loginController.php';
        break;
    case ROOT_ROUTE . 'home':
        require $CONTROLADOR_DIR . 'homeController.php';
        break;
    case ROOT_ROUTE . 'logout':
        require $CONTROLADOR_DIR . 'logout.php';
        break;
    case ROOT_ROUTE . 'clientes':
        require $CONTROLADOR_DIR . 'clientesController.php';
        break;
    case ROOT_ROUTE . 'prestamos':
        require $CONTROLADOR_DIR . 'prestamosController.php';
        break;

    default:
        echo $request;
}






?>