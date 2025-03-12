<?php
// Usuario.php
// Clase para gestionar los usuarios

require_once 'Database.php';

class Usuario
{
    private $conn;
    private $table_name = "usuario";

    public $id_usuario;
    public $nombre_usuario;
    public $contrasena;
    public $tipo_usuario;
    public $correo_electronico;

    public function __construct()
    {
        // Obtener la instancia de la conexión a la base de datos
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Listar todos los usuarios.
     * @return array|false Lista de usuarios o false en caso de error.
     */
    public function listar()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al listar los usuarios: " . $e->getMessage());
            return false;
        }
    }

    // Validar nombre de usuario
    private function validarNombreUsuario($nombre_usuario)
    {
        if (preg_match('/[^a-zA-Z0-9_]/', $nombre_usuario)) {
            return false; // Nombre de usuario contiene caracteres inválidos
        }
        return true;
    }

    /**
     * Registrar un nuevo usuario.
     * @param string $nombre_usuario
     * @param string $contrasena
     * @param string $tipo_usuario
     * @param string $correo_electronico
     * @return bool True en caso de éxito, false en caso de error.
     */
    public function registrar($nombre_usuario, $contrasena, $tipo_usuario, $correo_electronico)
    {
        // Validar entrada
        if (empty($nombre_usuario) || empty($contrasena) || empty($tipo_usuario) || empty($correo_electronico)) {
            return ["success" => false, "mensaje" => "Todos los campos son obligatorios"];
        }

        if (!$this->validarNombreUsuario($nombre_usuario)) {
            return ["success" => false, "mensaje" => "El nombre de usuario solo puede contener letras, números y guiones bajos"];
        }

        try {
            // Comprobar si el nombre de usuario ya existe
            $query = "SELECT * FROM " . $this->table_name . " WHERE nombre_usuario = :nombre_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ["success" => false, "mensaje" => "El nombre de usuario ya está en uso"];
            }

            $query = "INSERT INTO " . $this->table_name . " (nombre_usuario, contrasena, tipo_usuario, correo_electronico) 
                      VALUES (:nombre_usuario, :contrasena, :tipo_usuario, :correo_electronico)";
            $stmt = $this->conn->prepare($query);

            // Hash de la contraseña
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':contrasena', $contrasenaHash);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
            $stmt->bindParam(':correo_electronico', $correo_electronico);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Usuario registrado exitosamente"];
            } else {
                return ["success" => false, "mensaje" => "Error al registrar el usuario"];
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el usuario: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error de servidor. Por favor, inténtelo más tarde."];
        }
    }

    // Obtener usuario por nombre de usuario
    public function obtenerPorNombreUsuario($nombre_usuario)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE nombre_usuario = :nombre_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por nombre de usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un usuario por su ID.
     * @param int $id
     * @return array|false Detalles del usuario o false en caso de error.
     */
    public function obtener($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error al obtener el usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Editar un usuario.
     * @param int $id
     * @param string $nombre_usuario
     * @param string $contrasena
     * @param string $tipo_usuario
     * @param string $correo_electronico
     * @return bool True en caso de éxito, false en caso de error.
     */
    public function editar($id, $nombre_usuario, $contrasena, $tipo_usuario, $correo_electronico)
    {
        // Validar entrada
        if (empty($nombre_usuario) || empty($contrasena) || empty($tipo_usuario) || empty($correo_electronico)) {
            return ["success" => false, "mensaje" => "Todos los campos son obligatorios"];
        }

        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET nombre_usuario = :nombre_usuario, contrasena = :contrasena, tipo_usuario = :tipo_usuario, correo_electronico = :correo_electronico 
                      WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':contrasena', $contrasenaHash);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
            $stmt->bindParam(':correo_electronico', $correo_electronico);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Usuario actualizado exitosamente"];
            } else {
                return ["success" => false, "mensaje" => "Error al actualizar el usuario"];
            }
        } catch (PDOException $e) {
            error_log("Error al editar el usuario: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error de servidor. Por favor, inténtelo más tarde."];
        }
    }

    /**
     * Eliminar un usuario.
     * @param int $id
     * @return bool True en caso de éxito, false en caso de error.
     */
    public function eliminar($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar el usuario: " . $e->getMessage());
            return false;
        }
    }
}
