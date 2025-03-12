<?php
$page_title = "Editar Instructor - Sistema INCES";
require_once '../../includes/header.php';
require_once '../../controllers/instructorController.php';

$instructorController = new InstructorController();

// Validar el ID del instructor
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de instructor inválido.</div>";
    require_once '../../includes/footer.php';
    exit();
}

// Obtener datos del instructor
$resultado = $instructorController->obtener($_GET['id']);
if (!$resultado['success']) {
    echo "<div class='alert alert-danger'>Instructor no encontrado.</div>";
    require_once '../../includes/footer.php';
    exit();
}
$instructor = $resultado['data'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo   = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
        $error_message = "Todos los campos son requeridos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
        $error_message = "El teléfono solo debe contener números.";
    } else {
        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'correo'   => $correo,
            'telefono' => $telefono
        ];
        if ($instructorController->editar($_GET['id'], $data)) {
            $success_message = "Instructor actualizado exitosamente.";
            // Actualizar datos del formulario
            $resultado = $instructorController->obtener($_GET['id']);
            $instructor = $resultado['data'];
        } else {
            $error_message = "Error al actualizar el instructor. Por favor, intenta de nuevo.";
        }
    }
}
?>
<div class="container mt-5">
    <h2 class="mb-4">Editar Instructor</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($instructor['nombres']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($instructor['apellidos']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($instructor['correo_electronico']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($instructor['telefono']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Instructor</button>
    </form>
</div>
<?php require_once '../../includes/footer.php'; ?>