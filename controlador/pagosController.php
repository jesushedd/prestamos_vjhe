<?php

$CONFIG = __DIR__ . '/config/';

require_once $CONFIG . 'init.php';

require_once ROOT_DIR . 'config/conexion.php';
require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';
require_once ROOT_DIR . 'modelo/PagoModelo.php';


if (isset($_GET['id_cliente'])) {
    $repo_clientes = new ClienteRepositorio($conexion);
    $repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);
    $repo_pagos = new PagoRepositorio($conexion);
    
    $el_presatamo = $repo_prestamos->obtener_por_id((int) $_GET['id']);
    $LOS_PAGOS = $repo_pagos->obtener_por_prestamo($el_presatamo);
    require $VISTAS . 'detalle_prestamo.php';
    return;    
}
