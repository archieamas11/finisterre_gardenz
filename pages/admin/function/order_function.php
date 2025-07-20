<?php
require_once (__DIR__ . "/../../../include/initialize.php");
require_once (__DIR__ . "/../../../include/notificationsConfig.php");


$function = (isset($_GET['function']) && $_GET['function'] != '') ? $_GET['function'] : '';
switch ($function) {
    case 'checkout' :
        checkout();
        break;

	case 'pay' :
        pay();
        break;

    case 'cancel' :
        cancel();
        break;

    case 'complete' :
        complete();
        break;

    case 'account' :
        account();
        break;
}
function checkout(){
    global $mysqli;
    $name_error = $email_error = $contact_error = $grave_error = $deceasedname_error = $instruction_error = "";
    $name = $email = $contact = $graveno = $deceasedname = $instruction = "";

    $ordercost = $_GET['ordercost'];
    $ordername = $_GET['ordername'];
    $ordercode = $_POST['orderNumber'];
    $date = date("Y-m-d H:i:s");

    if (isset($_POST['checkout'])) {
        
        if (empty($_POST['ordererName'])) {
            $name_error = "true";
        } else {
            $id = $_POST['id'];
        }

        if (empty($_POST['ordererEmail'])) {
            $email_error = "true";
        } else {
            $email = $_POST['ordererEmail'];
        }

        if (empty($_POST['ordererContact'])) {
            $contact_error = "true";
        } else {
            $contact = $_POST['ordererContact'];
        }

        if (empty($_POST['graveNo'])) {
            $grave_error = "true";
        } else {
            $graveno = $_POST['graveNo'];
        }

        if (empty($_POST['deceasedName'])) {
            $deceasedname_error = "true";
        } else {
            $deceasedname = $_POST['deceasedName'];
        }

        if (empty($_POST['instruction'])) {
            $instruction_error = "true";
        } else {
            $instruction = $_POST['instruction'];
        }

        if (empty($name_error) && empty($deceasedname_error)) {

            $sql = "INSERT INTO tbl_orders (orderer_id, orderer_email, orderer_contact, order_name, selected_grave, deceased_name, order_total, payment_method, payment_status, order_refnumber, instruction, order_status, order_date) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssssssssssss", $param_id, $param_email, $param_contact, $param_service, $param_grave, $param_deceasedname, $param_total, $param_method, $param_paymentstatus, $param_code, $param_instruction, $param_status, $param_date);
                $param_id = $id;
                $param_email = $email;
                $param_contact = $contact;
                $param_service = $ordername;
                $param_grave = $graveno;
                $param_deceasedname = $deceasedname;
                $param_total = $ordercost;
                $param_method = "In-Person";
                $param_paymentstatus = "Un-paid";
                $param_code = $ordercode;
                $param_instruction = $instruction;
                $param_status = "Pending";
                $param_date = $date;

                if ($stmt->execute()) {
                    message ("Order Placed", "Your order was placed successfully.", "success");
                    header("location: ../index.php?page=order");
                } else {
                    message ("Order Failed", "Something went wrong while placing your order. Please try again later.", "error");
                    header("location: ../index.php?page=order");
                }
                $stmt->close();
            }
            
        } else {
            message ("Incomplete Fields", "Please ensure all required fields are filled out before submitting your order.", "error");
            header("location: ../index.php?page=order");
        }   
    }
    $mysqli->close();
}

function pay() {
    global $mysqli;
    $refnumber = $_GET['refnumber'];
    
    // First, check the current payment status
    $check_sql = "SELECT payment_status FROM tbl_orders WHERE order_refnumber = ?";
    if ($check_stmt = $mysqli->prepare($check_sql)) {
        $check_stmt->bind_param("s", $refnumber);
        if ($check_stmt->execute()) {
            $result = $check_stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $current_payment_status = $row['payment_status'];
                
                // Determine what to update based on current payment status
                if ($current_payment_status == 'paid') {
                    // Only update order status to active
                    $sql = "UPDATE tbl_orders SET order_status = ? WHERE order_refnumber = ?";
                    if ($stmt = $mysqli->prepare($sql)) {
                        $stmt->bind_param("ss", $param_order, $param_refnumber);
                        $param_order = 'active';
                        $param_refnumber = $refnumber;
                    }
                } else {
                    // Update both payment status and order status
                    $sql = "UPDATE tbl_orders SET payment_status = ?, order_status = ? WHERE order_refnumber = ?";
                    if ($stmt = $mysqli->prepare($sql)) {
                        $stmt->bind_param("sss", $param_payment, $param_order, $param_refnumber);
                        $param_payment = 'paid';
                        $param_order = 'active';
                        $param_refnumber = $refnumber;
                    }
                }
                
                if (isset($stmt) && $stmt->execute()) {
                    $activity = "Order $refnumber accepted";
                    $activity_query = "INSERT INTO tbl_activity (user_id, act_title, act_description, act_date) VALUES (?, ?, ?, ?)";
                    if ($stmt_act = $mysqli->prepare($activity_query)) {
                        $stmt_act->bind_param("isss", $param_user, $param_activity, $param_description, $param_act_date);
                        $param_user = $_SESSION['id'];
                        $param_activity = $activity;
                        $param_description = "Order #$refnumber is now active and payment has been confirmed.";
                        $param_act_date = date("Y-m-d H:i:s");
                        if ($stmt_act->execute()) {
                            message ("Order Accepted", "Order #$refnumber is now active and payment has been confirmed.", "success");
                            header("location: ../index.php?page=order");
                        } else {
                            message ("Activity Log Error", "Order was activated but activity could not be logged. Please try again later.", "error");
                            header("location: ../index.php?page=order");        
                        }
                    } else {
                        message ("ERROR", "Something went wrong please try again later","error");
                        header("location: ../index.php?page=order");
                    }     
                } else {
                    message ("Payment Update Failed", "Unable to update payment status. Please try again later.", "error");
                    header("location: ../index.php?page=order");    
                }
                if (isset($stmt)) $stmt->close();
            } else {
                message ("Order Not Found", "The specified order could not be found.", "error");
                header("location: ../index.php?page=order");
            }
        } else {
            message ("Database Error", "Could not check current payment status. Please try again later.", "error");
            header("location: ../index.php?page=order");
        }
        $check_stmt->close();
    } else {
        message ("Database Error", "Could not prepare the payment check statement. Please try again later.", "error");
        header("location: ../index.php?page=order");
    }
    $mysqli->close();
}

function cancel() {
    global $mysqli;
    $refnumber = $_GET['refnumber'];
        
        $sql = "UPDATE tbl_orders SET order_status = 'cancelled' WHERE order_refnumber = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_refnumber);
            $param_refnumber = $refnumber;
            if ($stmt->execute()) {
                $activity = "Order $refnumber cancelled";
                $activity_query = "INSERT INTO tbl_activity (user_id, act_title, act_description, act_date) VALUES (?, ?, ?, ?)";
                if ($stmt_act = $mysqli->prepare($activity_query)) {
                    $stmt_act->bind_param("isss", $param_user, $param_activity, $param_description, $param_act_date);
                    $param_user = $_SESSION['id'];
                    $param_activity = $activity;
                    $param_description = "Order #$refnumber has been cancelled.";
                    $param_act_date = date("Y-m-d H:i:s");
                    if ($stmt_act->execute()) {
                        message ("Order Cancelled", "The order has been successfully cancelled and removed from the system.", "success");
                        header("location: ../index.php?page=order");
                    } else {
                        message ("Activity Log Error", "Order was cancelled but activity could not be logged. Please try again later.", "error");
                        header("location: ../index.php?page=order");        
                    }
                } else {
                    message ("Something went wrong please try again later ","error");
                    header("location: ../index.php?page=order");
                }
            } else {
                message ("Order Cancellation Failed", "Unable to cancel the order. Please try again later.", "error");
                header("location: ../index.php?page=order");
            }
        } else {
            message ("Database Error", "Could not prepare the cancellation statement. Please try again later.", "error");
            header("location: ../index.php?page=order");
        }
        $stmt->close();
    $mysqli->close();
}

function complete() {
    global $mysqli;
    $refnumber = $_GET['referencenumber'];
    $sql = "UPDATE tbl_orders SET order_status = ? WHERE order_refnumber = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $param_order = 'complete';
        $stmt->bind_param("ss", $param_order, $refnumber);
        
        if ($stmt->execute()) {
            $date = date('M-d-Y');
            $personnel = $_SESSION['name'];
            $activity = "Order $refnumber completed";
            
            $activity_query = "INSERT INTO tbl_activity (user_id, act_title, act_description, act_date) VALUES (?, ?, ?, ?)";
            if ($stmt_act = $mysqli->prepare($activity_query)) {
                $param_activity = $activity;
                $param_user = $_SESSION['id'];
                $param_description = "Order #$refnumber has been completed.";
                $param_act_date = date("Y-m-d H:i:s");
                $stmt_act->bind_param("isss", $param_user, $param_activity, $param_description, $param_act_date);

                if ($stmt_act->execute()) {
                    $fetch = $mysqli->query("SELECT grave.*, users.first_name, users.last_name FROM tbl_orders AS grave
                    JOIN tbl_customers AS users ON grave.customer_id = users.customer_id
                    WHERE order_refnumber = '$refnumber'");
                    if ($fetch && $row = $fetch->fetch_assoc()) {
                        if (!empty($row['orderer_email'])) {
                            $subject = "Notice of Completion for Order #$refnumber";
                            $message = "Your order #$refnumber has been completed successfully. Thank you for choosing our service.";
                            sendViaEmail($row['orderer_email'], $subject, $message);
                        }
                        message("Order Completed", "Order #$refnumber has been marked as completed and the customer has been notified.", "success");
                        header("Location: ../index.php?page=order");
                        } else {
                            message("Activity Log Error", "Failed to log activity for order completion. Please try again later.", "error");
                        }
                        } else {
                            message("Database Error", "Unable to log activity for order completion.", "error");
                        }
                        } else {
                            message("Order Status Update Failed", "Failed to update order status. Please try again.", "error");
                        }
                        $stmt->close();
                        } else {
                            message("Database Error", "Unable to prepare statement for order completion.", "error");
                        }
                        $mysqli->close();
                        header("Location: ../index.php?page=order");
}
}



function account() {
    global $mysqli;
    $emp_fname = $emp_lname = $emp_email = $emp_status = $emp_pass = "";
    $fname_error = $lname_error =  $email_error = $pass_error = "";
    
    if(isset($_POST['btn-submit'])){
    
        // Firstname
        if (empty($_POST['acc-fname'])) {
            $fname_error = "true";
        }else {
            $emp_fname = $_POST['acc-fname'];
        }

        // Lastname
        if (empty($_POST['acc-lname'])) {
            $lname_error = "true";
        }else {
            $emp_lname = $_POST['acc-lname'];
        }

        // Email
        if (empty($_POST['acc-email'])) {
            $email_error = "true";
        } else {
            $emp_email = $_POST['acc-email'];
        }

        // Password
        if (empty($_POST['acc-pass'])) {
            $pass_error = "true";
        } else {
            $emp_pass = $_POST['acc-pass'];
        }

        $emp_status = $_POST['acc-role'];
        
        if (empty($fname_error) && empty($lname_error) && empty($email_error)) {
            $sql = "INSERT INTO grave_user (user_type, user_name, user_email, user_password, user_status, creation_date) VALUE (?,?,?,?,?,?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssssss", $param_role ,$param_name, $param_email, $param_password, $param_status, $param_date);
                $param_role = $emp_status;
                $param_name = $emp_fname." ".$emp_lname;
                $param_email = $emp_email;
                $param_password = password_hash($emp_pass, PASSWORD_DEFAULT);;
                $param_status = "verified";
                $param_date = date("Y-m-d H:i:s");
                if ($stmt->execute()) {
                    message ("Account Registered", "The account has been registered successfully.", "success");
                    header("location: ../index.php?page=account");     
                } else {
                    message ("Registration Failed", "Something went wrong while registering the account. Please try again later.", "error");
                    header("location: ../index.php?page=account");    
                }
                $stmt->close();
            } else {
                message ("Database Error", "Could not prepare the registration statement. Please try again later.", "error");
                header("location: ../index.php?page=account");
            }
        } else {
            message("Invalid Input", "Please make sure you are entering valid information and that all fields are filled out.", "error");
            header("location: ../index.php?page=account");
        }
    }
        $mysqli->close();
}

?>