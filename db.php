<?php
// db.php
// Archivo para gestionar la conexión a la base de datos

require_once __DIR__ . '/../config.php'; // Configuración de la base de datos

function getDatabaseConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Habilitar excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo de obtención por defecto
            PDO::ATTR_EMULATE_PREPARES => false, // Deshabilitar emulación de preparaciones
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log del error y mensaje genérico en producción
        error_log("Error de conexión: " . $e->getMessage());
        die("No se pudo conectar a la base de datos. Inténtalo más tarde.");
    }
}
?>
