<?php
require_once ("../../../include/initialize.php");
	 
    $function = (isset($_GET['function']) && $_GET['function'] != '') ? $_GET['function'] : '';
    switch ($function) {
        case 'link' :
            servicelink();
            break;

        case 'unlink' :
            serviceunlink();
            break;
        
        case 'delete' :
            deleteservice();
            break;

        case 'update' :
            serviceupdate();
            break;
        
        case 'add' :
            newservice();
            break;
    }

    function servicelink(){
        global $mysqli;
            $service_id = $_GET['serviceid']; 
            $sql = "UPDATE tbl_services SET service_availability = ? WHERE service_id = ?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ss", $param_availability, $param_serviceid);
                    $param_availability = 'available';
                    $param_serviceid = $service_id;
                    if ($stmt->execute()) {
                        header("location: ../index.php?page=shop");  
                        message ("Change Status", "The service is now marked as Available and can be purchased.", "success");
                    } else {
                        message ("Linking Failed", "An error occurred while updating the service availability. Please try again later.", "error");
                        header("location: ../index.php?page=shop");    
                    }
                } else {
                    message ("Database Error", "Could not prepare the statement to link the service. Please try again later.", "error");
                    header("location: ../index.php?page=shop");
                }
            $stmt->close();
        $mysqli->close();
    }

    function serviceunlink(){
        global $mysqli;
            $service_id = $_GET['serviceid']; 
            $sql = "UPDATE tbl_services SET service_availability = ? WHERE service_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ss", $param_availability, $param_serviceid);
                $param_availability = 'unavailable';
                $param_serviceid = $service_id;
                if ($stmt->execute()) {
                    header("location: ../index.php?page=shop");     
                    message ("Change Status", "The service is now marked as Unavailable and cannot be purchased.", "warning");
                } else {
                    message ("Unlinking Failed", "An error occurred while updating the service availability. Please try again later.", "error");
                    header("location: ../index.php?page=shop");    
                }
            } else {
                message ("Database Error", "Could not prepare the statement to unlink the service. Please try again later.", "error");
                header("location: ../index.php?page=shop");
            }
            $stmt->close();
        $mysqli->close();
    }

    function deleteservice(){
        global $mysqli;
        $service_id = $_GET['serviceid']; 
            $sql = "DELETE FROM tbl_services WHERE service_id = ?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $param_serviceid);
                    $param_serviceid = $service_id;
                    if ($stmt->execute()) {
                        message ("Service Deleted", "The service has been deleted successfully from the shop.", "success");
                        header("location: ../index.php?page=shop");     
                    } else {
                        message ("Deletion Failed", "An error occurred while deleting the service. Please try again later.", "error");
                        header("location: ../index.php?page=shop");    
                    }
                } else {
                    message ("Database Error", "Could not prepare the statement to delete the service. Please try again later.", "error");
                    header("location: ../index.php?page=shop");
                }
                $stmt->close();
            $mysqli->close();
    }    function serviceupdate(){
        global $mysqli;
        $service_id = $_GET['serviceid'];
        $service_name = $service_fee = $service_completion = $service_status = "";
        $name_error = $fee_error = $completion_error = $status_error = "";
        
        if(isset($_POST['btn-submit'])){
        
            if (empty($_POST["service-name"])) {
                $name_error = "true";
                message ("Missing Service Name", "The service name field must not be empty.", "error");
            }elseif (!preg_match('/^[a-z A-Z]+$/', $_POST["service-name"])) {
                $name_error = "true";
                message ("Invalid Service Name", "The service name can only contain letters.", "error");
            }else {
                $service_name = $_POST["service-name"];
            }
    
            if (empty($_POST["service-fee"])) {
                $fee_error = "true";
                message ("Missing Service Fee", "The service fee field must not be empty.", "error");
            }elseif (!preg_match('/^[0-9.]+$/', $_POST["service-fee"])) {
                $fee_error = "true";
                message ("Invalid Service Fee", "The service fee must be a numeric value.", "error");
            } else {
                $service_fee = $_POST["service-fee"];
            }
    
            if (empty($_POST["service-duration"])) {
                $completion_error = "true";
                message ("Missing Service Duration", "The service duration field must not be empty.", "error");
            }elseif (!preg_match('/^[0-9]+$/', $_POST["service-duration"])) {
                $completion_error = "true";
                message ("Invalid Service Duration", "The service duration must be a numeric value.", "error");
            } else {
                $service_completion = $_POST["service-duration"];
            }
    
            if (empty($_POST["service-status"])) {
                $status_error = "true";
                message ("Missing Service Status", "The service status field must not be empty.", "error");
            } else {
                $service_status = $_POST["service-status"];
            }
            
            if (empty($name_error) && empty($fee_error) && empty($completion_error) && empty($status_error)) {
                $sql = "UPDATE tbl_services SET service_name = ?, service_cost = ?, service_availability = ?, service_completion = ? WHERE service_id = ?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("sssss", $param_name ,$param_fee, $param_availability, $param_completion, $param_id);
                    $param_name = $service_name;
                    $param_fee = $service_fee;
                    $param_availability = $service_status;
                    $param_completion = $service_completion;
                    $param_id = $service_id;
                    
                    if ($stmt->execute()) {
                        message ("Service Updated", "The service details have been updated successfully.", "success");
                        header("location: ../index.php?page=shop");     
                    } else {
                        message ("Update Failed", "An error occurred while updating the service. Please try again later.", "error");
                        header("location: ../index.php?page=shop");    
                    }
                    $stmt->close();
                } else {
                    message ("Database Error", "Could not prepare the statement to update the service. Please try again later.", "error");
                    header("location: ../index.php?page=shop");
                }
            } else {
                header("location: ../index.php?page=shop");
            }
        }
        $mysqli->close();
    }    function newservice(){
        global $mysqli;
        $service_name = $service_fee = $service_completion = $service_status = "";
        $name_error = $fee_error = $completion_error = $status_error = "";
        
        if(isset($_POST['btn-submit'])){
        
            if (empty($_POST["service-name"])) {
                $name_error = "true";
                message ("Missing Service Name", "The service name field must not be empty.", "error");
            }elseif (!preg_match('/^[a-z A-Z]+$/', $_POST["service-name"])) {
                $name_error = "true";
                message ("Invalid Service Name", "The service name can only contain letters.", "error");
            }else {
                $service_name = $_POST["service-name"];
            }
    
            if (empty($_POST["service-fee"])) {
                $fee_error = "true";
                message ("Missing Service Fee", "The service fee field must not be empty.", "error");
            }elseif (!preg_match('/^[0-9.]+$/', $_POST["service-fee"])) {
                $fee_error = "true";
                message ("Invalid Service Fee", "The service fee must be a numeric value.", "error");
            } else {
                $service_fee = $_POST["service-fee"];
            }
    
            if (empty($_POST["service-duration"])) {
                $completion_error = "true";
                message ("Missing Service Duration", "The service duration field must not be empty.", "error");
            }elseif (!preg_match('/^[0-9]+$/', $_POST["service-duration"])) {
                $completion_error = "true";
                message ("Invalid Service Duration", "The service duration must be a numeric value.", "error");
            } else {
                $service_completion = $_POST["service-duration"];
            }
    
            if (empty($_POST["service-status"])) {
                $status_error = "true";
                message ("Missing Service Status", "The service status field must not be empty.", "error");
            } else {
                $service_status = $_POST["service-status"];
            }
            
            if (empty($name_error) && empty($fee_error) && empty($completion_error) && empty($status_error)) {
                $sql = "INSERT INTO tbl_services(service_name, service_cost, service_availability, service_completion) VALUES (?,?,?,?)";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ssss", $param_name ,$param_fee, $param_availability, $param_completion);
                    $param_name = $service_name;
                    $param_fee = $service_fee;
                    $param_availability = $service_status;
                    $param_completion = $service_completion;
                    
                    if ($stmt->execute()) {
                        message ("Service Added", "A new service has been added to the shop successfully.", "success");
                        header("location: ../index.php?page=shop");     
                    } else {
                        message ("Addition Failed", "An error occurred while adding the new service. Please try again later.", "error");
                        header("location: ../index.php?page=shop");    
                    }
                    $stmt->close();
                } else {
                    message ("Database Error", "Could not prepare the statement to add the new service. Please try again later.", "error");
                    header("location: ../index.php?page=shop");
                }
            } else {
                header("location: ../index.php?page=shop");
            }
        }
        $mysqli->close();
    }
?>