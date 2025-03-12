<?php
require_once __DIR__ . '/participanteFormacionController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_formacion'], $_POST['id_participante']) && is_numeric($_POST['id_formacion']) && is_numeric($_POST['id_participante'])) {
        $controller = new ParticipanteFormacionController();
        $resultado = $controller->asignar($_POST['id_participante'], $_POST['id_formacion']);
        if ($resultado['success']) {
            header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?mensaje=" . urlencode($resultado['mensaje']));
            exit();
        } else {
            header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?error=" . urlencode($resultado['mensaje']));
            exit();
        }
    } else {
        header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php?error=" . urlencode("Datos inválidos."));
        exit();
    }
} else {
    header("Location: /sistema_definitivo/inces_sistema/views/formacion/asignaciones.php");
    exit();
}
