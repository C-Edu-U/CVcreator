<?php
class Log {
    private $conn;
    private $table = "logs";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert($id_user, $type, $desc) {
        $sql = "INSERT INTO {$this->table} (id_user, action_type, description) VALUES (:id_user, :type, :desc)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":desc", $desc);
        return $stmt->execute();
    }

    public function getAll() {
        $sql = "SELECT l.*, u.name AS user_name FROM logs l
                LEFT JOIN users u ON l.id_user = u.id_user
                ORDER BY l.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
