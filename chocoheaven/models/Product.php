<?php
require_once 'Model.php';

class Product extends Model {
    private $id;
    private $name;
    private $description;
    private $price;
    private $image_url;
    private $category;
    private $created_at;

    public function __construct($db) {
        parent::__construct($db);
        $this->table = "products";
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getImageUrl() { return $this->image_url; }
    public function getCategory() { return $this->category; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrice($price) { $this->price = $price; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    public function setCategory($category) { $this->category = $category; }

    // CRUD Operations
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (name, description, price, image_url, category) 
                  VALUES (:name, :description, :price, :image_url, :category)";
        
        $params = [
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_url' => $data['image_url'],
            ':category' => $data['category']
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
                  SET name = :name, description = :description, price = :price, 
                      image_url = :image_url, category = :category 
                  WHERE id = :id";
        
        $params = [
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_url' => $data['image_url'],
            ':category' => $data['category'],
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
    public function readByCategory($category) {
        $query = "SELECT * FROM " . $this->table . " WHERE category = :category";
        $stmt = $this->executeQuery($query, [':category' => $category]);
        return $stmt;
    }

    public function searchProducts($searchTerm) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE name LIKE :search OR description LIKE :search 
                  ORDER BY name";
        $stmt = $this->executeQuery($query, [':search' => '%' . $searchTerm . '%']);
        return $stmt;
    }
}
?>