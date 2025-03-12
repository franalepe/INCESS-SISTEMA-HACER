<?php
// cursoController.php
// Controlador para gestionar las acciones de los cursos

require_once __DIR__ . '/../models/Curso.php';

class CursoController
{
    // Método para aperturar un curso
    public function aperturarCurso($id, $data)
    {
        try {
            $cursoModel = new Curso();
            $result = $cursoModel->registrar($data['nombre_curso'], $data['descripcion'], $data['id_tipo_curso'], $data['fecha_inicio'], $data['fecha_fin']);
            return $result;
        } catch (Exception $e) {
            error_log("Error al aperturar el curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al aperturar el curso."];
        }
    }

    // Listar todos los cursos
    public function listar()
    {
        try {
            $cursoModel = new Curso();
            $result = $cursoModel->listar();
            return $result;
        } catch (Exception $e) {
            error_log("Error al listar los cursos: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al listar los cursos."];
        }
    }

    // Registrar un nuevo curso
    public function registrar($data)
    {
        try {
            $cursoModel = new Curso();
            $result = $cursoModel->registrar($data['nombre_curso'], $data['descripcion'], $data['id_tipo_curso'], $data['fecha_inicio'], $data['fecha_fin']);
            return $result;
        } catch (Exception $e) {
            error_log("Error al registrar el curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al registrar el curso."];
        }
    }

    // Obtener un curso por su ID
    public function obtener($id)
    {
        if (!is_numeric($id)) {
            return ["success" => false, "mensaje" => 'El ID del curso debe ser un número.'];
        }

        try {
            $cursoModel = new Curso();
            $curso = $cursoModel->obtener($id);

            if ($curso) {
                return ["success" => true, "mensaje" => 'Curso obtenido exitosamente.', "data" => $curso];
            } else {
                return ["success" => false, "mensaje" => 'Curso no encontrado.'];
            }
        } catch (Exception $e) {
            return ["success" => false, "mensaje" => "Error al obtener el curso: " . $e->getMessage()];
        }
    }

    // Obtener los tipos de curso
    public function obtenerTiposCurso()
    {
        try {
            $cursoModel = new Curso();
            $result = $cursoModel->obtenerTiposCurso();
            return $result;
        } catch (Exception $e) {
            error_log("Error al obtener los tipos de curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al obtener los tipos de curso."];
        }
    }

    // Eliminar curso
    public function eliminar($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return ["success" => false, "mensaje" => "ID inválido."];
        }

        try {
            $cursoModel = new Curso();
            if ($cursoModel->eliminar($id)) {
                return ["success" => true, "mensaje" => "Curso eliminado exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "No se pudo eliminar el curso."];
            }
        } catch (Exception $e) {
            error_log("Error al eliminar el curso: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al eliminar el curso."];
        }
    }
}
