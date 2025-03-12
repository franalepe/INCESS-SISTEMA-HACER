<?php

require_once 'C:/xampp/htdocs/sistema_definitivo/inces_sistema/includes/verificar_sesion.php';


// listar.php
// Página para listar usuarios

$page_title = "Listado de Usuarios - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/usuarioController.php';

$usuarioController = new UsuarioController();
$resultado = $usuarioController->listar();

?>

<div class="container mt-5">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Listado de Usuarios</h2>
        </div>
        <div class="card-body">
            <a href="registrar.php" class="btn btn-secondary mb-3">Registrar nuevo usuario</a>

            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Correo Electrónico</th>
                            <th>Tipo de Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado['success']): ?>
                            <?php foreach ($resultado['data'] as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                                    <td><?= htmlspecialchars($usuario['correo_electronico']) ?></td>
                                    <td><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
                                    <td>
                                        <a href="editar.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="eliminar.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center"><?= htmlspecialchars($resultado['mensaje']) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
