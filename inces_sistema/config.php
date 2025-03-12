<?php
// config.php

// Definir el entorno manualmente (puedes cambiar 'development' a 'production' en producción)
define('ENV', getenv('ENV') ?: 'development');  // Cambia a 'production' en un entorno de producción

// Configuración de la visualización de errores (solo en desarrollo)
if (ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);  // Mostrar errores solo en desarrollo
} else {
    error_reporting(E_ALL);       // Registrando todos los errores
    ini_set('display_errors', 0);  // No mostrar errores al usuario
    ini_set('log_errors', 1);      // Activar el registro de errores
    ini_set('error_log', __DIR__ . '/logs/php_error.log');  // Ruta del log de errores
}

// Configuración de la base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'inces_sistema');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'tu_contraseña_default');  // Valor predeterminado si no está definida la variable de entorno

// Configuración del SMTP
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.example.com');
define('SMTP_USER', getenv('SMTP_USER') ?: 'user@example.com');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: 'contraseña_smtp');

// URL base de la aplicación
define("BASE_URL", "http://localhost/sistema_definitivo/inces_sistema/");

// Configuración para sesiones
define('SESSION_TIMEOUT', getenv('SESSION_TIMEOUT') ?: 3600); // Tiempo de expiración de sesión en segundos (1 hora)

// Asegurarse de que todas las configuraciones requeridas estén definidas
$required_constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'SMTP_HOST', 'SMTP_USER', 'SMTP_PASSWORD', 'BASE_URL'];

foreach ($required_constants as $constant) {
    if (empty(constant($constant))) {  // Verificar que las variables están definidas
        die("Error: La configuración $constant no está definida en el entorno.");
    }
}
