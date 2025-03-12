<?php
require_once __DIR__ . "/../models/Database.php";



header('Content-Type: application/json');

try {
    // Verificar conexión a la base de datos
    $database = Database::getInstance();
    $conn = $database->getConnection();


    // Obtener el conteo de participantes
    $query = "SELECT COUNT(*) as total FROM participante";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && isset($result['total'])) {
        echo json_encode([
            'success' => true,
            'count' => (int)$result['total']
        ]);
    } else {
        throw new Exception("No se pudo obtener el conteo de participantes");
    }


} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
