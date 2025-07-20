<?php
require_once __DIR__ . "/../../../../include/initialize.php";
if(isset($_POST["save-edit-profile"])){
    $customerID=mysqli_real_escape_string($mysqli, $_POST["customer_id"]);
    $firstName=mysqli_real_escape_string($mysqli, $_POST["name"]);
    $middleName=mysqli_real_escape_string($mysqli, $_POST["mname"]);
    $lastName=mysqli_real_escape_string($mysqli, $_POST["lname"]);
    $nickname=mysqli_real_escape_string($mysqli, $_POST["nickname"]);
    $email=mysqli_real_escape_string($mysqli, $_POST["email"]);
    $phone=mysqli_real_escape_string($mysqli, $_POST["phone"]);
    $birthday=mysqli_real_escape_string($mysqli, $_POST["birthday"]);
    $gender=mysqli_real_escape_string($mysqli, $_POST["gender"]);
    $address=mysqli_real_escape_string($mysqli, $_POST["address"]);

    $sql = "UPDATE `tbl_customers` SET `first_name`='$firstName',`middle_name`='$middleName',`last_name`='$lastName',`nickname`='$nickname',`address`='$address',`contact_number`='$phone',`email`='$email',`birth_date`='$birthday',`gender`='$gender', `date_modified` = NOW() WHERE `customer_id`='$customerID'";

    $sql=$mysqli->query($sql);
  
    if($sql){
      message("Customer Update Successful", "Customer information has been successfully updated in the system.", "success");
      header("location: ../../index.php?page=dashboard");
    } else{
      message("Update Failed", "Unable to update customer information. Please check your data and try again.", "error");
      header("location: ../../index.php?page=dashboard");
    } 
}
?>
