<?php
require_once "../include/config.php";
require_once "../include/database.php";

$password = $confirm_password = "";
$password_err = $confirm_password_err = $token_err = $success_msg = "";
$valid_token = false;

// Check if token is provided in URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = trim($_GET['token']);
    
    // Verify token exists and hasn't expired
    $sql = "SELECT user_id, reset_token_expires FROM tbl_users WHERE BINARY reset_token = ? AND reset_token_expires > NOW()";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $valid_token = true;
                $stmt->bind_result($user_id, $expires);
                $stmt->fetch();
            } else {
                // Debug information
                $debug_sql = "SELECT reset_token, reset_token_expires FROM tbl_users WHERE reset_token IS NOT NULL";
                $debug_result = $mysqli->query($debug_sql);
                if ($debug_result && $debug_result->num_rows > 0) {
                    $row = $debug_result->fetch_assoc();
                    if ($row['reset_token'] === $token) {
                        $token_err = "Token found but might be expired. Expires: " . $row['reset_token_expires'];
                    } else {
                        $token_err = "Invalid reset token.";
                    }
                } else {
                    $token_err = "No matching token found in database.";
                }
            }
        }
        $stmt->close();
    }
} else {
    $token_err = "No reset token provided.";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $valid_token) {
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before updating password
    if (empty($password_err) && empty($confirm_password_err)) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $sql = "UPDATE tbl_users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success_msg = "Password has been reset successfully! You can now login with your new password.";
                // Clear form values
                $password = $confirm_password = "";
            } else {
                $password_err = "Something went wrong. Please try again.";
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
    <title>Reset Password - CemeterEase</title>
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/png">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/auth.css">
</head>
<body>
    <section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
        <div class="d-flex flex-column w-100 align-items-center" style="max-width: 400px;">
            <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100"> 
                <!-- Logo -->
                <div class="text-center mb-5">
                    <a href="../index.html" class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
                </div>

                <!-- Title -->
                <h2 class="fw-bold text-center mb-1">Reset Password</h2>
                <p class="text-center text-muted mb-4">Enter your new password</p>

                <!-- Token Error Message -->
                <?php if (!empty($token_err)): ?>
                    <div class="alert alert-danger text-center mb-4">
                        <?php echo $token_err; ?>
                    </div>
                    <div class="text-center">
                        <a href="forgotPassword.php" class="text-decoration-none text-primary fw-semibold">
                            Request a new reset link
                        </a>
                    </div>
                <?php elseif ($valid_token): ?>
                    <!-- Success Message -->
                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success text-center mb-4">
                            <?php echo $success_msg; ?>
                        </div>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-primary w-100 py-2 fw-semibold">
                                Go to Login
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Reset Password Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=" . urlencode($token); ?>" method="POST">
                            <!-- Password Field -->
                            <div class="form-group mb-4 w-100">
                                <label class="fw-semibold mb-1">New Password</label>
                                <input type="password" name="password" 
                                    class="form-control bg-light-subtle rounded <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                                    placeholder="Enter new password" 
                                    value="<?php echo $password; ?>"
                                    required>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group mb-4 w-100">
                                <label class="fw-semibold mb-1">Confirm New Password</label>
                                <input type="password" name="confirm_password" 
                                    class="form-control bg-light-subtle rounded <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" 
                                    placeholder="Confirm new password" 
                                    value="<?php echo $confirm_password; ?>"
                                    required>
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>

                            <!-- Update Password Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-4">Update Password</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

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
</html>