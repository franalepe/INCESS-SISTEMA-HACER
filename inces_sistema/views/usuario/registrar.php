<?php
// registrar.php
// Página para registrar un nuevo usuario

$page_title = "Registrar Usuario - Sistema INCES";
require_once "../../includes/header.php";
require_once '../../controllers/usuarioController.php';

$usuarioController = new UsuarioController();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiar y validar campos del formulario
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = trim($_POST['password']);
    $tipo_usuario = trim($_POST['tipo_usuario']);
    $correo_electronico = trim($_POST['correo_electronico']);

    if (empty($nombre_usuario) || empty($password) || empty($tipo_usuario) || empty($correo_electronico)) {
        $error_message = "El nombre de usuario, contraseña, tipo de usuario y correo electrónico son requeridos.";
    } elseif (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } else {
        // Intentar registrar el usuario
        try {
            $data = [
                'nombre_usuario' => $nombre_usuario,
                'password' => $password,
                'tipo_usuario' => $tipo_usuario,
                'correo_electronico' => $correo_electronico
            ];
            $resultado = $usuarioController->registrar($data);
            if ($resultado['success']) {
                $success_message = "Usuario registrado exitosamente.";
                // Limpiar los campos del formulario
                $_POST = [];
            } else {
                $error_message = $resultado['mensaje'];
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $error_message = "Hubo un error al intentar registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Registrar Usuario</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required placeholder="Usuario" value="<?= isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Contraseña">
        </div>
        <div class="form-group">
            <label for="tipo_usuario">Tipo de Usuario</label>
            <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                <option value="admin" <?= isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="limitado" <?= isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'limitado' ? 'selected' : '' ?>>Limitado</option>
            </select>
        </div>
        <div class="form-group">
            <label for="correo_electronico">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required placeholder="correo@ejemplo.com" value="<?= isset($_POST['correo_electronico']) ? htmlspecialchars($_POST['correo_electronico']) : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>
