<?php
abstract class Model {
    protected $db;
    protected $table;

    public function __construct($db) {
        $this->db = $db;
    }

    protected function executeQuery($query, $params = []) {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    abstract public function create($data);
    abstract public function read($id = null);
    abstract public function update($id, $data);
    abstract public function delete($id);
}
?>