<?php

$CONFIG = __DIR__ . '/config/';

require_once $CONFIG . 'init.php';

require_once ROOT_DIR . 'config/conexion.php';
require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';
require_once ROOT_DIR . 'modelo/PagoModelo.php';
require_once ROOT_DIR . 'modelo/PlazoModelo.php';

$conexion = conectar();

if (isset($_POST['id_cliente']) && isset($_POST['accion']) && $_POST['accion'] === 'listar') {

    $repo_clientes = new ClienteRepositorio($conexion);
    $repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);

    $el_cliente = $repo_clientes->obtenerPorId((int) $_POST['id_cliente']);
    $los_prestamos = $repo_prestamos->obtener_por_cliente($el_cliente);

    //echo $_POST['id'];
    echo json_encode($los_prestamos);
    return;
}

if (isset($_POST['id_cliente']) && isset($_POST['accion']) && $_POST['accion'] === 'cronograma') {

    $repo_clientes = new ClienteRepositorio($conexion);
    $repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);
    $repo_pagos = new PlazoRepositorio($conexion);

    $el_tipo_plazo = $repo_pagos->obtener_por_numero((int) $_POST['plazos']);
    $el_cliente = $repo_clientes->obtenerPorId((int) $_POST['id_cliente']);
    $el_presatamo = new Prestamo(
        $el_cliente,
        floatval($_POST['monto']),
        $el_tipo_plazo->numero_plazos,
        $el_tipo_plazo->tasa
    );

    $el_cronograma_pagos = $el_presatamo->obtener_cronograma();
    echo json_encode($el_cronograma_pagos);
    return;
}
//Vista Nuevo Prestamo
if (isset($_GET['id_cliente'])) {
    $repo_clientes = new ClienteRepositorio($conexion);
    $repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);
    $repo_pagos = new PagoRepositorio($conexion);
    $repo_plazos = new PlazoRepositorio($conexion);

    $id_cliente = (int) $_GET['id_cliente'];
    
    $EL_CLIENTE = $repo_clientes->obtenerPorId($id_cliente);
    $LOS_PLAZOS = $repo_plazos->obtener_todos();

    require $VISTAS . 'nuevo_prestamo.php';
    return;
}

