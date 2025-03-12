<?php
// aperturas.php
// Gestión de aperturas de cursos

$page_title = "Apertura de Cursos";
require_once "../../includes/header.php";

require_once '../../controllers/cursoController.php';
$cursoController = new CursoController();

// Verificar si se ha proporcionado un ID de curso
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID del curso no proporcionado o inválido.</div>";
    require_once "../../includes/footer.php";
    exit();
}

$curso = $cursoController->obtener($_GET['id']);
if (!$curso) {
    echo "<div class='alert alert-danger'>Curso no encontrado.</div>";
    require_once "../../includes/footer.php";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar fechas
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';

    if (empty($fecha_inicio) || empty($fecha_fin)) {
        echo "<div class='alert alert-danger'>Las fechas de inicio y fin son requeridas.</div>";
    } elseif (!strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
        echo "<div class='alert alert-danger'>Formato de fecha inválido. Por favor, use el formato correcto.</div>";
    } elseif (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
        echo "<div class='alert alert-danger'>La fecha de inicio debe ser anterior a la fecha de fin.</div>";
    } else {
        $resultado = $cursoController->aperturarCurso($_GET['id'], [
            'nombre_curso' => $curso['nombre_curso'], // Assuming you want to use the existing course name
            'descripcion' => $curso['descripcion'], // Assuming you want to use the existing course description
            'id_tipo_curso' => $curso['id_tipo_curso'], // Assuming you want to use the existing course type ID
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
        if ($resultado['success']) {
            echo "<div class='alert alert-success'>Curso aperturado exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al aperturar el curso: " . $resultado['mensaje'] . "</div>";
        }
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4"><?= htmlspecialchars($curso['nombre_curso']) ?></h1>
    <form method="post">
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha de Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
        </div>
        <button type="submit" class="btn btn-primary">Aperturar Curso</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>
