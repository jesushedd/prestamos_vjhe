<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: " . ROOT_ROUTE . 'login');
}