<?php

$COMPONENTES = $VISTAS . 'componentes/';
$CONFIG = __DIR__ . '/config/';
require_once ROOT_DIR . 'modelo/UsuarioModelo.php';
require_once ROOT_DIR . 'config/conexion.php';

require_once $CONFIG . 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    
    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
        header("Location:  " . ROOT_ROUTE . 'home');
    }

    $repo_usuarios = new UsuarioRepositorio(conectar());
    $USUARIOS = $repo_usuarios->obtener_todos();
    $TIPOS = $repo_usuarios->obtener_tipos();

    //print_r($USUARIOS);

    require $VISTAS . 'usuarios.php';
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //cchecar el tipo de usuario
    $usuario = unserialize($_SESSION['usuario']);

    if ($usuario->tipo_usuario->nombre_tipo !== ADMINISTRADOR) {
        header("Location:  " . ROOT_ROUTE . 'home');
    }

    $repo_usuarios = new UsuarioRepositorio(conectar());


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
