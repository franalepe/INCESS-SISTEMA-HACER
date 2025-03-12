<?php
// verificar_sesion.php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir al login
    header("Location: views/login.php");
    exit();
}
?>
