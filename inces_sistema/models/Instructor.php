<?php
// Instructor.php
// Modelo para gestionar los instructores
require_once __DIR__ . '/Database.php'; // Asegurarse de incluir la clase Database

class Instructor
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection(); // Obtener la conexión a la base de datos
    }

    // Método para registrar un nuevo instructor
    public function registrar($nombre, $apellido, $correo, $telefono)
    {
        $query = "INSERT INTO instructor (nombres, apellidos, correo_electronico, telefono) VALUES (:nombre, :apellido, :correo, :telefono)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            return ["success" => true, "mensaje" => "Instructor registrado exitosamente."];
        } else {
            // Capturar el error específico
            $errorInfo = $stmt->errorInfo();
            return ["success" => false, "mensaje" => "Error al registrar el instructor: " . $errorInfo[2]];
        }
    }

    // Método para listar todos los instructores
    public function listar()
    {
        $query = "SELECT * FROM instructor";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un instructor por ID
    public function obtener($id)
    {
        $query = "SELECT * FROM instructor WHERE id_instructor = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return ["success" => true, "data" => $data];
        }
        return ["success" => false, "mensaje" => "Instructor no encontrado."];
    }

    // Método para editar un instructor
    public function editar($id, $data)
    {
        $query = "UPDATE instructor SET nombres = :nombre, apellidos = :apellido, correo_electronico = :correo, telefono = :telefono WHERE id_instructor = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':correo', $data['correo']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para eliminar un instructor
    public function eliminar($id)
    {
        $query = "DELETE FROM instructor WHERE id_instructor = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
