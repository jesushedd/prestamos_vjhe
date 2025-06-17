<?php
require_once __DIR__ . '/../config/local.php';
require_once __DIR__ . '/../config/conexion.php';

require_once ROOT_DIR . 'modelo/ClienteModelo.php';


function random_dni(): string
{
    return bin2hex(random_bytes(8 / 2));
}



$repo_clientes = new ClienteRepositorio(conectar());

//crear cliente
$fecha_cr = '10-09-2022';
$dni = random_dni();
$nuevo_cliente = new Cliente(
    "yo",
    'no',
    $dni,
    "yo@no",
    'mi casa', 
    '55555',
    '2022-09-10'
);

$id = $repo_clientes->crear($nuevo_cliente);

$cliente_guardao = $repo_clientes->obtenerPorId($id);

assert($fecha_cr === $cliente_guardao->str_fecha());
assert($cliente_guardao !== null);
assert($cliente_guardao->id === $id);
assert($cliente_guardao->nombre === "yo");
assert($cliente_guardao->apellido === "no");
assert($cliente_guardao->dni === $dni);
assert($cliente_guardao->email === "yo@no");

// Crear cliente inicial con ese DNI
$dni = random_dni();
$cliente = new Cliente(
    "Ana",
    "Gomez",
    $dni,
    "ana@example.com",
    'otra casa',
    '66666',
    "2023-01-01"
);
$id = $repo_clientes->crear($cliente);
assert($id > 0);

// Obtener cliente y verificar datos iniciales
$cliente_bd = $repo_clientes->obtenerPorId($id);
assert($cliente_bd !== null);
assert($cliente_bd->nombre === "Ana");
assert($cliente_bd->apellido === "Gomez");
assert($cliente_bd->dni === $dni);
assert($cliente_bd->email === "ana@example.com");
assert($cliente_bd->str_fecha() === "01-01-2023");

// Modificar campos
$cliente_bd->nombre = "Ana María";
$cliente_bd->apellido = "López";
$cliente_bd->email = "anita@correo.com";
$cliente_bd->fecha_creacion = DateTime::createFromFormat('Y-m-d', '2024-06-15');

// Actualizar en base de datos
$ok = $repo_clientes->actualizar($cliente_bd);
assert($ok === true);

// Verificar que los datos fueron actualizados correctamente
$cliente_actualizado = $repo_clientes->obtenerPorId($id);
assert($cliente_actualizado !== null);
assert($cliente_actualizado->nombre === "Ana María");
assert($cliente_actualizado->apellido === "López");
assert($cliente_actualizado->dni === $dni); // no debe cambiar
assert($cliente_actualizado->email === "anita@correo.com");
assert($cliente_actualizado->str_fecha() === "15-06-2024");

//Verificar q el cliente id 3 existe
$cliente_3 = $repo_clientes ->obtenerPorId(3);
assert($cliente_3 !== null);