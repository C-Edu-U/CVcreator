<?php
class Section {
    private $conn;
    private $table = "sections";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByCv($id_cv) {
        $sql = "SELECT * FROM {$this->table} WHERE id_cv = :id_cv ORDER BY position_order ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_cv", $id_cv);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_section) {
        $sql = "SELECT * FROM {$this->table} WHERE id_section = :id_section LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_section", $id_section);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($type, $content, $order, $id_cv) {
        $sql = "INSERT INTO {$this->table} (type, content, position_order, id_cv)
                VALUES (:type, :content, :position_order, :id_cv)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":position_order", $order);
        $stmt->bindParam(":id_cv", $id_cv);
        return $stmt->execute();
    }

    public function update($id_section, $type, $content, $order) {
        $sql = "UPDATE {$this->table} SET type = :type, content = :content, position_order = :position_order
                WHERE id_section = :id_section";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":position_order", $order);
        $stmt->bindParam(":id_section", $id_section);
        return $stmt->execute();
    }

    public function delete($id_section) {
        $sql = "DELETE FROM {$this->table} WHERE id_section = :id_section";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_section", $id_section);
        return $stmt->execute();
    }

    public function getByCvId($id_cv) {
        $sql = "SELECT * FROM sections WHERE id_cv = :id_cv ORDER BY position_order ASC, id_section ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_cv", $id_cv);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
