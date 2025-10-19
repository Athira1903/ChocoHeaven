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
    public function setUsername($username) { 
        $this->username = htmlspecialchars(strip_tags($username)); 
    }
    
    public function setEmail($email) { 
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Invalid email format");
        }
    }
    
    public function setPassword($password) { 
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT); 
    }

    // CRUD Operations
    public function create($data) {
        // Validate required fields
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new Exception("All fields are required");
        }

        // Check if username or email already exists
        if ($this->usernameExists($data['username'])) {
            throw new Exception("Username already exists");
        }
        
        if ($this->emailExists($data['email'])) {
            throw new Exception("Email already registered");
        }

        $query = "INSERT INTO " . $this->table . " (username, email, password) 
                  VALUES (:username, :email, :password)";
        
        $params = [
            ':username' => htmlspecialchars(strip_tags($data['username'])),
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
            ':username' => htmlspecialchars(strip_tags($data['username'])),
            ':email' => $data['email'],
            ':id' => $id
        ];

        $stmt = $this->executeQuery($query, $params);
        return $stmt->rowCount();
    }

    public function updatePassword($id, $currentPassword, $newPassword) {
        // Verify current password
        $user = $this->getUserWithPassword($id);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception("Current password is incorrect");
        }

        // Update to new password
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $params = [
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
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
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username OR email = :username";
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

    // Additional User Management Methods
    public function getUserWithPassword($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $query = "SELECT id, username, email, created_at FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->executeQuery($query, [':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserStats($user_id) {
        $stats = [
            'total_orders' => 0,
            'total_spent' => 0,
            'pending_orders' => 0
        ];

        // Get total orders count
        $query = "SELECT COUNT(*) as total_orders FROM orders WHERE user_id = :user_id";
        $stmt = $this->executeQuery($query, [':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_orders'] = $result['total_orders'] ?? 0;

        // Get total amount spent
        $query = "SELECT COALESCE(SUM(total_amount), 0) as total_spent FROM orders 
                  WHERE user_id = :user_id AND status = 'completed'";
        $stmt = $this->executeQuery($query, [':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_spent'] = $result['total_spent'] ?? 0;

        // Get pending orders count
        $query = "SELECT COUNT(*) as pending_orders FROM orders 
                  WHERE user_id = :user_id AND status = 'pending'";
        $stmt = $this->executeQuery($query, [':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['pending_orders'] = $result['pending_orders'] ?? 0;

        return $stats;
    }

    public function validatePasswordStrength($password) {
        $errors = [];

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        return $errors;
    }

    public function searchUsers($searchTerm) {
        $query = "SELECT id, username, email, created_at FROM " . $this->table . " 
                  WHERE username LIKE :search OR email LIKE :search 
                  ORDER BY username";
        $stmt = $this->executeQuery($query, [':search' => '%' . $searchTerm . '%']);
        return $stmt;
    }

    public function getRecentUsers($limit = 10) {
        $query = "SELECT id, username, email, created_at FROM " . $this->table . " 
                  ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->executeQuery($query, [':limit' => $limit]);
        return $stmt;
    }

    public function updateLastLogin($user_id) {
        // If you have a last_login column in your users table
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
        $stmt = $this->executeQuery($query, [':id' => $user_id]);
        return $stmt->rowCount();
    }

    // Password reset functionality (basic implementation)
    public function generatePasswordResetToken($email) {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // In a real application, you'd store this in a password_resets table
        // For now, we'll just return the token
        return [
            'token' => $token,
            'expires' => $expires,
            'user_id' => $user['id']
        ];
    }

    // Account verification (if you add email verification)
    public function generateVerificationToken($user_id) {
        $token = bin2hex(random_bytes(32));
        // Store token in database (you'd need a verification_tokens table)
        return $token;
    }

    public function verifyAccount($token) {
        // Verify token and update user account status
        // Implementation depends on your verification system
        return true;
    }
}
?>