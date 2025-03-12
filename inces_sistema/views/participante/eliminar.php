<?php
// eliminar.php
// Eliminar un participante

require_once '../../controllers/participanteController.php';

$participanteController = new ParticipanteController();

// Verificar que el ID de participante esté presente en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de participante inválido.</div>";
    require_once "../../includes/footer.php";
    exit();
}

// Intentar eliminar el participante
$result = $participanteController->eliminar($_GET['id']);
if ($result['success']) {
    header('Location: listar.php?mensaje=Participante eliminado exitosamente.');
    exit();
} else {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($result['mensaje']) . "</div>";
    require_once "../../includes/footer.php";
    exit();
}
?>