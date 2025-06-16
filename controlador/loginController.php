<?php
//dependecias
require_once ROOT_DIR . 'modelo/UsuarioModelo.php';
require_once ROOT_DIR . 'config/conexion.php';
$repo_usuarios = new UsuarioRepositorio(conectar());
$COMPONENTES = $VISTAS . 'componentes/';
$CONFIG = __DIR__ . '/config/';


session_start();
//si ya tiene una seccion activa redirigir a home
if (isset($_SESSION['usuario'])) {
    header("Location: " . ROOT_ROUTE . 'home');

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password_typed = $_POST['password'];

    $usuario = $repo_usuarios->usuario_por_nombre($nombre_usuario);

    $verificado = password_verify($password_typed, $usuario->password);

    if ($verificado) {
        
        $_SESSION['usuario'] = serialize($usuario);
    }

    header("Location: " . ROOT_ROUTE . 'home');
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require $VISTAS . 'login.php';
} else {
    echo "wtf";
}






/*//verificar usuario
$nombre_usuario = $_POST['nombre_usuario'];
$password_typed = $_POST['password'];

$usuario = $repo_usuarios->usuario_por_nombre($nombre_usuario);

$verificado = password_verify($password_typed,  $usuario->password); 

if ($verificado) {
    session_start();    
    $_SESSION['usuario'] = serialize($usuario);
}

header("Location: ". ROOT_ROUTE . 'home');




?>*/