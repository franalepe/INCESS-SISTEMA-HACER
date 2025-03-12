<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ajustar la ruta para incluir config.php
require_once '../../config.php';

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El formato del correo no es válido.";
        exit;
    }
    
    try {
        // Buscar al usuario por correo
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE correo_electronico = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            // Generar token y calcular fecha de expiración (1 hora)
            $token = bin2hex(random_bytes(16));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
    
            // Guardar token y fecha en la base de datos
            $stmt = $conn->prepare("UPDATE usuario SET token_recuperacion = ?, token_expira = ? WHERE id_usuario = ?");
            $stmt->execute([$token, $expira, $user['id_usuario']]);
    
            // Construir enlace de reinicio
            $reset_link = "http://localhost/sistema_definitivo/reset_password.php?token=" . $token;
            
            // Mejorar estilo: mensaje centrado con encabezado y botón estilizado
            echo "<div class='alert alert-info text-center mt-3'>";
            echo "<h5>Enlace de Recuperación</h5>";
            echo "<p>Copia y pega este enlace en tu navegador para restablecer tu contraseña:</p>";
            echo "<a href='" . htmlspecialchars($reset_link) . "' class='btn btn-outline-primary'>" . htmlspecialchars($reset_link) . "</a>";
            echo "</div>";
        } else {
            echo "El correo ingresado no se encuentra registrado.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Acceso no autorizado.";
}
