<?php
// fetch_grave.php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cemeterease';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// SQL Query
$grave_id = 1;
$sql = "SELECT record_name, record_birth, record_death FROM grave_record WHERE grave_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $grave_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
