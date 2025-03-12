<?php
// eliminar.php
// Página para eliminar un curso

// Eliminar la inclusión del header para evitar enviar output previo
// require_once '../../includes/header.php';
require_once '../../controllers/formacionController.php';

$formacionController = new FormacionController();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirigir a la lista de cursos si el ID no es válido
    header('Location: listar.php');
    exit();
}

$id = intval($_GET['id']);

$resultado = $formacionController->eliminar($id);

if ($resultado['success']) {
    header('Location: listar.php?mensaje=' . urlencode($resultado['mensaje']));
    exit();
} else {
    header('Location: listar.php?error=' . urlencode($resultado['mensaje']));
    exit();
}
?>