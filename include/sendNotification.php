<?php
require_once(__DIR__ . "/notificationsConfig.php");
require_once(__DIR__ . "/emailMessage.php");
require_once(__DIR__ . "/database.php");


// Query to find lots with due dates within 15 days
$query = "SELECT l.*, c.first_name, c.last_name, c.contact_number, c.email 
          FROM tbl_lot l 
          JOIN tbl_customers c ON l.customer_id = c.customer_id 
          WHERE l.next_due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)
          AND l.lot_status = 'active'";

$result = mysqli_query($mysqli, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format the message
        $subject = "Payment Reminder: Lot Payment Due Soon";
        // HTML formatted email message
        $htmlMessage = lotReminderDueDateTemplate($row['first_name'], $row['last_name'], $row['next_due_date'], SYSTEM_NAME);

        // Plain text message for SMS
        $plainMessage = "Dear " . ucwords($row['first_name']) . " " . ucwords($row['last_name']) . ",\n\n";
        $plainMessage .= "This is a reminder that your lot payment is due on " . $row['next_due_date'] . ".\n";
        $plainMessage .= "Please settle your payment to avoid any penalties.\n\n";
        $plainMessage .= "Thank you,\n" . SYSTEM_NAME;
        // Send SMS if contact number exists
        if (!empty($row['contact_number'])) {
            try {
                $smsResult = sendSmsViaTextBee([$row['contact_number']], $plainMessage);
                if ($smsResult['status_code'] === 200 || $smsResult['status_code'] === 201) {
                    echo "SMS sent successfully to " . $row['first_name'] . " " . $row['last_name'] . " (" . $row['contact_number'] . ")\n";
                } else {
                    echo "Failed to send SMS to " . $row['first_name'] . " " . $row['last_name'] . " (" . $row['contact_number'] . ")\n";
                }
            } catch (Exception $e) {
                echo "Error sending SMS to " . $row['first_name'] . " " . $row['last_name'] . ": " . $e->getMessage() . "\n";
            }
        }

        // Send email if email exists
        if (!empty($row['email'])) {
            try {
                $emailResult = sendViaEmail([$row['email']], $subject, $htmlMessage);
                if ($emailResult['status_code'] === 200) {
                    echo "Email sent successfully to " . $row['email'] . "\n";
                } else {
                    echo "Failed to send email to " . $row['email'] . "\n";
                }
            } catch (Exception $e) {
                echo "Error sending email to " . $row['email'] . ": " . $e->getMessage() . "\n";
            }
        }
    }
} else {
    echo "No upcoming due payments found within the next 15 days.\n";
}
?>