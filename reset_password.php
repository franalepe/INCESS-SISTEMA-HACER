<?php
require_once 'config.php';

if (!isset($_GET['token'])) {
    die("Token no proporcionado.");
}

$token = $_GET['token'];
try {
    $stmt = $conn->prepare("SELECT id_usuario, token_expira FROM usuario WHERE token_recuperacion = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

if (!$user || strtotime($user['token_expira']) < time()) {
    die("El token es inválido o ha expirado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("UPDATE usuario SET contrasena = ?, token_recuperacion = NULL, token_expira = NULL WHERE id_usuario = ?");
            $stmt->execute([$hashed, $user['id_usuario']]);
            echo "<div class='alert alert-success'>Tu contraseña ha sido actualizada correctamente. Ahora puedes iniciar sesión.</div>";
            echo "<script>setTimeout(function(){ window.location.href = ' inces_sistema/views/login.php'; }, 2000);</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error al actualizar la contraseña.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h3 class="mb-4">Restablecer Contraseña</h3>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Restablecer</button>
    </form>
</div>
</body>
</html>
