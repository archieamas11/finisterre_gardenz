<?php
require_once("../../../include/initialize.php");

// echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: monospace;'>";
// var_dump($_POST); // Debugging line to check the POST data
// echo "</pre>";


if (!isset($_SESSION['id'])) {
    message("Please login first", "error");
    header("location: ../index.php?page=login");
    exit;
}

// Fetch customer_id from tbl_customers based on session ID
$session_id = $_SESSION['id'];
$query = "SELECT customer_id FROM tbl_customers WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$customer_id = $customer['customer_id'];


if (isset($_POST['btn-submit-checkout'])) {
    $service_id = mysqli_real_escape_string($mysqli, $_POST["service_id"]);
    $deceased_id = mysqli_real_escape_string($mysqli, $_POST["deceasedId"]);
    $ordercode = mysqli_real_escape_string($mysqli, $_POST["orderNumber"]);
    $instruction = mysqli_real_escape_string($mysqli, $_POST["instruction"]);

    if (empty($deceased_id)) {
        message("Please complete all required fields.", "error");
        header("location: ../index.php?page=service_form&service_id=$service_id");
        exit;
    }

    $query = "SELECT grave_id FROM tbl_deceased WHERE record_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $deceased_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $grave = $result->fetch_assoc();
    $grave_id = $grave['grave_id'];

    $query = "INSERT INTO tbl_orders (customer_id, service_id, grave_id, deceased_id, payment_method, payment_status, order_refnumber, instruction, order_status, order_date) 
                VALUES ('$customer_id', '$service_id', '$grave_id', '$deceased_id', 'in-person', 'unpaid', '$ordercode', '$instruction', 'pending', NOW())";

    if ($mysqli->query($query)) {
        message("Request was sent successfully", "success");
        include __DIR__ . '/../../../include/liveNotification.php';
        sendToLiveNotification($ordercode);
        message("Order Submitted Successfully", "Service order successfully submitted! Your order reference number is: " . $ordercode, "success");
        header("location: ../index.php?page=service");
        exit;
    } else {
        die("Database Error: " . $mysqli->error);
    }
}
?>