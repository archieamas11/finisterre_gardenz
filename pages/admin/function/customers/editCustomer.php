<?php
require_once "../../../../include/initialize.php";
// ---------------------------------CUSTOMER SUBMIT------------------------------------
if(isset($_POST["btn-update"])){
    $modal_customer_id=mysqli_real_escape_string($mysqli, $_POST["modal-customer-id"]);
    $modal_last_name=mysqli_real_escape_string($mysqli, $_POST["modal-family-name"]);
    $modal_first_name=mysqli_real_escape_string($mysqli, $_POST["modal-first-name"]);
    $modal_middle_name=mysqli_real_escape_string($mysqli, $_POST["modal-middle-name"]);
    $modal_nickname=mysqli_real_escape_string($mysqli, $_POST["modal-nickname"]);
    $modal_address=mysqli_real_escape_string($mysqli, $_POST["modal-home-address"]);
    $modal_contact=mysqli_real_escape_string($mysqli, $_POST["modal-contact"]);
    $modal_email=mysqli_real_escape_string($mysqli, $_POST["modal-email"]);
    $modal_bday=mysqli_real_escape_string($mysqli, $_POST["modal-bday"]);
    $modal_gender=mysqli_real_escape_string($mysqli, $_POST["modal-gender"]);
    $modal_religion=mysqli_real_escape_string($mysqli, $_POST["modal-religion"]);
    $modal_citizenship=mysqli_real_escape_string($mysqli, $_POST["modal-citizenship"]);
    $modal_civil_status=mysqli_real_escape_string($mysqli, $_POST["modal-civil-status"]);
    $modal_work=mysqli_real_escape_string($mysqli, $_POST["modal-work"]);


  

    $sql = "UPDATE `tbl_customers` SET `first_name`='$modal_first_name',`middle_name`='$modal_middle_name',`last_name`='$modal_last_name',`nickname`='$modal_nickname',`address`='$modal_address',`contact_number`='$modal_contact',`email`='$modal_email',`birth_date`='$modal_bday',`gender`='$modal_gender',`religion`='$modal_religion',`citizenship`='$modal_citizenship',`status`='$modal_civil_status',`occupation`='$modal_work', `date_modified` = NOW() WHERE `customer_id`='$modal_customer_id'";

    $sql=$mysqli->query($sql);
  
    if($sql){
      message("Customer Update Successful", "Customer information has been successfully updated in the system.", "success");
      header("location: ../../index.php?page=interment");
    } else{
      message("Update Failed", "Unable to update customer information. Please check your data and try again.", "error");
      header("location: ../../index.php?page=interment");
    } 
}
?>
