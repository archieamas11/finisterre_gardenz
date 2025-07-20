<?php
class Model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Function for inserting data into the database
    public function insert($table, $data) {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    // Function for getting the data from the database
    public function get($table, $conditions = []) {
        $sql = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($conditions)));
        }
    
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(array_values($conditions));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Function for updating the data in the database
    public function update($table, $data, $conditions) {
        if (empty($data) || empty($conditions)) {
            return false;
        }

        $setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($conditions)));

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";
        
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
    }

    // Function for deleting the data from the database
    public function delete($table, $conditions) {
        if (empty($conditions)) {
            return false;
        }

        $whereClause = implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $whereClause";

        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute(array_values($conditions));
    }
}
?>