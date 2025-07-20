<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {
    private $mail;

    public function __construct() {
        // Require PHPMailer
        require APPROOT . '/vendor/autoload.php';
        
        // Create a new PHPMailer instance
        $this->mail = new PHPMailer(true);
        
        // Configure SMTP settings
        $this->mail->isSMTP();
        $this->mail->Host = EMAIL_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = EMAIL_USERNAME;
        $this->mail->Password = EMAIL_PASSWORD;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = EMAIL_PORT;
        
        // Set sender
        $this->mail->setFrom(EMAIL_FROM, SITENAME);
    }

    // Send email
    public function send($to, $subject, $body) {
        try {
            // Set recipient
            $this->mail->addAddress($to);
            
            // Set email format to HTML
            $this->mail->isHTML(true);
            
            // Set subject and body
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            // Send email
            $this->mail->send();
            
            return true;
        } catch (Exception $e) {
            // Log error
            error_log('Email could not be sent. Mailer Error: ' . $this->mail->ErrorInfo);
            
            return false;
        }
    }
}