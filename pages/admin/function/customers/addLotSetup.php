<?php
require_once "../../../../include/initialize.php";
// ---------------------------------CUSTOMER SUBMIT------------------------------------
if(isset($_POST["btn-owner-setup"])){
    $customer_id=mysqli_real_escape_string($mysqli, $_POST["customer-id"]);
    $graveID=mysqli_real_escape_string($mysqli, $_POST["customer-grave-number"]);
    $lawnType=mysqli_real_escape_string($mysqli, $_POST["customer-lawn-type"]);
    $paymentType=mysqli_real_escape_string($mysqli, $_POST["customer-payment-type"]);
    $paymentFrequency=mysqli_real_escape_string($mysqli, $_POST["customer-payment-frequency"]);
    $time=time();

    $startDate = date('Y-m-d H:i:s');
    $lot_status = 'active';

    if ($paymentType == 'full') {
        $nextDueDate = 'NULL';
        $paymentFrequency = 'none'; // No frequency for full payment
        $lot_status = 'completed'; // Set status to completed for full payment
    } elseif ($paymentFrequency == 'monthly') {
        $nextDueDate = "DATE_ADD('$startDate', INTERVAL 30 DAY)";
    } elseif ($paymentFrequency == 'annually') {
        $nextDueDate = "DATE_ADD('$startDate', INTERVAL 12 MONTH)";
    } else {
        $nextDueDate = 'NULL'; // fallback
    }

    $sql = $mysqli->query("INSERT INTO `tbl_lot`(`customer_id`, `grave_id`, `type`, `payment_type`, `payment_frequency`, `start_date`, `next_due_date`, `lot_status`)
    VALUES ('$customer_id','$graveID','$lawnType','$paymentType','$paymentFrequency', '$startDate', $nextDueDate, '$lot_status')");
    if($sql){
        $mysqli->query("UPDATE `grave_points` SET `status`='reserved' WHERE `grave_id`='$graveID'");
        message("Lot Setup Complete", "Lot setup has been successfully configured and grave location has been reserved.", "success");
        header("location: ../../index.php?page=interment");
    } else{
      message("Setup Failed", "Unable to complete lot setup. Please verify your information and try again.", "error");
      header("location: ../../index.php?page=interment");
    } 
}
?>
