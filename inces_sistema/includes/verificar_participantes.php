<?php
require_once __DIR__ . "/../models/Database.php";



try {
    // Verificar conexión a la base de datos
    $database = Database::getInstance();
    $conn = $database->getConnection();


    // Consulta para obtener los primeros 5 registros
    $query = "SELECT * FROM participante LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($result);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
