<?php
require_once(__DIR__ . "/../../include/database.php");
require_once(__DIR__ . "/../../include/config.php");

// Set content type to JSON
header('Content-Type: application/json');

$sql = "UPDATE tbl_notifications SET is_read = 1 WHERE is_read = 0";
$result = $mysqli->query($sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Notifications marked as read.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error marking notifications as read: ' . $mysqli->error]);
}