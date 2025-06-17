<?php
$COMPONENTES = $VISTAS . 'componentes/';
$CONFIG = __DIR__ . '/config/';

require_once ROOT_DIR . 'modelo/UsuarioModelo.php';
require_once ROOT_DIR . 'config/conexion.php';
require_once ROOT_DIR . 'modelo/ClienteModelo.php';

require_once $CONFIG . 'init.php';


$repo_clientes = new ClienteRepositorio(conectar());

if (!isset($_SESSION['usuario'])) {
    header("Location:  " . ROOT_ROUTE . 'home');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //cchecar el tipo de usuario
    $usuario = unserialize($_SESSION['usuario']);

    if ($usuario->tipo_usuario->nombre_tipo === ADMINISTRADOR) {
        $SUBVISTA = $VISTAS . 'componentes/clientes_admin.php';
    } elseif ($usuario->tipo_usuario->nombre_tipo === ASISTENTE) {
        $SUBVISTA = $VISTAS . 'componentes/clientes_asist.php';
    }
    //pasar el contexto
    
    $CLIENTES = $repo_clientes->obtener_clientes();
    //cargar vista clientes
    require $VISTAS . 'clientes.php';
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //cchecar el tipo de usuario
    $usuario = unserialize($_SESSION['usuario']);

    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
         header("Location:  " . ROOT_ROUTE . 'home');
    } 

    
    //obtener datos del form
    $cliente = $repo_clientes->obtenerPorId((int) $_POST['editando_id']);

    $cliente->nombre = $_POST['editando_nombre'];
    $cliente->apellido = $_POST['editando_apellido'];
    $cliente->email = $_POST['editando_email'];
    $cliente->direccion = $_POST['editando_direccion'];
    $cliente->telefono = $_POST['editando_telefono'];

    $repo_clientes->actualizar($cliente);

    header("Location:  " . ROOT_ROUTE . 'clientes');


}