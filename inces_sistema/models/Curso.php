<?php

require_once 'Database.php';

class Curso
{
    private $conn;
    private $table_name = "curso";

    public function __construct()
    {
        // Obtener la instancia de la conexión a la base de datos
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function listar($offset = 0, $limit = 10)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " LIMIT :offset, :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return ["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            error_log("Error al listar los cursos: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar los cursos."];
        }
    }

    public function registrar($nombre_curso, $descripcion, $id_tipo_curso, $fecha_inicio, $fecha_fin)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre_curso, descripcion, id_tipo_curso, fecha_inicio, fecha_fin) VALUES (:nombre_curso, :descripcion, :id_tipo_curso, :fecha_inicio, :fecha_fin)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_curso', $nombre_curso);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_tipo_curso', $id_tipo_curso);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Curso registrado exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "Hubo un error al registrar el curso."];
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al registrar el curso: " . $e->getMessage()];
        }
    }

    public function obtener($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el curso: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTiposCurso()
    {
        try {
            $query = "SELECT * FROM tipo_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return ["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            error_log("Error al obtener los tipos de curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener los tipos de curso."];
        }
    }

    public function eliminar($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_curso = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar el curso: " . $e->getMessage());
            return false;
        }
    }
}
