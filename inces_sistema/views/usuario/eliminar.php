<?php
// eliminar.php
// Página para eliminar un usuario

require_once '../../includes/header.php';
require_once '../../controllers/usuarioController.php';

$usuarioController = new UsuarioController();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirigir a la lista de usuarios si el ID no es válido
    header('Location: listar.php');
    exit();
}

$id = $_GET['id'];

$resultado = $usuarioController->eliminar($id);

if ($resultado['success']) {
    // Redirigir a la lista de usuarios con un mensaje de éxito
    header('Location: listar.php?mensaje=' . urlencode($resultado['mensaje']));
    exit();
} else {
    // Redirigir a la lista de usuarios con un mensaje de error
    header('Location: listar.php?error=' . urlencode($resultado['mensaje']));
    exit();
}
?>