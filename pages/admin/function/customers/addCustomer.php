<?php
/**
 * Customer Registration Handler
 * This script processes new customer registration form submissions
 * for adding new customers information 
 */

require_once "../../../../include/initialize.php";

// Check if the customer registration form was submitted
if(isset($_POST["btn-submit-customer"])){
  // Sanitize all input data to prevent SQL injection
  $family_name=mysqli_real_escape_string($mysqli, $_POST["family-name"]);
  $first_name=mysqli_real_escape_string($mysqli, $_POST["first-name"]);
  $middle_name=mysqli_real_escape_string($mysqli, $_POST["middle-name"]);
  $nickname=mysqli_real_escape_string($mysqli, $_POST["nickname"]);
  $address=mysqli_real_escape_string($mysqli, $_POST["home-address"]);
  $contact=mysqli_real_escape_string($mysqli, $_POST["contact"]);
  $email=mysqli_real_escape_string($mysqli, $_POST["email"]);
  $bday=mysqli_real_escape_string($mysqli, $_POST["bday"]);
  $gender=mysqli_real_escape_string($mysqli, $_POST["gender"]);
  $religion=mysqli_real_escape_string($mysqli, $_POST["religion"]);
  $citizenship=mysqli_real_escape_string($mysqli, $_POST["citizenship"]);
  $civil_status=mysqli_real_escape_string($mysqli, $_POST["civil-status"]);
  $work=mysqli_real_escape_string($mysqli, $_POST["work"]);

  // Check for duplicate customer records by comparing full name
  $sql = "SELECT * FROM `tbl_customers` WHERE `last_name`='$family_name' AND `first_name`='$first_name' AND `middle_name`='$middle_name'";

  $sql=$mysqli->query($sql);
  $row=$sql->fetch_array();
  
  // Verify if customer with same name doesn't already exist
  if($family_name!=isset($row["last_name"])&&$first_name!=isset($row["first_name"])&&$middle_name!=isset($row["middle_name"])){
    $sql=$mysqli->query("INSERT INTO `tbl_customers`(`last_name`, `first_name`, `middle_name`, `nickname`, `address`, `contact_number`, `email`, `birth_date`, `gender`, `religion`, `citizenship`, `status`, `occupation`, date_created, date_modified) VALUES ('$family_name','$first_name','$middle_name','$nickname','$address','$contact','$email','$bday','$gender','$religion','$citizenship','$civil_status','$work', NOW(), NOW())");    
    
    if($sql){
      message("Customer Registration Successful", "Successfully added new customer to the system", "success");
      header("location: ../../index.php?page=interment");
    } else{
      message("Customer Registration Failed", "Unable to add customer. Please check your information and try again", "error");
      header("location: ../../index.php?page=interment");
    } 
  } else {
    message("Duplicate Customer Record", "A customer with the same name already exists in the system", "error");
    header("location: ../../index.php?page=interment");
  }
}
?>