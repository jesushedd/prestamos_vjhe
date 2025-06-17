<?php
session_start();

require_once ROOT_DIR . 'modelo/UsuarioModelo.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: " . ROOT_ROUTE . 'login');
}

$usuario = unserialize($_SESSION['usuario']);

if ($usuario->tipo_usuario->nombre_tipo === ADMINISTRADOR) {
        $HEADER = $VISTAS . 'componentes/admin_header.php';
    } elseif ($usuario->tipo_usuario->nombre_tipo === ASISTENTE) {
        $HEADER = $VISTAS . 'componentes/header.php';
    }