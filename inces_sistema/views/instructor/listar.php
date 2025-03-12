<?php
require_once 'C:/xampp/htdocs/sistema_definitivo/inces_sistema/includes/verificar_sesion.php';

$page_title = "Instructores - Sistema INCES";
require_once '../../includes/header.php';
require_once '../../controllers/instructorController.php';

$instructorController = new InstructorController();
$instructores = $instructorController->listar();
?>

<div class="container mt-5">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Listado de Formadores</h2>
        </div>
        <div class="card-body">
            <a href="registrar_instructor.php" class="btn btn-secondary mb-3">Registrar Nuevo Formador</a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($instructores) && count($instructores) > 0): ?>
                            <?php foreach($instructores as $instructor): ?>
                                <tr>
                                    <td><?= htmlspecialchars($instructor['id_instructor']) ?></td>
                                    <td><?= htmlspecialchars($instructor['nombres']) ?></td>
                                    <td><?= htmlspecialchars($instructor['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($instructor['correo_electronico']) ?></td>
                                    <td><?= htmlspecialchars($instructor['telefono']) ?></td>
                                    <td>
                                        <a href="editar.php?id=<?= htmlspecialchars($instructor['id_instructor']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="eliminar.php?id=<?= htmlspecialchars($instructor['id_instructor']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este formador?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No se encontraron Formadores.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
