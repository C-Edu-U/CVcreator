<?php
class Database {
    private $host = 'db'; // Servicio definido en docker-compose
    private $db_name = 'cv_generator';
    private $username = 'root';
    private $password = 'notSecureChangeMe';
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Conexión fallida: " . $e->getMessage());
        }

        return $this->conn;
    }
}
