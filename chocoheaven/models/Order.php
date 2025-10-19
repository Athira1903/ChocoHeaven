<?php
require_once 'Model.php';

class Order extends Model {
    private $id;
    private $user_id;
    private $total_amount;
    private $status;
    private $created_at;

    public function __construct($db) {
        parent::__construct($db);
        $this->table = "orders";
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getTotalAmount() { return $this->total_amount; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setTotalAmount($total_amount) { $this->total_amount = $total_amount; }
    public function setStatus($status) { $this->status = $status; }

    // CRUD Operations
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (user_id, total_amount, status) 
                  VALUES (:user_id, :total_amount, :status)";
        
        $params = [
            ':user_id' => $data['user_id'],
            ':total_amount' => $data['total_amount'],
            ':status' => $data['status'] ?? 'pending'
        ];

        $stmt = $this->executeQuery($query, $params);
        return $this->db->lastInsertId();
    }

    public function read($id = null) {
        if ($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->executeQuery($query, [':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
            $stmt = $this->executeQuery($query);
            return $stmt;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET user_id = :user_id, total_amount = :total_amount, status = :status 
                  WHERE id = :id";
        
        $params = [
            ':user_id' => $data['user_id'],
            ':total_amount' => $data['total_amount'],
            ':status' => $data['status'],
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

    // Additional methods
    public function getOrdersByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->executeQuery($query, [':user_id' => $user_id]);
        return $stmt;
    }

    public function createOrderWithItems($user_id, $cartItems, $total_amount) {
        try {
            $this->db->beginTransaction();

            // Create order
            $order_id = $this->create([
                'user_id' => $user_id,
                'total_amount' => $total_amount,
                'status' => 'completed'
            ]);

            // Create order items
            $orderItem = new OrderItem($this->db);
            foreach ($cartItems as $item) {
                $orderItem->create([
                    'order_id' => $order_id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $this->db->commit();
            return $order_id;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Order creation failed: " . $e->getMessage());
        }
    }
}
?>