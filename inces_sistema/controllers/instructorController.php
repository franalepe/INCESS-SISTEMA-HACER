<?php
// instructorController.php
// Controlador para gestionar las acciones de los instructores

require_once __DIR__ . '/../models/Instructor.php';

class InstructorController
{
    // Método para registrar un nuevo instructor
    public function registrar($data)
    {
        try {
            $instructorModel = new Instructor();
            $result = $instructorModel->registrar($data['nombre'], $data['apellido'], $data['correo'], $data['telefono']);
            return $result;
        } catch (Exception $e) {
            error_log("Error al registrar el instructor: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al registrar el instructor."];
        }
    }

    // Método para listar todos los instructores
    public function listar()
    {
        try {
            $instructorModel = new Instructor();
            $result = $instructorModel->listar();
            return $result;
        } catch (Exception $e) {
            error_log("Error al listar los instructores: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al listar los instructores."];
        }
    }

    // Método para obtener un instructor por ID
    public function obtener($id)
    {
        try {
            $instructorModel = new Instructor();
            return $instructorModel->obtener($id);
        } catch (Exception $e) {
            error_log("Error al obtener el instructor: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener el instructor."];
        }
    }

    // Método para editar un instructor
    public function editar($id, $data)
    {
        try {
            $instructorModel = new Instructor();
            return $instructorModel->editar($id, $data);
        } catch (Exception $e) {
            error_log("Error al editar el instructor: " . $e->getMessage());
            return false;
        }
    }

    // Método para eliminar un instructor
    public function eliminar($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return ["success" => false, "mensaje" => "ID inválido."];
        }

        try {
            $instructorModel = new Instructor();
            if ($instructorModel->eliminar($id)) {
                return ["success" => true, "mensaje" => "Instructor eliminado exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "No se pudo eliminar el instructor."];
            }
        } catch (Exception $e) {
            error_log("Error al eliminar el instructor: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al eliminar el instructor."];
        }
    }
}
?>
