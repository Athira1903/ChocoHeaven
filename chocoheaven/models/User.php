<?php
require_once 'Model.php';

class User extends Model {
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;

    public function __construct($db) {
        parent::__construct($db);
        $this->table = "users";
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }

    // CRUD Operations
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (username, email, password) 
                  VALUES (:username, :email, :password)";
        
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        $stmt = $this->executeQuery($query, $params);
        return $this->db->lastInsertId();
    }

    public function read($id = null) {
        if ($id) {
            $query = "SELECT id, username, email, created_at FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->executeQuery($query, [':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT id, username, email, created_at FROM " . $this->table . " ORDER BY created_at DESC";
            $stmt = $this->executeQuery($query);
            return $stmt;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, email = :email 
                  WHERE id = :id";
        
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':id' => $id
        ];

        $stmt = $this->executeQuery($query, $params);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        return $stmt->rowCount();
    }

    // Authentication methods
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->executeQuery($query, [':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Remove password from returned data
            return $user;
        }
        return false;
    }

    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->executeQuery($query, [':username' => $username]);
        return $stmt->rowCount() > 0;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->executeQuery($query, [':email' => $email]);
        return $stmt->rowCount() > 0;
    }
}
?>