<?php
// registrar_instructor.php
// Página para registrar un nuevo instructor

$page_title = "Registrar Instructor - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/instructorController.php';

$instructorController = new InstructorController();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
        $error_message = "Todos los campos son requeridos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
        $error_message = "El teléfono solo debe contener números.";
    } else {
        // Intentar registrar el instructor
        $data = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'telefono' => $telefono
        ];
        $resultado = $instructorController->registrar($data);
        if ($resultado['success']) {
            $success_message = "Instructor registrado exitosamente.";
            $_POST = []; // Limpiar los campos del formulario
        } else {
            $error_message = $resultado['mensaje'];
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Registrar Nuevo Instructor</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?= isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : '' ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>

</div>

<?php require_once "../../includes/footer.php"; ?>
