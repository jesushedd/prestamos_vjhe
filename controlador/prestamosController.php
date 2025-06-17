<?php

$CONFIG = __DIR__. '/config/';

require_once $CONFIG . 'init.php';

require_once ROOT_DIR . 'config/conexion.php';
require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';

if (isset($_POST['id'])) {
    $conexion = conectar();
    $repo_clientes = new ClienteRepositorio($conexion);
    $repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);
    
    $el_cliente = $repo_clientes->obtenerPorId((int) $_POST['id']);
    $los_prestamos = $repo_prestamos->obtener_por_cliente($el_cliente);

    //echo $_POST['id'];
    echo json_encode($los_prestamos);

}