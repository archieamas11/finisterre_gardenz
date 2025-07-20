<?php
require_once "../../../../include/initialize.php";
// ---------------------------------LOT OWNER UPDATE------------------------------------
if(isset($_POST["btn-edit-owner-setup"])){
    $lot_id = mysqli_real_escape_string($mysqli, $_POST["lot-id"]);
    $customer_id = mysqli_real_escape_string($mysqli, $_POST["customer-id"]);
    $graveID = mysqli_real_escape_string($mysqli, $_POST["customer-grave-number"]);
    $lawnType = mysqli_real_escape_string($mysqli, $_POST["customer-lawn-type"]);
    $paymentType = mysqli_real_escape_string($mysqli, $_POST["customer-payment-type"]);
    $paymentFrequency = mysqli_real_escape_string($mysqli, $_POST["edit-payment-frequency"]);
    $lotStatus = mysqli_real_escape_string($mysqli, $_POST["owner-lot-status"]);
    
    $startDate = date('Y-m-d H:i:s');    // Handle payment frequency and next due date logic
    if ($paymentType == 'full') {
        $nextDueDate = NULL;
        $paymentFrequency = "none";
        $nextDueDateValue = 'NULL';
        $lotStatus = "'completed'";
    } elseif ($paymentFrequency == 'monthly') {
        $nextDueDateValue = "DATE_ADD('$startDate', INTERVAL 30 DAY)";
        $lotStatus = "'$lotStatus'";
    } elseif ($paymentFrequency == 'annually') {
        $nextDueDateValue = "DATE_ADD('$startDate', INTERVAL 12 MONTH)";
        $lotStatus = "'$lotStatus'";
    } else {
        $nextDueDateValue = 'NULL';
        $lotStatus = "'$lotStatus'";
    }

    // Update the grave status - set previous grave to vacant if changed
    $currentGraveQuery = mysqli_query($mysqli, "SELECT grave_id FROM tbl_lot WHERE lot_id = '$lot_id'");
    $currentGraveRow = mysqli_fetch_array($currentGraveQuery);
    
    if ($lotStatus == "'cancelled'") {
        // Set grave to vacant when lot is cancelled
        mysqli_query($mysqli, "UPDATE grave_points SET status = 'vacant' WHERE grave_id = '".$currentGraveRow['grave_id']."'");
    } else if($currentGraveRow && $currentGraveRow['grave_id'] != $graveID) {
        // Set old grave to vacant
        mysqli_query($mysqli, "UPDATE grave_points SET status = 'vacant' WHERE grave_id = '".$currentGraveRow['grave_id']."'");
        // Set new grave to occupied
        mysqli_query($mysqli, "UPDATE grave_points SET status = 'occupied1' WHERE grave_id = '$graveID'");
    }

    // Update the lot information
    $sql = "UPDATE `tbl_lot` SET 
            `grave_id`='$graveID',
            `type`='$lawnType',
            `payment_type`='$paymentType',
            `payment_frequency`='$paymentFrequency',
            `start_date`='$startDate',
            `last_payment_date`=NOW(),
            `next_due_date`=$nextDueDateValue,
            `lot_status`=$lotStatus 
            WHERE `lot_id`='$lot_id'";

    $result = $mysqli->query($sql);

    if($result){
        message("Lot Owner Update Successful", "Lot owner information has been successfully updated in the system.", "success");
        header("location: ../../index.php?page=interment&tab=home");
    } else{
        message("Update Failed", "Unable to update lot owner information. Please check your data and try again. Error: " . $mysqli->error, "error");
        header("location: ../../index.php?page=interment");
    }
}
?>
