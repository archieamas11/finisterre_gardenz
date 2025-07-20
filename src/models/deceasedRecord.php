<?php
class deceasedRecord {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllDeceasedData() {
        $sql = "SELECT grave_record.*, grave_points.* 
                    FROM grave_record 
                    LEFT JOIN grave_points ON grave_record.grave_id=grave_points.grave_id 
                    WHERE grave_record.status != 'vacant'";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDeceasedData($id) {
        $sql = "SELECT grave_record.*, grave_points.* 
                    FROM grave_record 
                    LEFT JOIN grave_points ON grave_record.grave_id=grave_points.grave_id 
                    WHERE grave_record.status != 'vacant' AND grave_record.id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>