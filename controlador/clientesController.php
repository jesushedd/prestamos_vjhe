<?php
$COMPONENTES = $VISTAS . 'componentes/';
$CONFIG = __DIR__. '/config/';

require $CONFIG . 'init.php';
require ROOT_DIR . 'modelo/UsuarioModelo.php';
require ROOT_DIR . 'config/conexion.php';
require ROOT_DIR . 'modelo/ClienteModelo.php';

//cchecar el tipo de usuario
$usuario = unserialize($_SESSION['usuario']);

if ($usuario->tipo_usuario->nombre_tipo === ADMINISTRADOR) {
    $SUBVISTA = $VISTAS . 'componentes/clientes_admin.php';
} elseif ($usuario->tipo_usuario->nombre_tipo === ASISTENTE) {
    $SUBVISTA = $VISTAS . 'componentes/clientes_asist.php';
}
//pasar el contexto
$repo_clientes = new ClienteRepositorio(conectar());
$CLIENTES = $repo_clientes->obtener_clientes();
//cargar vista clientes
require $VISTAS . 'clientes.php';





?>