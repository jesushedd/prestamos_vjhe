<?php

$COMPONENTES = $VISTAS . 'componentes/';
$CONFIG = __DIR__ . '/config/';
require_once ROOT_DIR . 'modelo/UsuarioModelo.php';
require_once ROOT_DIR . 'config/conexion.php';

require_once $CONFIG . 'init.php';


$repo_usuarios = new UsuarioRepositorio(conectar());

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    
    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
        header("Location:  " . ROOT_ROUTE . 'home');
    }

    
    $USUARIOS = $repo_usuarios->obtener_todos();
    $TIPOS = $repo_usuarios->obtener_tipos();

    //print_r($USUARIOS);

    require $VISTAS . 'usuarios.php';
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'Guardar') {
    //cchecar el tipo de usuario
    $usuario = unserialize($_SESSION['usuario']);

    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
        header("Location:  " . ROOT_ROUTE . 'home');
    }

    //obtener datos del form

    $usuario = new Usuario(
        $_POST['nombre'], 
        $_POST['apellido'],
        $_POST['dni'],
        $repo_usuarios->obtener_tipo_por_id((int) $_POST['tipo'])
    );

    $usuario->nombre_usuario = $_POST['nombre_usuario'];
    $usuario->set_password($_POST['password']);

    $repo_usuarios->crear($usuario);

    header("Location:  " . ROOT_ROUTE . 'usuarios');


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'Actualizar') {
    //cchecar el tipo de usuario
    $usuario = unserialize($_SESSION['usuario']);

    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
        header("Location:  " . ROOT_ROUTE . 'home');
    }

    //obtener datos del form

    $usuario = $repo_usuarios->obtener_usuario_por_id((int) $_POST['editando_id']);

    $usuario->nombre_usuario = $_POST['editando_nombre_usuario'];
    $usuario->nombre = $_POST['editando_nombre'];
    $usuario->apellido = $_POST['editando_apellido'];
    $usuario->dni = $_POST['editando_dni'];
    $usuario->tipo_usuario = $repo_usuarios->obtener_tipo_por_nombre( $_POST['editando_tipo']);
    
    $repo_usuarios->actualizar($usuario);

    header("Location:  " . ROOT_ROUTE . 'usuarios');


}
