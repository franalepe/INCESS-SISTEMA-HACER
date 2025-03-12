<?php
// Database.php
// Clase para gestionar la conexión con la base de datos

class Database
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db_name = 'inces_sistema';
    private $username = 'root';
    private $password = '';

    // Constructor privado para evitar la creación directa de instancias
    private function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    // Método para obtener la instancia única de la clase
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection()
    {
        return $this->conn;
    }

    // Evitar la clonación de la instancia
    private function __clone() {}

    // Evitar la deserialización de la instancia
    public function __wakeup() {}
}
