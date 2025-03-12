<?php
// editar.php
// Editar información de un usuario

$page_title = "Editar Usuario";
require_once "../../includes/header.php";
require_once '../../controllers/usuarioController.php';

$usuarioController = new UsuarioController();

// Verificar que el ID de usuario esté presente en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de usuario inválido.</div>";
    require_once "../../includes/footer.php";
    exit();
}

// Obtener datos del usuario
$response = $usuarioController->obtener($_GET['id']);
if (!$response['success']) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($response['mensaje']) . "</div>";
    require_once "../../includes/footer.php";
    exit();
}

$usuario = $response['data'];

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiar y validar campos del formulario
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = trim($_POST['password']);
    $tipo_usuario = trim($_POST['tipo_usuario']);
    $correo_electronico = trim($_POST['correo_electronico']);

    if (empty($nombre_usuario) || empty($tipo_usuario) || empty($correo_electronico)) {
        $error_message = "Todos los campos son requeridos.";
    } elseif (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } else {
        // Intentar actualizar el usuario
        try {
            $data = [
                'nombre_usuario' => $nombre_usuario,
                'password' => $password,
                'tipo_usuario' => $tipo_usuario,
                'correo_electronico' => $correo_electronico
            ];
            $resultado = $usuarioController->editar($_GET['id'], $data);
            if ($resultado['success']) {
                $success_message = "Usuario actualizado exitosamente.";
                // Actualizar los datos del usuario para reflejar los cambios
                $response = $usuarioController->obtener($_GET['id']);
                $usuario = $response['data'];
            } else {
                $error_message = $resultado['mensaje'];
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $error_message = "Hubo un error al intentar actualizar el usuario: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5" style="height: 80vh; overflow-y: auto;">
    <h2 class="mb-4">Editar Usuario</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
        </div>
        <div class="form-group">
            <label for="tipo_usuario">Tipo de Usuario</label>
            <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                <option value="admin" <?= $usuario['tipo_usuario'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="limitado" <?= $usuario['tipo_usuario'] == 'limitado' ? 'selected' : '' ?>>Limitado</option>
            </select>
        </div>
        <div class="form-group">
            <label for="correo_electronico">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>
