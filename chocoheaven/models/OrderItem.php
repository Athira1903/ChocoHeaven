<?php
require_once 'Model.php';

class OrderItem extends Model {
    private $id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    public function __construct($db) {
        parent::__construct($db);
        $this->table = "order_items";
    }

    // Getters
    public function getId() { return $this->id; }
    public function getOrderId() { return $this->order_id; }
    public function getProductId() { return $this->product_id; }
    public function getQuantity() { return $this->quantity; }
    public function getPrice() { return $this->price; }

    // Setters
    public function setOrderId($order_id) { $this->order_id = $order_id; }
    public function setProductId($product_id) { $this->product_id = $product_id; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setPrice($price) { $this->price = $price; }

    // CRUD Operations
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (order_id, product_id, quantity, price) 
                  VALUES (:order_id, :product_id, :quantity, :price)";
        
        $params = [
            ':order_id' => $data['order_id'],
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
            ':price' => $data['price']
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
            $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
            $stmt = $this->executeQuery($query);
            return $stmt;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET order_id = :order_id, product_id = :product_id, 
                      quantity = :quantity, price = :price 
                  WHERE id = :id";
        
        $params = [
            ':order_id' => $data['order_id'],
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
            ':price' => $data['price'],
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

    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.name, p.image_url 
                  FROM " . $this->table . " oi 
                  JOIN products p ON oi.product_id = p.id 
                  WHERE oi.order_id = :order_id";
        $stmt = $this->executeQuery($query, [':order_id' => $order_id]);
        return $stmt;
    }
}
?>