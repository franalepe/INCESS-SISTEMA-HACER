<?php
// formacionController.php
// Controlador para gestionar las acciones relacionadas con las formaciones

require_once __DIR__ . '/../models/Formacion.php';

class FormacionController
{
    // Aperturar (registrar) una formación, alternativa a registrar, si se requiere lógica diferenciada
    public function aperturarFormacion($data)
    {
        if (!isset($data['nombre_curso'], $data['descripcion'], $data['id_tipo_curso'], $data['fecha_inicio'], $data['fecha_fin'])) {
            return ["success" => false, "mensaje" => "Datos incompletos en aperturarFormacion."];
        }
        try {
            $formacionModel = new Formacion();
            $result = $formacionModel->registrar(
                $data['nombre_curso'],
                $data['descripcion'],
                $data['id_tipo_curso'],
                $data['fecha_inicio'],
                $data['fecha_fin']
            );
            return $result;
        } catch (Exception $e) {
            error_log("Error al aperturar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al aperturar la formación."];
        }
    }

    // Listar todas las formaciones
    public function listar()
    {
        try {
            $formacionModel = new Formacion();
            $result = $formacionModel->listar();
            return $result;
        } catch (Exception $e) {
            error_log("Error al listar las formaciones: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al listar las formaciones."];
        }
    }

    // Registrar una nueva formación
    public function registrar($data)
    {
        $formacionModel = new Formacion();
        return $formacionModel->registrar(
            $data['nombre_curso'],
            $data['descripcion'],
            $data['id_tipo_curso'],
            $data['fecha_inicio'],
            $data['fecha_fin']
        );
    }

    // Obtener una formación por su ID
    public function obtenerPorId($id)
    {
        if (!is_numeric($id)) {
            return ["success" => false, "mensaje" => "El ID debe ser numérico."];
        }
        try {
            $formacionModel = new Formacion();
            $formacion = $formacionModel->obtenerPorId($id);
            if ($formacion) {
                return ["success" => true, "mensaje" => "Formación obtenida exitosamente.", "data" => $formacion];
            } else {
                return ["success" => false, "mensaje" => "Formación no encontrada."];
            }
        } catch (Exception $e) {
            error_log("Error al obtener la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener la formación: " . $e->getMessage()];
        }
    }

    // Obtener las opciones formativas (tipos de formación)
    public function obtenerTiposFormacion()
    {
        try {
            $formacionModel = new Formacion();
            $result = $formacionModel->obtenerTiposCurso();
            return $result;
        } catch (Exception $e) {
            error_log("Error al obtener los tipos de formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener los tipos de formación."];
        }
    }

    // Agregamos el método para obtener las opciones formativas
    public function obtenerTiposCurso()
    {
        $formacionModel = new Formacion();
        return $formacionModel->obtenerTiposCurso();
    }

    // Actualizar una formación existente
    public function actualizar($data)
    {
        if (
            !isset($data['id_curso'], $data['nombre_curso'], $data['descripcion'], 
                   $data['id_tipo_curso'], $data['fecha_inicio'], $data['fecha_fin'])
        ) {
            return ["success" => false, "mensaje" => "Datos incompletos en actualizar."];
        }
        try {
            $formacionModel = new Formacion();
            $result = $formacionModel->actualizar($data);
            return $result;
        } catch (Exception $e) {
            error_log("Error al actualizar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al actualizar la formación."];
        }
    }

    // Eliminar una formación por su ID
    public function eliminar($id)
    {
        if (!is_numeric($id)) {
            return ["success" => false, "mensaje" => "El ID debe ser numérico."];
        }
        try {
            $formacionModel = new Formacion();
            $result = $formacionModel->eliminar($id);
            return $result;
        } catch (Exception $e) {
            error_log("Error al eliminar la formación: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Hubo un error al eliminar la formación."];
        }
    }
}
