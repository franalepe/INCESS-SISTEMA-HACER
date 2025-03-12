<?php
require_once __DIR__ . '/participanteFormacionController.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $controller = new ParticipanteFormacionController();
    $resultado = $controller->eliminarAsignacion($_GET['id']);
    if ($resultado['success']) {
        header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?mensaje=" . urlencode($resultado['mensaje']));
    } else {
        header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?error=" . urlencode($resultado['mensaje']));
    }
    exit();
} else {
    header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?error=" . urlencode("ID inválido."));
    exit();
}
