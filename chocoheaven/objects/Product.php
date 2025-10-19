<?php
// Check if class is already declared
if (!class_exists('Product')) {
    class Product {
        private $conn;
        private $table_name = "products";

        public $id;
        public $name;
        public $description;
        public $price;
        public $image_url;
        public $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Read all products
        function read() {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Read a single product
        function readOne() {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row) {
                $this->name = $row['name'];
                $this->price = $row['price'];
                $this->description = $row['description'];
                $this->image_url = $row['image_url'];
                $this->category = $row['category'];
                return true;
            }
            return false;
        }

        // Get products by category
        function readByCategory($category) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE category = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $category);
            $stmt->execute();
            return $stmt;
        }
    }
}
?>