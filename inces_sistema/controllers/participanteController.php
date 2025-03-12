<?php
// participanteController.php
// Controlador para gestionar las acciones de los participantes

require_once __DIR__ . '/../models/Participante.php';

class ParticipanteController
{
    private $participanteModel;

    public function __construct()
    {
        $this->participanteModel = new Participante();
    }

    /**
     * Lista participantes.
     *
     * @return array Retorna un array con el resultado de la operación.
     */
    public function listar($busqueda = null)
    {
        try {
            $result = $this->participanteModel->listar($busqueda);

            if ($result['success']) {
                return $result;
            } else {
                return ["success" => false, "mensaje" => "Error al listar participantes: " . $result['mensaje']];
            }
        } catch (Exception $e) {
            error_log("Error al listar participantes en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar participantes."];
        }
    }

    /**
     * Registra un nuevo participante.
     *
     * @param array $data Datos del participante a registrar.
     * @return array Retorna un array con el resultado de la operación.
     */
    public function registrar($data)
    {
        // Validaciones básicas (puedes agregar más validaciones aquí)
        if (empty($data['tipo_identificacion'])) {
            return ["success" => false, "mensaje" => "El tipo de identificación es requerido."];
        }

        if (empty($data['identificacion'])) {
            return ["success" => false, "mensaje" => "La identificación es requerida."];
        }

        if (empty($data['nombres'])) {
            return ["success" => false, "mensaje" => "Los nombres son requeridos."];
        }

        if (empty($data['apellidos'])) {
            return ["success" => false, "mensaje" => "Los apellidos son requeridos."];
        }

        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "mensaje" => "El correo electrónico no es válido."];
        }

        try {
            $result = $this->participanteModel->registrar($data);

            if ($result['success']) {
                return ["success" => true, "mensaje" => "Participante registrado exitosamente."];
            } else {
                // Ahora se retorna el mensaje de error real desde el modelo
                return ["success" => false, "mensaje" => $result['mensaje']];
            }
        } catch (Exception $e) {
            error_log("Error al registrar participante en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al registrar el participante: " . $e->getMessage()];
        }
    }

    /**
     * Obtiene un participante por ID.
     *
     * @param int $id ID del participante a obtener.
     * @return array Retorna un array con el resultado de la operación.
     */
    public function obtener($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if ($id === false || $id == null) {
            return ["success" => false, "mensaje" => "ID de participante no válido."];
        }
        try {
            $participante = $this->participanteModel->obtener($id);

            if ($participante) {
                return ["success" => true, "data" => $participante];
            } else {
                return ["success" => false, "mensaje" => "Participante no encontrado."];
            }
        } catch (Exception $e) {
            error_log("Error al obtener participante en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener el participante."];
        }
    }

    /**
     * Edita un participante existente.
     *
     * @param int $id ID del participante a editar.
     * @param array $data Datos del participante a actualizar.
     * @return array Retorna un array con el resultado de la operación.
     */
    public function editar($id, $data)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if ($id === false || $id == null) {
            return ["success" => false, "mensaje" => "ID de participante no válido."];
        }

        // Validaciones básicas (puedes agregar más validaciones aquí)
        if (empty($data['identificacion'])) {
            return ["success" => false, "mensaje" => "La identificación es requerida."];
        }

        if (empty($data['nombres'])) {
            return ["success" => false, "mensaje" => "Los nombres son requeridos."];
        }

        if (empty($data['apellidos'])) {
            return ["success" => false, "mensaje" => "Los apellidos son requeridos."];
        }

        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "mensaje" => "El correo electrónico no es válido."];
        }

        try {
            $result = $this->participanteModel->editar($id, $data);

            if ($result) {
                return ["success" => true, "mensaje" => "Participante actualizado exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "No se pudo actualizar el participante."];
            }
        } catch (Exception $e) {
            error_log("Error al editar participante en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al editar el participante."];
        }
    }

    /**
     * Elimina un participante.
     *
     * @param int $id ID del participante a eliminar.
     * @return array Retorna un array con el resultado de la operación.
     */
    /**
     * Busca participantes por cédula, nombre o apellido.
     *
     * @param string $busqueda Término de búsqueda
     * @return array Retorna un array con el resultado de la operación.
     */
    public function buscar($busqueda)
    {
        try {
            $result = $this->participanteModel->buscar($busqueda);
            if ($result['success']) {
                return $result;
            } else {
                return ["success" => false, "mensaje" => "Error al buscar participantes: " . $result['mensaje']];
            }
        } catch (Exception $e) {
            error_log("Error al buscar participantes en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al buscar participantes."];
        }
    }

    public function eliminar($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if ($id === false || $id == null) {

            return ["success" => false, "mensaje" => "ID de participante no válido."];
        }
        try {
            $result = $this->participanteModel->eliminar($id);

            if ($result) {
                return ["success" => true, "mensaje" => "Participante eliminado exitosamente."];
            } else {
                return ["success" => false, "mensaje" => "No se pudo eliminar el participante."];
            }
        } catch (Exception $e) {
            error_log("Error al eliminar participante en ParticipanteController: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al eliminar el participante."];
        }
    }
}
