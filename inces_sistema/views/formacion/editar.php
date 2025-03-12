<?php
// filepath: /c:/xampp/htdocs/sistema_definitivo/inces_sistema/views/curso/editar.php
// editar.php
// Página para editar una formación

$page_title = "Editar Formación - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/formacionController.php';

$formacionController = new FormacionController();

// Verificar que se haya enviado el id de la formación a editar
if (!isset($_GET['id'])) {
    header("Location: listar.php?error=" . urlencode("ID de formación no especificado"));
    exit();
}

$id = intval($_GET['id']);
// Se utiliza obtenerPorId en el controlador
$resultado = $formacionController->obtenerPorId($id);

if (!$resultado['success']) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Error: " . htmlspecialchars($resultado['mensaje']) . "</div></div>";
    require_once "../../includes/footer.php";
    exit();
}

$formacion = $resultado['data'];

// Obtener opciones formativas (se usa el nuevo método)
$tiposFormacion = $formacionController->obtenerTiposFormacion();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre_curso = trim($_POST['nombre_curso']);
    $descripcion = trim($_POST['descripcion']);
    $id_tipo_curso = trim($_POST['id_tipo_curso']);
    $fecha_inicio = trim($_POST['fecha_inicio']);
    $fecha_fin = trim($_POST['fecha_fin']);

    if (empty($nombre_curso) || empty($descripcion) || empty($id_tipo_curso) || empty($fecha_inicio) || empty($fecha_fin)) {
        $error_message = "Todos los campos son requeridos.";
    } else {
        $data = [
            'id_curso' => $id,
            'nombre_curso' => $nombre_curso,
            'descripcion' => $descripcion,
            'id_tipo_curso' => $id_tipo_curso,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ];
        $actualizar = $formacionController->actualizar($data);
        if ($actualizar['success']) {
            $success_message = "Formación actualizada exitosamente.";
            // Recargar datos actualizados
            $resultado = $formacionController->obtenerPorId($id);
            if ($resultado['success']) {
                $formacion = $resultado['data'];
            }
        } else {
            $error_message = $actualizar['mensaje'];
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Formación</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nombre_curso" class="form-label">Nombre de la Formación</label>
            <input type="text" name="nombre_curso" class="form-control" value="<?= htmlspecialchars($formacion['nombre_formacion'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($formacion['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="id_tipo_curso" class="form-label">Opción Formativa</label>
            <select name="id_tipo_curso" class="form-control" required>
                <option value="">Seleccione una opción formativa</option>
                <?php if ($tiposFormacion['success']): ?>
                    <?php foreach ($tiposFormacion['data'] as $tipo): ?>
                        <option value="<?= htmlspecialchars($tipo['id_tipo_curso']) ?>" 
                        <?= isset($formacion['id_tipo_curso']) && $formacion['id_tipo_curso'] == $tipo['id_tipo_curso'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo['nombre_tipo_curso']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No se pudieron cargar las opciones formativas</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($formacion['fecha_inicio']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($formacion['fecha_fin']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Formación</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>