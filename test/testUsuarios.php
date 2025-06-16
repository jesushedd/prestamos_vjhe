<?php
require_once __DIR__ .  '/../config/local.php';
require_once  __DIR__  . '/../config/conexion.php';

require_once ROOT_DIR . 'modelo/UsuarioModelo.php';

$conexion = conectar();
$repo_usuarios = new UsuarioRepositorio($conexion);

//checar tipos base : administrador , asistente
$id_Tadministrador = 1;
$id_Tasistente = 2;
//:adminsitrador
$Tadmin = $repo_usuarios->obtener_tipo_por_id($id_Tadministrador);
assert('administrador' === $Tadmin->nombre_tipo);
assert(1 === $Tadmin->id);
//:asistente
$Tasist = $repo_usuarios->obtener_tipo_por_id($id_Tasistente);
assert('asistente' === $Tasist->nombre_tipo);
assert(2 === $Tasist->id);


//test obtener los usuarios correctos por TipoUsuario
$administradores = $repo_usuarios->obtener_usuarios_por_tipo($Tadmin);
assert($administradores[0]->nombre === 'root_nombre');

//test crear un usuario
$nuevo_usuario = new Usuario("yo", "no", "50709", $Tasist);

$nuevo_usuario->nombre_usuario = "yo_usuario7";
$nuevo_usuario->password = password_hash("123456", PASSWORD_DEFAULT);

$id_asignado = $repo_usuarios->crear($nuevo_usuario);
$asistentes = $repo_usuarios->obtener_usuarios_por_tipo($Tasist);
print_r($asistentes);
assert($asistentes[0]->nombre === 'yo');
assert($repo_usuarios->obtener_usuario_por_id($id_asignado)->dni === "50709");
assert(password_verify('123456', ($repo_usuarios->obtener_usuario_por_id($id_asignado))->password));

$usuario_por_nombre = $repo_usuarios->usuario_por_nombre("yo_usuario7");
assert(password_verify('123456', $usuario_por_nombre->password));



?>