<?php
require_once "../../../../include/initialize.php";

if(isset($_POST["btn-submit-dead"])){
    // Sanitize input data
    $lotID = mysqli_real_escape_string($mysqli, $_POST["lot-id"]);
    $graveID = mysqli_real_escape_string($mysqli, $_POST["grave-id"]);
    $customerID = mysqli_real_escape_string($mysqli, $_POST["customer-id"]);
    $deadFullName = mysqli_real_escape_string($mysqli, 
        $_POST["dead-first-name"] . " " . 
        $_POST["dead-middle-name"] . " " . 
        $_POST["dead-last-name"]
    );
    $deadGender = mysqli_real_escape_string($mysqli, $_POST["dead-gender"]);
    $deadCitizenship = mysqli_real_escape_string($mysqli, $_POST["dead-citizenship"]);
    $deadCivilStatus = mysqli_real_escape_string($mysqli, $_POST["dead-civil-status"]);
    $deadRelationship = mysqli_real_escape_string($mysqli, $_POST["dead-relationship"]);
    $deadVisibility = mysqli_real_escape_string($mysqli, $_POST["dead-visibility"]);
    $deadIntern = mysqli_real_escape_string($mysqli, $_POST["dead-intern"]);
    $deadBday = mysqli_real_escape_string($mysqli, $_POST["dead-bday"]);
    $deadDeath = mysqli_real_escape_string($mysqli, $_POST["dead-death"]);

    // Fixed SQL query - removed single quote around dead_interment column name
    $sql = $mysqli->query("INSERT INTO `tbl_deceased`(
        `grave_id`, 
        `lot_id`, 
        `customer_id`, 
        `dead_fullname`, 
        `dead_gender`, 
        `dead_citizenship`, 
        `dead_civil_status`, 
        `dead_relationship`, 
        `dead_interment`, 
        `dead_birth_date`, 
        `dead_date_death`, 
        `dead_visibility`
    ) VALUES (
        '$graveID', 
        '$lotID', 
        '$customerID', 
        '$deadFullName', 
        '$deadGender', 
        '$deadCitizenship', 
        '$deadCivilStatus', 
        '$deadRelationship', 
        '$deadIntern', 
        '$deadBday', 
        '$deadDeath', 
        '$deadVisibility'
    )");

    if($sql){
        message("Record Saved", "Deceased record has been successfully saved and grave status updated", "success");
        $updateGraveStatus = $mysqli->query("UPDATE grave_points SET status = 'occupied1' WHERE grave_id = '$graveID'");
        if(!$updateGraveStatus) {
            message("Update Failed", "Deceased record saved but failed to update grave status", "error");
            header("location: ../../index.php?page=interment");
            exit();
        }
        header("location: ../../index.php?page=interment");
        exit();
    } else {
        message("Save Failed", "Unable to save deceased record. Please check your input and try again", "error");
        header("location: ../../index.php?page=interment");
        exit();
    } 
} 
?>
