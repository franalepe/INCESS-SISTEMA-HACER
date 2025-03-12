<?php
require_once '../../config.php';  // Asegúrate de que aquí se defina BASE_URL
require_once '../../controllers/instructorController.php';

$instructorController = new InstructorController();

// Validar que el ID de instructor esté presente
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de instructor inválido.</div>";
    require_once '../../includes/footer.php';
    exit();
}

// Intentar eliminar el instructor
$result = $instructorController->eliminar($_GET['id']);
if ($result['success']) {
    header("Location: " . BASE_URL . "views/instructor/listar.php?mensaje=Instructor eliminado exitosamente.");
    exit();
} else {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($result['mensaje']) . "</div>";
    require_once '../../includes/footer.php';
    exit();
}
?>