<?php
require_once "../include/config.php";
require_once "../include/database.php";
require_once "../include/google_auth.php";
require_once "../include/notificationsConfig.php";


$forgotPasswordEmail = $email_error = $email = $reset_sent = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["forgotPasswordEmail"]))) {
        $email_error = "Please enter email";
    } else {
        $email = trim($_POST["forgotPasswordEmail"]);
    }

    if (empty($email_error)) {
        // Get user details from both tables
        $sql = "SELECT c.customer_id, c.email, u.user_id 
                FROM tbl_customers c 
                JOIN tbl_users u ON c.user_id = u.user_id 
                WHERE c.email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($customer_id, $email, $user_id);
                    if ($stmt->fetch()) {
                        // Generate reset token and expiry
                        $reset_token = bin2hex(random_bytes(32));
                        $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                        $verification_link = WEBROOT . "login/resetPassword.php?token=" . urlencode($reset_token);


                        // Update user table with reset token
                        $update_sql = "UPDATE tbl_users SET reset_token = ?, reset_token_expires = ? WHERE user_id = ?";
                        if ($update_stmt = $mysqli->prepare($update_sql)) {
                            $update_stmt->bind_param("ssi", $reset_token, $reset_expires, $user_id);
                            if ($update_stmt->execute()) {
                                // Send email with reset link
                                $subject = "Password Reset Request";
                                $message = "Dear " . SYSTEM_NAME . " User,\n\n"
                                    . "We received a request to reset your password. If you did not make this request, please ignore this email.\n\n"
                                    . "To reset your password, click on the following link (valid for 1 hour):\n"
                                    . $verification_link . "\n\n"
                                    . "For security reasons, this link will expire in 1 hour.\n\n"
                                    . "Best regards,\n"
                                    . "The " . SYSTEM_NAME . " Team";
                                sendViaEmail([$email], $subject, $message);
                                $reset_sent = "Reset link has been sent to your email address. Please check your inbox.";
                            } else {
                                $email_error = "Failed to update reset token. Please try again.";
                            }
                            $update_stmt->close();
                        }
                    }
                } else {
                    $email_error = "No account found with that email address.";
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/png">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/auth.css">
</head>

<body>

    <body>
        <section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
            <div class="d-flex flex-column w-100 align-items-center" style="max-width: 400px;">
                <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100">
                    <!-- Logo -->
                    <div class="text-center mb-5">
                        <a href="../index.html" class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
                    </div>

                    <!-- Title -->
                    <h2 class="fw-bold text-center mb-1">Forgot Password</h2>
                    <p class="text-center text-muted mb-4">Enter your email to receive a reset link</p>

                    <form class="form-forgot-password" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <!-- Email Field -->
                        <div class="form-group mb-4 w-100">
                            <label class="fw-semibold mb-1">Email address</label>
                            <input type="email" name="forgotPasswordEmail"
                                class="form-control bg-light-subtle rounded <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>"
                                placeholder="email@example.com"
                                value="<?php echo $email; ?>"
                                required>
                            <span class="invalid-feedback"><?php echo $email_error; ?></span>
                        </div>

                        <!-- Reset Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-4">Send Reset Link</button>

                        <!-- Success Message (hidden by default) -->
                        <?php if (!empty($reset_sent)): ?>
                            <div class="alert alert-success text-center">
                                <?php echo $reset_sent; ?>
                            </div>
                        <?php endif; ?>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none text-primary fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</body>

</html>