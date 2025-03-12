<?php
require_once "../../includes/header.php";
require_once '../../models/Database.php';

// Obtener asignaciones con join entre participante_formacion, participante y formacion
$database = Database::getInstance();
$conn = $database->getConnection();
$query = "SELECT pf.id_participante_formacion, p.identificacion, p.nombres, p.apellidos, f.nombre_formacion 
          FROM participante_formacion pf 
          JOIN participante p ON pf.id_participante = p.id_participante 
          JOIN formacion f ON pf.id_formacion = f.id_formacion";
$stmt = $conn->prepare($query);
$stmt->execute();
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <!-- Mensajes de alerta -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Inicio de la Card -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Asignaciones de Participantes a Formaciones</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Asignación</th>
                            <th>Identificación Participante</th>
                            <th>Nombres y Apellidos</th>
                            <th>Formación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asignaciones)): ?>
                            <?php foreach ($asignaciones as $asignacion): ?>
                                <tr>
                                    <td><?= htmlspecialchars($asignacion['id_participante_formacion']) ?></td>
                                    <td><?= htmlspecialchars($asignacion['identificacion']) ?></td>
                                    <td><?= htmlspecialchars($asignacion['nombres'] . " " . $asignacion['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($asignacion['nombre_formacion']) ?></td>
                                    <td>
                                        <a href="/sistema_definitivo/inces_sistema/controllers/eliminarAsignacion.php?id=<?= htmlspecialchars($asignacion['id_participante_formacion']) ?>" 
                                           onclick="return confirm('¿Deseas eliminar esta asignación?');" 
                                           class="btn btn-danger btn-sm">
                                           Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay asignaciones registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Fin de la Card -->
</div>

<?php require_once "../../includes/footer.php"; ?>
