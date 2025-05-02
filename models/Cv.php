<?php
class Cv {
    private $conn;
    private $table = "cvs";

    public $id_cv;
    public $title;
    public $id_user;
    public $fecha_creacion;
    public $contacto;
    public $website;
    public $personal_statement;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($title, $id_user, $contacto, $website, $personal_statement) {
        $sql = "INSERT INTO {$this->table} 
                (title, id_user, contacto, website, personal_statement, fecha_creacion) 
                VALUES (:title, :id_user, :contacto, :website, :personal_statement, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":contacto", $contacto);
        $stmt->bindParam(":website", $website);
        $stmt->bindParam(":personal_statement", $personal_statement);
        return $stmt->execute();
    }

    public function update($id_cv, $title, $contacto, $website, $personal_statement) {
        $sql = "UPDATE {$this->table} 
                SET title = :title, contacto = :contacto, website = :website, personal_statement = :personal_statement 
                WHERE id_cv = :id_cv";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":contacto", $contacto);
        $stmt->bindParam(":website", $website);
        $stmt->bindParam(":personal_statement", $personal_statement);
        $stmt->bindParam(":id_cv", $id_cv);
        return $stmt->execute();
    }

    public function getAllByUser($id_user) {
        $sql = "SELECT id_cv, title, fecha_creacion FROM {$this->table} WHERE id_user = :id_user ORDER BY id_cv DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT c.id_cv, c.title, c.fecha_creacion, u.name AS user_name 
                FROM {$this->table} c 
                JOIN users u ON c.id_user = u.id_user 
                ORDER BY c.id_cv DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_cv) {
        $sql = "SELECT * FROM {$this->table} WHERE id_cv = :id_cv";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_cv", $id_cv);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id_cv) {
        $sql = "DELETE FROM {$this->table} WHERE id_cv = :id_cv";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_cv", $id_cv);
        return $stmt->execute();
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
