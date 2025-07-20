<?php
class userModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getUser($data) {
        try {
            $sql = "SELECT * FROM tbl_users WHERE email = :email";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getUserByEmail($data) {
        try {
            $sql = "SELECT email FROM tbl_users WHERE email = :email";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getUserByToken($data) {
        try {
            $sql = "SELECT * FROM tbl_users WHERE token = :token";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    public function insertUser($data) {
        try {
            $sql = "INSERT INTO tbl_users (first_name, last_name, email, password, token, created_at, updated_at) VALUES (:first_name, :last_name, :email, :password, :token, NOW(), NOW())";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    public function updateUser($data) {
        try {
            $sql = "UPDATE tbl_users SET status = :status, token = :token, token_expiry = :token_expiry WHERE token = :token_old";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute($data);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function getDeceasedData($id) {
        try {
            $sql = "SELECT grave_record.*, grave_points.* 
                        FROM grave_record 
                        LEFT JOIN grave_points ON grave_record.grave_id=grave_points.grave_id 
                        WHERE grave_record.status != 'vacant' AND grave_record.id = :id";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}