<?php
require_once __DIR__ . '/../config/local.php';
require_once __DIR__ . '/../config/conexion.php';

require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';
function random_dni(): string
{
    return bin2hex(random_bytes(8 / 2));
}

$conexion = conectar();

$repo_clientes = new ClienteRepositorio($conexion);
$repo_prestamos = new PrestamoRepositorio($conexion, $repo_clientes);

$cliente = new Cliente(
    "maria",
    "chucena",
    random_dni(),
    "yo@ja",
    "mi calle",
    "1324"
);

$repo_clientes->crear($cliente);

$prestamo = new Prestamo(
    $cliente,
    75000,
    48,
    5
);

$repo_prestamos->crear($prestamo);
