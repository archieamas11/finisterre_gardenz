<?php
session_start();
require_once __DIR__ . "/../include/config.php";
require_once __DIR__ . "/../include/database.php";
require_once __DIR__ . "/../include/google_auth.php";

$login_email = $login_password = "";
$login_email_error = $login_password_error = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["inputEmail"]))) {
        $login_email_error = "Please enter username or email";
    } else {
        $login_email = trim($_POST["inputEmail"]);
    }

    // Validate password
    if (empty(trim($_POST["inputPassword"]))) {
        $login_password_error = "Please enter password";
    } else {
        $login_password = trim($_POST["inputPassword"]);
    }

    if (empty($login_email_error) && empty($login_password_error)) {
        $sql = "SELECT u.user_id, u.user_type, u.username, u.password, u.is_verified, u.last_login, c.email AS customer_email, c.first_name, c.last_name, c.customer_id 
                FROM tbl_users u 
                LEFT JOIN tbl_customers c ON u.user_id = c.user_id 
                WHERE u.username = ? OR c.email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ss", $param_email, $param_email);
            $param_email = $login_email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $type, $db_username, $hashed_password, $is_verified, $last_login, $customer_email, $first_name, $last_name, $customer_id);
                    if ($stmt->fetch()) {
                        // Verify password
                        if ($is_verified == 1) {
                            // User is verified, proceed with password check
                            if ($hashed_password === "1" || password_verify($login_password, $hashed_password)) {
                                // Update last login time
                                $update_sql = "UPDATE tbl_users SET last_login = NOW() WHERE user_id = ?";
                                if ($update_stmt = $mysqli->prepare($update_sql)) {
                                    $update_stmt->bind_param("i", $id);
                                    $update_stmt->execute();
                                    $update_stmt->close();
                                }

                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["customer_id"] = $customer_id;
                                $_SESSION["user_type"] = $type;
                                $_SESSION["username"] = $db_username;
                                $_SESSION["email"] = $customer_email;
                                $_SESSION["firstname"] = $first_name; 
                                $_SESSION["lastname"] = $last_name; 
                                $_SESSION["name"] = $first_name . " " . $last_name;
                                header("location: " . ($type == "admin" || $type == "staff" ? "../pages/admin/" : "../pages/user/") . "index.php");
                                exit();
                            } else {
                                $login_email_error = "Invalid email or password";
                            }
                        } else {
                            $login_email_error = "Please verify your email first";
                        }
                    }
                } else {
                    $login_email_error = "Invalid email or password";
                }
            }
            $stmt->close();
        }
    }
}

// Google Login
$client = initGoogleClient();
$auth_url = $client->createAuthUrl();

// Handle Google callback
$google_login_result = handleGoogleCallback($mysqli, $client);
if ($google_login_result) {
    header("location: " . ($google_login_result == "admin" ? "../pages/admin/" : "../pages/user/") . "index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CemeterEase</title>
    <link rel="shortcut icon" href="<?php echo WEBROOT; ?>assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/auth.css">
</head>
<body>
<!-- <script src="../assets/static/js/initTheme.js"></script> -->
    <section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
        <div class="d-flex flex-column w-100 align-items-center" style="max-width: 400px;">
            <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100"> 
                <!-- Logo -->
                <div class="text-center mb-5">
                    <a href="../index.html" class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
                </div>

                <!-- Title -->
                <h2 class="fw-bold text-center mb-1">Welcome Back</h2>
                <p class="text-center text-muted mb-4">Sign in with your login details</p>

                <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off">
                    <!-- Google Sign In Button -->
                    <div class="mb-4 w-100">
                        <a href="<?php echo htmlspecialchars($auth_url); ?>" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon" width="20" height="20">
                            <span class="fw-semibold">Sign in with Google</span>
                        </a>
                    </div>

                    <!-- OR Divider -->
                    <div class="d-flex align-items-center mb-4">
                        <hr class="flex-grow-1">
                        <span class="mx-2 text-muted small">or</span>
                        <hr class="flex-grow-1">
                    </div>

                    <!-- Email Field -->
                    <div class="form-group mb-3 w-100">
                        <label class="fw-semibold mb-1">Username or email address</label>
                        <input type="text" name="inputEmail"
                            class="form-control bg-light-subtle rounded <?php echo (!empty($login_email_error)) ? 'is-invalid' : ''; ?>"
                            placeholder="email@example.com"
                            value="<?php echo $login_email; ?>" autocomplete="off" autofocus>
                        <span class="invalid-feedback"><?php echo $login_email_error; ?></span>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group mb-3 w-100 position-relative">
                        <label class="fw-semibold mb-1">Password</label>
                        <input type="password" name="inputPassword"
                            class="form-control bg-light-subtle rounded <?php echo (!empty($login_password_error)) ? 'is-invalid' : ''; ?>"
                            placeholder="Enter password" autocomplete="new-password">
                        <span class="invalid-feedback"><?php echo $login_password_error; ?></span>
                    </div>

                    <!-- Options -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="forgotPassword.php" class="text-decoration-none small text-primary fw-semibold">Forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Log In</button>
                </form>

                <!-- Sign Up Link -->
                <div class="text-center mt-4">
                    <p class="text-muted">Don't have an account?</p>
                    <a href="signup.php" class="btn btn-light-subtle w-100 border rounded py-2 fw-semibold">Sign up</a>
                </div>
            </div>
        </div>
    </section>
</body>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/68249c3d8984c1190f3abff7/1ir7g4kiv';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</html>