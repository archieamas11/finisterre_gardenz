<?php
require_once(__DIR__ . "/config.php");

function sendSmsViaTextBee($recipients, $message) {    
    $data = [
        'recipients' => $recipients,
        'message' => $message
        // 'sender' => 'CemeterEase'
    ];
    
    $headers = [
        'x-api-key: ' . TEXTBEE_API_KEY,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init(TEXTBEE_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: " . $error);
    }
    
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function sendViaEmail($recipients, $subject, $message) {
    // require '/../vendor/autoload.php';
    require (__DIR__. "/../vendor/autoload.php");
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = EMAIL_PORT;
        
        // Recipients
        $mail->setFrom(EMAIL_FROM, SYSTEM_NAME);
        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient);
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);
        
        $mail->send();
        return [
            'status_code' => 200,
            'response' => ['message' => 'Email sent successfully']
        ];
    } catch (Exception $e) {
        return [
            'status_code' => 500,
            'response' => ['error' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"]
        ];
    }
}
?>