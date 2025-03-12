<?php
require_once 'Database.php';

class ParticipanteFormacion
{
    private $conn;
    private $table_name = "participante_formacion";

    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Método para asignar un participante a una formación
    public function asignar($id_participante, $id_formacion)
    {
        // Inserción directa en participante_formacion usando el id_formacion
        $query = "INSERT INTO " . $this->table_name . " (id_participante, id_formacion) VALUES (:id_participante, :id_formacion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_participante', $id_participante, PDO::PARAM_INT);
        $stmt->bindParam(':id_formacion', $id_formacion, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ["success" => true, "mensaje" => "Participante asignado correctamente."];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ["success" => false, "mensaje" => "Error: " . implode(" | ", $errorInfo)];
        }
    }

    // Método para eliminar una asignación de participante a formación
    public function eliminarAsignacion($id_asignacion)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_participante_formacion = :id_asignacion";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_asignacion', $id_asignacion, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ["success" => true, "mensaje" => "Asignación eliminada correctamente."];
        } else {
            $errorInfo = $stmt->errorInfo();
            return ["success" => false, "mensaje" => "Error: " . implode(" | ", $errorInfo)];
        }
    }
}
