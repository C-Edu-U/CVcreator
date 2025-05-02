<?php
class User {
    private $conn;
    private $table = "users";

    public $id_user;
    public $name;
    public $email;
    public $password;
    public $id_role;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $sql = "INSERT INTO {$this->table} (name, email, password, id_role) VALUES (:name, :email, :password, :id_role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id_role", $this->id_role);
        return $stmt->execute();
    }

    public function getAllWithRoles() {
        $sql = "SELECT u.*, r.role_name 
                FROM users u 
                JOIN roles r ON u.id_role = r.id_role 
                ORDER BY u.id_user ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_user) {
        $sql = "SELECT * FROM users WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update() {
        if (!empty($this->password)) {
            $sql = "UPDATE users SET name = :name, email = :email, password = :password, id_role = :id_role, status = :status WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":password", $this->password);
        } else {
            $sql = "UPDATE users SET name = :name, email = :email, id_role = :id_role, status = :status WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($sql);
        }
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id_role", $this->id_role);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_user", $this->id_user);
    
        return $stmt->execute();
    }

    public function delete($id_user) {
        $sql = "DELETE FROM users WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_user", $id_user);
        return $stmt->execute();
    }

    public function updateSelf() {
        if (!empty($this->password)) {
            $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":password", $this->password);
        } else {
            $sql = "UPDATE users SET name = :name, email = :email WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($sql);
        }
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id_user", $this->id_user);
    
        return $stmt->execute();
    }
    
    
}
