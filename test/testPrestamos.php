<?php
require_once __DIR__ . '/../config/local.php';
require_once __DIR__ . '/../config/conexion.php';

require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';

$conexion = conectar();

$cliente_repo = new ClienteRepositorio($conexion);
$prestamos_repo = new PrestamoRepositorio($conexion, $cliente_repo);
//Crear un prstamo para un cliente
$un_cliente = $cliente_repo->obtenerPorId(3);

$prestamo = new Prestamo(
    $un_cliente,
    10000,
    60,
    0.12
);

$id_prestamo = $prestamos_repo->crear($prestamo);

$prestamo_guardado = $prestamos_repo->obtener_por_id($id_prestamo);
assert($prestamo_guardado !== null);
assert($prestamo_guardado->id === $id_prestamo);
assert($prestamo_guardado->id_cliente === $un_cliente->id);
assert($prestamo_guardado->monto === 10000.0);
assert($prestamo_guardado->plazos === 60);
assert($prestamo_guardado->interes_anual === 0.12);
assert($prestamo_guardado->estado === 'vigente');

// Comprobamos que la fecha es igual (puede variar en formato, así que comparamos como string)
assert($prestamo_guardado->str_fecha_inicio() === $prestamo->str_fecha_inicio());

//;//.///
// Crear préstamo nuevo
$prestamo = new Prestamo(
    $un_cliente,
    10000,
    60,
    0.12
);

$id_prestamo = $prestamos_repo->crear($prestamo);

// === PRUEBA: obtener_por_id ===
$prestamo_guardado = $prestamos_repo->obtener_por_id($id_prestamo);
assert($prestamo_guardado !== null);
assert($prestamo_guardado->id === $id_prestamo);
assert($prestamo_guardado->id_cliente === $un_cliente->id);
assert($prestamo_guardado->monto === 10000.0);
assert($prestamo_guardado->plazos === 60);
assert($prestamo_guardado->interes_anual === 0.12);
assert($prestamo_guardado->estado === 'vigente');
assert($prestamo_guardado->str_fecha_inicio() === $prestamo->str_fecha_inicio());

// === PRUEBA: obtener_por_cliente ===
$prestamos_cliente = $prestamos_repo->obtener_por_cliente($un_cliente);
assert(is_array($prestamos_cliente));
assert(count($prestamos_cliente) > 0);

$encontrado = false;
foreach ($prestamos_cliente as $p) {
    assert($p->id_cliente === $un_cliente->id);
    if ($p->id === $id_prestamo) {
        $encontrado = true;
    }
}
assert($encontrado, "El préstamo creado no se encontró al listar por cliente");

// === PRUEBA: obtener_todos ===
$todos = $prestamos_repo->obtener_todos();
assert(is_array($todos));
assert(count($todos) > 0);

$encontrado = false;
foreach ($todos as $p) {
    assert($p->id > 0);
    assert(in_array($p->estado, ['vigente', 'pagado', 'atrasado']));
    assert($p->monto > 0);
    assert($p->plazos > 0);
    assert($p->interes_anual >= 0.0);
    if ($p->id === $id_prestamo) {
        $encontrado = true;
    }
}
assert($encontrado, "El préstamo creado no se encontró en la lista global");

// === Mensaje final ===
echo "✅ Todos los tests pasaron correctamente.\n";
