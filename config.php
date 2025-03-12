<?php
declare(strict_types=1);

$host = 'localhost';
$db   = 'inces_sistema';
$user = 'root';           // Modifica según corresponda
$pass = '';               // Modifica según corresponda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Registrar el error y detener la ejecución
    die("Error de conexión: " . $e->getMessage());
}
