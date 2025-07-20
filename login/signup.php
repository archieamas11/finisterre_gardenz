<?php
session_start();

require_once(__DIR__ . "/../include/config.php");
require_once(__DIR__ . "/../include/database.php");
require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../include/google_auth.php");
require_once(__DIR__ . "/../include/notificationsConfig.php");


// Initialize Google client early
$client = initGoogleClient();
$auth_url = $client->createAuthUrl();

// Handle Google callback before any output
if (isset($_GET['code'])) {
    $google_login_result = handleGoogleCallback($mysqli, $client);
    if ($google_login_result) {
        header("location: ../pages/user/index.php");
        exit();
    }
}


$user_firstname = $user_lastname = $user_email = $user_password = $user_confirm_password = "";
$firstname_error = $lastname_error = $email_error = $password_error = $confirm_password_error = "";
$user_username = $username_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validate user firstname
    if (empty(trim($_POST["inputFirstname"]))) {
        $firstname_error = "Please enter your firstname";
    } elseif (!preg_match('/^[a-zA-Z]+$/', trim($_POST["inputFirstname"]))) {
        $firstname_error = "Firstname can only contain letters";
    } else {
        $user_firstname = trim($_POST["inputFirstname"]);
    }

    //validate user lastname
    if (empty(trim($_POST["inputLastname"]))) {
        $lastname_error = "Please enter your lastname";
    } elseif (!preg_match('/^[a-zA-Z]+$/', trim($_POST["inputLastname"]))) {
        $lastname_error = "Lastname can only contain letters";
    } else {
        $user_lastname = trim($_POST["inputLastname"]);
    }

    //validate user email
    if (empty(trim($_POST["inputEmail"]))) {
        $email_error = "Please enter an email";
    } else {
        $sql = "SELECT email FROM tbl_customers WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["inputEmail"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_error = "This email is already taken";
                } else {
                    $user_email = trim($_POST["inputEmail"]);
                }
            } else {
                echo "Something went wrong please try again later.";
            }
            $stmt->close();
        }
    }

    // validate username
    if (empty(trim($_POST["inputUsername"]))) {
        $username_error = "Please enter a username";
    } else {
        $sql = "SELECT username FROM tbl_users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["inputUsername"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_error = "This username is already taken";
                } else {
                    $user_username = trim($_POST["inputUsername"]);
                }
            } else {
                echo "Something went wrong please try again later.";
            }
            $stmt->close();
        }
    }

    //validate user password
    if (empty(trim($_POST["inputPassword"]))) {
        $password_error = "Please enter a password";
    } elseif (strlen(trim($_POST["inputPassword"])) < 6) {
        $password_error = "Password must have at least 6 characters";
    } elseif (!preg_match('/[A-Z]/', trim($_POST["inputPassword"]))) {
        $password_error = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', trim($_POST["inputPassword"]))) {
        $password_error = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', trim($_POST["inputPassword"]))) {
        $password_error = "Password must contain at least one number";
    } elseif (!preg_match('/[\W]/', trim($_POST["inputPassword"]))) {
        $password_error = "Password must contain at least one special character";
    } else {
        $user_password = trim($_POST["inputPassword"]);
    }

    //confirm password
    if (empty(trim($_POST["inputConfirmpassword"]))) {
        $confirm_password_error = "Please confirm password";
    } else {
        $user_confirm_password = trim($_POST["inputConfirmpassword"]);
        if (empty($password_err) && ($user_password != $user_confirm_password)) {
            $confirm_password_error = "Password did not match";
        }
    }

    //Insert the Statement
    if (empty($firstname_error) && empty($lastname_error) && empty($email_error) && empty($password_error) && empty($confirm_password_error)) {
        $verificationToken = bin2hex(random_bytes(32)); // Generate a secure random token for email verification

        $sql = "INSERT INTO tbl_users (username, password, verification_token, verification_token_expires, created_at, updated_at) VALUES (?, ?, ?, NOW() + INTERVAL 1 DAY, NOW(), NOW())";
        if ($stmt = $mysqli->prepare($sql)) {

            $stmt->bind_param("sss", $param_username, $param_password, $param_verification_token);
            $param_username = $user_username;
            $param_password = password_hash($user_password, PASSWORD_DEFAULT);
            $param_verification_token = $verificationToken;

            if ($stmt->execute()) {
                // Store user details in session
                $_SESSION['temp_user_email'] = $user_email;
                $_SESSION['temp_user_firstname'] = $user_firstname;
                $_SESSION['temp_user_lastname'] = $user_lastname;

                $verification_link = WEBROOT . "login/signup-confirmation.php?key=" . urlencode($user_email) . "&token=" . urlencode($verificationToken);

                // Send verification email
                $user_email;
                $subject = "Email Verification - " . SYSTEM_NAME;
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                        <div style='text-align: center; margin-bottom: 30px;'>
                            <h1 style='color: #2c3e50; margin-bottom: 10px;'>" . SYSTEM_NAME . "</h1>
                            <h2 style='color: #34495e; margin-top: 0;'>Email Verification Required</h2>
                        </div>
                        
                        <p>Dear " . htmlspecialchars(ucwords($user_firstname . ' ' . $user_lastname)) . ",</p>
                        
                        <p>Thank you for registering with " . SYSTEM_NAME . "! To complete your account setup and ensure the security of your account, please verify your email address.</p>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . $verification_link . "' 
                               style='background-color: #3498db; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                                Verify Email Address
                            </a>
                        </div>
                        
                        <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
                        <p style='word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;'>
                            " . $verification_link . "
                        </p>
                        
                        <p><strong>Important:</strong> This verification link will expire in 24 hours for security reasons.</p>
                        
                        <p>If you didn't create an account with " . SYSTEM_NAME . ", please ignore this email.</p>
                        
                        <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                        
                        <p style='font-size: 12px; color: #666; text-align: center;'>
                            This is an automated message from " . SYSTEM_NAME . ". Please do not reply to this email.<br>
                            © " . date('Y') . " " . SYSTEM_NAME . ". All rights reserved.
                        </p>
                    </div>
                </body>
                </html>
                ";

                // Send email verification
                sendViaEmail([$user_email], $subject, $message);
                header("location: signup-board.php");
                exit();
            } else {
                echo "Something went wrong please try again later";
            }

            $stmt->close();
        }
    }




    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CemeterEase</title>
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/CemeterEase.svg" type="image/png">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/auth.css">
</head>
<style>
.password-requirements {
    display: none;
    font-size: 1rem;
    margin-top: 5px;
    padding: 8px;
    border-radius: 4px;
    background: #f8f9fa;
}

.password-requirements.show {
    display: block;
}

.requirement {
    display: none;
    color: #dc3545;
    margin: 4px 0;
}

.requirement.active {
    display: block;
}

.is-valid {
    border-color: #198754 !important;
    background-color: #f8fff9 !important;
}

.is-valid ~ .form-control-icon i {
    color: #198754 !important;
}
</style>
<body>
    <section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
        <div class="d-flex flex-column w-100 align-items-center" style="max-width: 500px;">
            <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100"> 
                <!-- Logo -->
                <div class="text-center mb-5">
                    <a href="../index.html" class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
                </div>

                <!-- Title -->
                <h2 class="fw-bold text-center mb-1">Sign Up</h2>
                <p class="text-center text-muted mb-4">Create Cemeterease account</p>

                <form class="form-signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <!-- Google Sign In Button -->
                    <div class="mb-4 w-100">
                        <a href="<?php echo htmlspecialchars($auth_url); ?>" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon" width="20" height="20">
                            <span class="fw-semibold">Sign up with Google</span>
                        </a>
                    </div>

                    <!-- OR Divider -->
                    <div class="d-flex align-items-center mb-4">
                        <hr class="flex-grow-1">
                        <span class="mx-2 text-muted small">or</span>
                        <hr class="flex-grow-1">
                    </div>
                     <div class="row">
                        <!-- First Name Input -->
                        <div class="col-md-6">
                            <div class="form-group mb-3 w-100">
                                <label class="fw-semibold mb-1">First Name</label>
                                <input type="text" id="inputFirstname" class="form-control <?php echo (!empty($firstname_error)) ? 'is-invalid' : ''; ?>" name="inputFirstname" value="<?php echo $user_firstname; ?>" placeholder="e.g. Juan">
                                <span class="error-feedback text-danger" <?php if (empty($firstname_error)) { echo 'hidden'; } ?>><?php echo $firstname_error . "<br>" ?></span>
                            </div>
                        </div>
                        
                        <!-- Last Name Input -->
                        <div class="col-md-6">
                            <div class="form-group mb-3 w-100">
                                <label class="fw-semibold mb-1">Last Name</label>
                                <input type="text" id="inputLastname" class="form-control <?php echo (!empty($lastname_error)) ? 'is-invalid' : ''; ?>" name="inputLastname" value="<?php echo $user_lastname; ?>" placeholder="e.g. Delacruz">
                                <span class="error-feedback text-danger" <?php if (empty($lastname_error)) { echo 'hidden'; } ?>><?php echo $lastname_error . "<br>" ?></span>
                            </div>
                        </div>
                     </div>

                    <!-- username field -->
                    <div class="form-group mb-3 w-100">
                        <label class="fw-semibold mb-1">Username</label>
                        <input type="text" id="inputUsername" class="form-control <?php echo (!empty($username_error)) ? 'is-invalid' : ''; ?>" name="inputUsername" value="<?php echo $user_username; ?>" placeholder="Enter your username">
                        <span class="error-feedback text-danger" <?php if (empty($username_error)) { echo 'hidden'; } ?>><?php echo $username_error . "<br>" ?></span>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group mb-3 w-100">
                        <label class="fw-semibold mb-1">Email address</label>
                        <input type="text" id="inputEmail" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" name="inputEmail" value="<?php echo $user_email; ?>" placeholder="email@gmail.com">
                        <span class="error-feedback text-danger" <?php if (empty($email_error)) { echo 'hidden'; } ?>><?php echo $email_error . "<br>" ?></span>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group mb-3 w-100 position-relative">
                        <label class="fw-semibold mb-1">Password</label>
                        <input type="password" id="inputPassword" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" name="inputPassword" value="<?php echo $user_password; ?>" placeholder="Enter your password">
                        <div class="password-requirements">
                            <p class="requirement length">
                                <i class="bi bi-x-circle"></i> At least 6 characters
                            </p>
                            <p class="requirement uppercase">
                                <i class="bi bi-x-circle"></i> At least one uppercase letter
                            </p>
                            <p class="requirement lowercase">
                                <i class="bi bi-x-circle"></i> At least one lowercase letter
                            </p>
                            <p class="requirement number">
                                <i class="bi bi-x-circle"></i> At least one number
                            </p>
                            <p class="requirement special">
                                <i class="bi bi-x-circle"></i> At least one special character
                            </p>
                        </div>
                        <span class="error-feedback text-danger" <?php if (empty($password_error)) { echo 'hidden'; } ?>><?php echo $password_error . "<br>" ?></span>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group mb-4 w-100 position-relative">
                        <label class="fw-semibold mb-1">Confirm Password</label>
                        <input type="password" id="inputConfirmpassword" class="form-control <?php echo (!empty($confirm_password_error)) ? 'is-invalid' : ''; ?>" name="inputConfirmpassword" value="<?php echo $user_confirm_password; ?>" placeholder="Confirm your password">
                        <span class="error-feedback text-danger" <?php if (empty($confirm_password_error)) { echo 'hidden'; } ?>><?php echo $confirm_password_error . "<br>" ?></span>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a></label>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Create Account</button>
                </form>

                <!-- Login Link -->
                <div class="text-center mt-4">
                    <p class="text-muted">Already have an account?</p>
                    <a href="index.php" class="btn btn-light-subtle w-100 border rounded py-2 fw-semibold">Log In</a>
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

<script>
    document.getElementById('inputConfirmpassword').addEventListener('input', function() {
        const errorSpan = this.parentElement.querySelector('.error-feedback');
        if (errorSpan) {
            errorSpan.setAttribute('hidden', true);
            errorSpan.textContent = '';
        }
    });

    document.getElementById('inputPassword').addEventListener('input', function() {
        const confirmPasswordInput = document.getElementById('inputConfirmpassword');
        const errorSpan = confirmPasswordInput.parentElement.querySelector('.error-feedback');
        if (errorSpan) {
            errorSpan.setAttribute('hidden', true);
            errorSpan.textContent = '';
        }
    });
</script>

<script>
document.getElementById('inputPassword').addEventListener('focus', function() {
    document.querySelector('.password-requirements').classList.add('show');
});

document.getElementById('inputPassword').addEventListener('blur', function() {
    if (!this.value) {
        document.querySelector('.password-requirements').classList.remove('show');
    }
});

document.getElementById('inputPassword').addEventListener('input', function() {
    const errorSpan = this.parentElement.querySelector('.error-feedback');
    const confirmPasswordInput = document.getElementById('inputConfirmpassword');
    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }
    const password = this.value;
    const requirements = [
        {
            regex: /.{6,}/,
            element: document.querySelector('.requirement.length'),
            message: 'At least 6 characters'
        },
        {
            regex: /[A-Z]/,
            element: document.querySelector('.requirement.uppercase'),
            message: 'At least one uppercase letter'
        },
        {
            regex: /[a-z]/,
            element: document.querySelector('.requirement.lowercase'),
            message: 'At least one lowercase letter'
        },
        {
            regex: /[0-9]/,
            element: document.querySelector('.requirement.number'),
            message: 'At least one number'
        },
        {
            regex: /[\W]/,
            element: document.querySelector('.requirement.special'),
            message: 'At least one special character'
        }
    ];

    // Reset classes
    this.classList.remove('is-valid', 'is-invalid');
    confirmPasswordInput.classList.remove('is-valid', 'is-invalid');

    // Hide all requirements first
    requirements.forEach(req => {
        req.element.classList.remove('active');
    });

    // Check requirements
    const allRequirementsMet = requirements.every(req => req.regex.test(password));
    
    if (allRequirementsMet) {
        this.classList.add('is-valid');
        document.querySelector('.password-requirements').classList.remove('show');
        
        // Check confirm password
        if (confirmPasswordInput.value && confirmPasswordInput.value === password) {
            confirmPasswordInput.classList.add('is-valid');
        }
    } else {
        // Show first failed requirement
        const failedRequirement = requirements.find(req => !req.regex.test(password));
        if (failedRequirement) {
            failedRequirement.element.classList.add('active');
        }
    }
});

// Update confirm password validation
document.getElementById('inputConfirmpassword').addEventListener('input', function() {
    const passwordInput = document.getElementById('inputPassword');
    const errorSpan = this.parentElement.querySelector('.error-feedback');
    
    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }
    
    this.classList.remove('is-valid', 'is-invalid');
    
    if (this.value && this.value === passwordInput.value) {
        this.classList.add('is-valid');
    }
});

</script>

</html>