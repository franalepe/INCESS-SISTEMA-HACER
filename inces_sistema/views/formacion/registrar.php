<?php
// registrar.php
// Página para registrar una nueva formación

$page_title = "Registrar Formación - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/formacionController.php';

$formacionController = new FormacionController();
// Se llama al método correcto del controlador
$tiposFormacion = $formacionController->obtenerTiposCurso();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiar y validar campos del formulario
    $nombre_curso = trim($_POST['nombre_curso']);
    $descripcion = trim($_POST['descripcion']);
    $id_tipo_curso = trim($_POST['id_tipo_curso']);
    $fecha_inicio = trim($_POST['fecha_inicio']);
    $fecha_fin = trim($_POST['fecha_fin']);

    if (empty($nombre_curso) || empty($descripcion) || empty($id_tipo_curso) || empty($fecha_inicio) || empty($fecha_fin)) {
        $error_message = "Todos los campos son requeridos.";
    } else {
        // Intentar registrar la formación
        try {
            $data = [
                'nombre_curso' => $nombre_curso,
                'descripcion' => $descripcion,
                'id_tipo_curso' => $id_tipo_curso,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            ];
            $resultado = $formacionController->registrar($data);
            if ($resultado['success']) {
                $success_message = "Formación registrada exitosamente.";
                // Limpiar los campos del formulario
                $_POST = [];
            } else {
                $error_message = $resultado['mensaje'];
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $error_message = "Hubo un error al intentar registrar la formación: " . $e->getMessage();
        }
    }
}
?>

<h2 class="mb-4">Registrar Nueva Formación</h2>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nombre_curso" class="form-label">Nombre de la Formación</label>
        <input type="text" name="nombre_curso" class="form-control" value="<?= isset($_POST['nombre_curso']) ? htmlspecialchars($_POST['nombre_curso']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3" required><?= isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '' ?></textarea>
    </div>
    <div class="mb-3">
        <label for="id_tipo_curso" class="form-label">Opción Formativa</label>
        <select name="id_tipo_curso" class="form-control" required>
            <option value="">Seleccione una opción formativa</option>
            <?php if ($tiposFormacion['success']): ?>
                <?php foreach ($tiposFormacion['data'] as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo['id_tipo_curso']) ?>" <?= isset($_POST['id_tipo_curso']) && $_POST['id_tipo_curso'] == $tipo['id_tipo_curso'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['nombre_tipo_curso']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled><?= htmlspecialchars($tiposFormacion['mensaje']) ?></option>
            <?php endif; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio" class="form-control" value="<?= isset($_POST['fecha_inicio']) ? htmlspecialchars($_POST['fecha_inicio']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
        <input type="date" name="fecha_fin" class="form-control" value="<?= isset($_POST['fecha_fin']) ? htmlspecialchars($_POST['fecha_fin']) : '' ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

<?php require_once "../../includes/footer.php"; ?>