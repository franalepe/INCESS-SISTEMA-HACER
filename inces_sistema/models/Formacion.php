<?php

require_once 'Database.php';

class Formacion
{
    private $conn;
    private $table_name = "formacion"; // Asegúrate que este es el nombre correcto de la tabla

    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Método para listar todas las formaciones
    public function listar()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            error_log("Error al listar formaciones: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar las formaciones."];
        }
    }

    // Método para registrar una nueva formación
    public function registrar($nombre_curso, $descripcion, $id_tipo_curso, $fecha_inicio, $fecha_fin)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (nombre_formacion, descripcion, id_tipo_formacion, fecha_inicio, fecha_fin)
                      VALUES (:nombre_formacion, :descripcion, :id_tipo_formacion, :fecha_inicio, :fecha_fin)";
            $stmt = $this->conn->prepare($query);
            // Asignamos los parámetros (mapeando la variable $nombre_curso al campo nombre_formacion)
            $stmt->bindParam(':nombre_formacion', $nombre_curso);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_tipo_formacion', $id_tipo_curso);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            
            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Formación registrada exitosamente."];
            } else {
                // Loggeo del error SQL para depurar
                $errorInfo = $stmt->errorInfo();
                error_log("Error SQL al registrar la formación: " . implode(" | ", $errorInfo));
                return ["success" => false, "mensaje" => "Error al registrar la formación."];
            }
        } catch (PDOException $e) {
            error_log("Excepción al registrar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al registrar la formación."];
        }
    }

    // Método para obtener una formación por su ID (actualizado)
    public function obtenerPorId($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_formacion = :id_formacion LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_formacion', $id, PDO::PARAM_INT);
            $stmt->execute();
            $formacion = $stmt->fetch(PDO::FETCH_ASSOC);
            return $formacion ? $formacion : null;
        } catch (PDOException $e) {
            error_log("Error al obtener la formación: " . $e->getMessage());
            return null;
        }
    }

    // Método para obtener las opciones formativas (tipos de formación)
    public function obtenerTiposCurso()
    {
        try {
            $query = "SELECT id_tipo_formacion AS id_tipo_curso, nombre_tipo_formacion AS nombre_tipo_curso FROM tipo_formacion";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($tipos)) {
                return ["success" => true, "data" => $tipos];
            } else {
                return ["success" => false, "mensaje" => "No se encontraron opciones formativas."];
            }
        } catch (PDOException $e) {
            error_log("Error al obtener los tipos de formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener los tipos de formación."];
        }
    }

    // Método para eliminar una formación por su ID (actualizado)
    public function eliminar($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_formacion = :id_formacion";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_formacion', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Formación eliminada exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "Error al eliminar la formación."];
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al eliminar la formación."];
        }
    }

    // Método para actualizar una formación (actualizado)
    public function actualizar($data)
    {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET nombre_formacion = :nombre_formacion, 
                          descripcion = :descripcion, 
                          id_tipo_formacion = :id_tipo_formacion, 
                          fecha_inicio = :fecha_inicio, 
                          fecha_fin = :fecha_fin 
                      WHERE id_formacion = :id_formacion";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_formacion', $data['nombre_curso']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':id_tipo_formacion', $data['id_tipo_curso']);
            $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
            $stmt->bindParam(':id_formacion', $data['id_curso'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Formación actualizada exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "Error al actualizar la formación."];
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al actualizar la formación."];
        }
    }
}
