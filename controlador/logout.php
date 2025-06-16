<?php

require_once ROOT_DIR . 'modelo/UsuarioModelo.php';
require_once ROOT_DIR . 'config/conexion.php';
session_start();

if (isset($_SESSION['usuario'])) {

    session_destroy();
    session_unset();
}

header("Location: " . ROOT_ROUTE . 'home');
