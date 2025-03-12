
<?php

// listar.php
// Listar participantes

$page_title = "Listado de Participantes";
require_once "../../includes/header.php";

require_once '../../controllers/participanteController.php';

// Verificar si hay una búsqueda activa
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Verificar si la clase existe antes de instanciarla
if (class_exists('ParticipanteController')) {
    $participanteController = new ParticipanteController();
    try {
        $participantes = $participanteController->listar($busqueda);

        if (!$participantes['success']) {
            $error_message = $participantes['mensaje'];
        }
    } catch (Exception $e) {
        $error_message = "Hubo un error al obtener los participantes: " . $e->getMessage();
    }
    
    // Mostrar mensaje si no hay resultados de búsqueda
    if (!empty($busqueda) && empty($participantes['data'])) {
        $info_message = "No se encontraron resultados para: " . htmlspecialchars($busqueda);
    }

} else {
    $error_message = "Error: La clase ParticipanteController no fue encontrada.";
}
?>

<div class="container mt-5">
    <!-- Inicio de la Card -->
    <div class="card shadow mb-4">
        <!-- MODIFICADO: Cambiado bg-info por bg-primary -->
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Listado de Participantes</h2>
        </div>
        <div class="card-body">
            <!-- Formulario de búsqueda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="get" action="" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por cédula, nombre o apellido">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <a href="registrar.php" class="btn btn-secondary">Registrar nuevo participante</a>
                </div>
            </div>

            <!-- Mensajes de alerta -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php elseif (isset($info_message)): ?>
                <div class="alert alert-info"><?= $info_message ?></div>
            <?php endif; ?>

            <!-- Tabla de participantes -->
            <div class="table-responsive">
                <?php if (!empty($participantes['data'])): ?>
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Identificación</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participantes['data'] as $participante): ?>
                                <tr>
                                    <td><?= htmlspecialchars($participante['id_participante']) ?></td>
                                    <td><?= htmlspecialchars($participante['identificacion']) ?></td>
                                    <td><?= htmlspecialchars($participante['nombres']) ?></td>
                                    <td><?= htmlspecialchars($participante['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($participante['correo']) ?></td>
                                    <td>
                                        <a href="ver.php?id=<?= htmlspecialchars($participante['id_participante']) ?>" class="btn btn-info btn-sm">Ver</a>
                                        <a href="editar.php?id=<?= htmlspecialchars($participante['id_participante']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="eliminar.php?id=<?= htmlspecialchars($participante['id_participante']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este participante?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">No hay participantes registrados.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Fin de la Card -->
</div>

<?php require_once "../../includes/footer.php"; ?>
