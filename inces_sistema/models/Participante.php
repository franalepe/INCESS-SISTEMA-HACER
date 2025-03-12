<?php

require_once 'Database.php';

class Participante
{
    private $conn;
    private $table_name = "participante";

    public function __construct()
    {
        // Obtener la instancia de la conexión a la base de datos
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Lista participantes con paginación.
     *
     * @param int $offset Número de inicio para la paginación.
     * @param int $limit Cantidad de registros a retornar.
     * @return array Retorna un array con el resultado de la operación.
     */
    public function listar($busqueda = null, $offset = 0, $limit = 10)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            
            if ($busqueda) {
                $query .= " WHERE identificacion LIKE :busqueda OR nombres LIKE :busqueda OR apellidos LIKE :busqueda";
            }
            
            $query .= " LIMIT :offset, :limit";
            
            $stmt = $this->conn->prepare($query);
            
            if ($busqueda) {
                $busquedaParam = "%" . $busqueda . "%";
                $stmt->bindValue(':busqueda', $busquedaParam);
            }
            
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ["success" => true, "data" => $data];

        } catch (PDOException $e) {
            error_log("Error al listar los participantes: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar los participantes."];
        }
    }

    /**
     * Lista todos los participantes ordenados por ID.
     *
     * @return array Retorna un array con el resultado de la operación.
     */
    public function listarTodos()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_participante ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            error_log("Error al listar participantes: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar participantes."];
        }
    }

    /**
     * Registra un nuevo participante en la base de datos.
     *
     * @param array $data Datos del participante a registrar.
     * @return array Retorna un array con el resultado de la operación.
     */
    public function registrar($data)
    {
        try {
            // Verificar si ya existe la identificación
            $query_check = "SELECT id_participante FROM " . $this->table_name . " WHERE identificacion = :identificacion";
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindValue(':identificacion', $data['identificacion']);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                return ["success" => false, "mensaje" => "Error al registrar el participante: La identificación ya existe."];
            }

            $query = "INSERT INTO " . $this->table_name . " (
                        tipo_identificacion,
                        identificacion,
                        nombres,
                        apellidos,
                        fecha_nacimiento,
                        edad,
                        genero,
                        nacionalidad,
                        correo,
                        telefono,
                        direccion,
                        estado,
                        municipio,
                        parroquia,
                        comuna,
                        ciudad,
                        sector,
                        posee_discapacidad,
                        tipo_discapacidad,
                        grado_instruccion,
                        estudia,
                        tipo_economia,
                        trabaja,
                        entidad_trabajo
                      ) VALUES (
                        :tipo_identificacion,
                        :identificacion,
                        :nombres,
                        :apellidos,
                        :fecha_nacimiento,
                        :edad,
                        :genero,
                        :nacionalidad,
                        :correo,
                        :telefono,
                        :direccion,
                        :estado,
                        :municipio,
                        :parroquia,
                        :comuna,
                        :ciudad,
                        :sector,
                        :posee_discapacidad,
                        :tipo_discapacidad,
                        :grado_instruccion,
                        :estudia,
                        :tipo_economia,
                        :trabaja,
                        :entidad_trabajo
                      )";
            $stmt = $this->conn->prepare($query);

            // Mapear 'V' o 'P' a los valores reales para 'tipo_identificacion'
            $tipoIdentificacion = ($data['tipo_identificacion'] === 'V') ? 'Cédula' : 'Pasaporte';
            $stmt->bindValue(':tipo_identificacion', $tipoIdentificacion);
            $stmt->bindValue(':identificacion', $data['identificacion']);
            $stmt->bindValue(':nombres', $data['nombres']);
            $stmt->bindValue(':apellidos', $data['apellidos']);
            $stmt->bindValue(':fecha_nacimiento', $data['fecha_nacimiento'] ?? null);
            $stmt->bindValue(':edad', $data['edad'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':genero', $data['genero'] ?? null);
            $stmt->bindValue(':nacionalidad', $data['nacionalidad'] ?? null);
            $stmt->bindValue(':correo', $data['correo']);
            $stmt->bindValue(':telefono', $data['telefono']);
            $stmt->bindValue(':direccion', $data['direccion']);
            $stmt->bindValue(':estado', $data['estado']);
            $stmt->bindValue(':municipio', $data['municipio']);
            $stmt->bindValue(':parroquia', $data['parroquia']);
            $stmt->bindValue(':comuna', $data['comuna']);
            $stmt->bindValue(':ciudad', $data['ciudad'] ?? null);
            $stmt->bindValue(':sector', $data['sector'] ?? null);
            $stmt->bindValue(':posee_discapacidad', isset($data['posee_discapacidad']) ? $data['posee_discapacidad'] : 0, PDO::PARAM_BOOL);
            $stmt->bindValue(':tipo_discapacidad', $data['tipo_discapacidad'] ?? null);
            $stmt->bindValue(':grado_instruccion', $data['grado_instruccion'] ?? null);
            $stmt->bindValue(':estudia', isset($data['estudia']) ? $data['estudia'] : 0, PDO::PARAM_BOOL);
            $stmt->bindValue(':tipo_economia', $data['tipo_economia'] ?? null);
            $stmt->bindValue(':trabaja', isset($data['trabaja']) ? $data['trabaja'] : 0, PDO::PARAM_BOOL);
            $stmt->bindValue(':entidad_trabajo', $data['entidad_trabajo'] ?? null);
            
            $stmt->execute();
            return ["success" => true, "mensaje" => "Participante registrado exitosamente."];
        } catch (PDOException $e) {
            error_log("Error al registrar el participante: " . $e->getMessage());
            // Devuelve el mensaje real para depurar (recuerda quitarlo en producción)
            return ["success" => false, "mensaje" => "Error al registrar el participante: " . $e->getMessage()];
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al registrar el participante."];
        }
    }

    /**
     * Obtiene un participante por su ID.
     *
     * @param int $id ID del participante a buscar.
     * @return mixed Retorna un array asociativo con los datos del participante o false si no se encuentra.
     */
    public function obtener($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_participante = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ?: false; // Retorna false si no encuentra nada
        } catch (PDOException $e) {
            error_log("Error al obtener el participante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Edita la información de un participante existente.
     *
     * @param int $id ID del participante a editar.
     * @param array $data Datos actualizados del participante.
     * @return bool Retorna true si la edición fue exitosa, false en caso contrario.
     */
    public function editar($id, $data)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET
               identificacion    = :identificacion,
               nombres           = :nombres,
               apellidos         = :apellidos,
               correo            = :correo,
               telefono          = :telefono,
               direccion         = :direccion,
               estado            = :estado,
               municipio         = :municipio,
               parroquia         = :parroquia,
               comuna            = :comuna,
               fecha_nacimiento  = :fecha_nacimiento,
               edad              = :edad,
               genero            = :genero,
               nacionalidad      = :nacionalidad,
               posee_discapacidad= :posee_discapacidad,
               tipo_discapacidad = :tipo_discapacidad,
               grado_instruccion = :grado_instruccion,
               estudia           = :estudia,
               detalle_estudia   = :detalle_estudia,
               tipo_economia     = :tipo_economia,
               trabaja           = :trabaja,
               entidad_trabajo   = :entidad_trabajo
               WHERE id_participante = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':identificacion', $data['identificacion']);
            $stmt->bindValue(':nombres', $data['nombres']);
            $stmt->bindValue(':apellidos', $data['apellidos']);
            $stmt->bindValue(':correo', $data['correo']);
            $stmt->bindValue(':telefono', $data['telefono']);
            $stmt->bindValue(':direccion', $data['direccion']);
            $stmt->bindValue(':estado', $data['estado']);
            $stmt->bindValue(':municipio', $data['municipio']);
            $stmt->bindValue(':parroquia', $data['parroquia']);
            $stmt->bindValue(':comuna', $data['comuna']);
            $stmt->bindValue(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindValue(':edad', $data['edad']);
            $stmt->bindValue(':genero', $data['genero']);
            $stmt->bindValue(':nacionalidad', $data['nacionalidad']);
            $stmt->bindValue(':posee_discapacidad', $data['posee_discapacidad'], PDO::PARAM_BOOL);
            $stmt->bindValue(':tipo_discapacidad', $data['tipo_discapacidad']);
            $stmt->bindValue(':grado_instruccion', $data['grado_instruccion']);
            $stmt->bindValue(':estudia', $data['estudia'], PDO::PARAM_BOOL);
            $stmt->bindValue(':detalle_estudia', $data['detalle_estudia']);
            $stmt->bindValue(':tipo_economia', $data['tipo_economia']);
            $stmt->bindValue(':trabaja', $data['trabaja'], PDO::PARAM_BOOL);
            $stmt->bindValue(':entidad_trabajo', $data['entidad_trabajo']);
            
            if($stmt->execute()){
                return ["success" => true];
            } else {
                return ["success" => false, "mensaje" => "La ejecución de la consulta falló."];
            }
        } catch (PDOException $e) {
            error_log("Error al editar el participante: " . $e->getMessage());
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Elimina un participante de la base de datos.
     *
     * @param int $id ID del participante a eliminar.
     * @return bool Retorna true si la eliminación fue exitosa, false en caso contrario.
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
            $query = "SELECT * FROM " . $this->table_name . " 
                     WHERE identificacion LIKE :busqueda 
                     OR nombres LIKE :busqueda 
                     OR apellidos LIKE :busqueda";
            
            $stmt = $this->conn->prepare($query);
            $busquedaParam = "%" . $busqueda . "%";
            $stmt->bindValue(':busqueda', $busquedaParam);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ["success" => true, "data" => $data];
        } catch (PDOException $e) {
            error_log("Error al buscar participantes: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al buscar participantes."];
        }
    }

    public function eliminar($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_participante = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Error al eliminar el participante: " . $e->getMessage());
            return false;
        }
    }
}
