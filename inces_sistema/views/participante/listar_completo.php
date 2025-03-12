<?php
// listar_completo.php
// Listar participantes con todos los datos

$page_title = "Listado Completo de Participantes";
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
    <h2 class="mb-4">Listado Completo de Participantes</h2>
    
    <div class="row mb-3">
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
            <a href="registrar.php" class="btn btn-primary">Registrar nuevo participante</a>
        </div>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php elseif (isset($info_message)): ?>
        <div class="alert alert-info"><?= $info_message ?></div>
    <?php else: ?>

        <?php if (!empty($participantes['data'])): ?>
            <!-- Tabla de participantes -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo ID</th>
                        <th>Identificación</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Parroquia</th>
                        <th>Comuna</th>
                        <th>Fecha Nacimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes['data'] as $participante): ?>
                        <tr>
                            <td><?= htmlspecialchars($participante['id_participante']) ?></td>
                            <td><?= htmlspecialchars($participante['tipo_identificacion']) ?></td>
                            <td><?= htmlspecialchars($participante['identificacion']) ?></td>
                            <td><?= htmlspecialchars($participante['nombres']) ?></td>
                            <td><?= htmlspecialchars($participante['apellidos']) ?></td>
                            <td><?= htmlspecialchars($participante['correo']) ?></td>
                            <td><?= htmlspecialchars($participante['telefono']) ?></td>
                            <td><?= htmlspecialchars($participante['direccion']) ?></td>
                            <td><?= htmlspecialchars($participante['estado']) ?></td>
                            <td><?= htmlspecialchars($participante['municipio']) ?></td>
                            <td><?= htmlspecialchars($participante['parroquia']) ?></td>
                            <td><?= htmlspecialchars($participante['comuna']) ?></td>
                            <td><?= htmlspecialchars($participante['fecha_nacimiento']) ?></td>
                            <td>
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
    <?php endif; ?>
</div>

<?php require_once "../../includes/footer.php"; ?>
