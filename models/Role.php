<?php
class Role {
    private $conn;
    private $table = "roles";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_role";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($name, $desc) {
        $sql = "INSERT INTO roles (role_name, description) VALUES (:name, :desc)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":desc", $desc);
        return $stmt->execute();
    }
    public function getById($id) {
        $sql = "SELECT * FROM roles WHERE id_role = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $name, $desc) {
        $sql = "UPDATE roles SET role_name = :name, description = :desc WHERE id_role = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":desc", $desc);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    public function canDelete($id) {
        $sql = "SELECT COUNT(*) FROM users WHERE id_role = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn() == 0;
    }
    
    public function delete($id) {
        $sql = "DELETE FROM roles WHERE id_role = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    
}
