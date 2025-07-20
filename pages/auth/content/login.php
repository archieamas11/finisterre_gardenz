<?php
// Include AuthController
require_once __DIR__ . '/../../../controller/auth_controller.php';

// Initialize AuthController
$authController = new AuthController($mysqli);

// Handle login
$loginResult = $authController->login();
$errors = $loginResult['errors'];
$formData = $loginResult['data'];

// Extract individual variables for backward compatibility with the form
$login_email_error = $errors['email_error'];
$login_password_error = $errors['password_error'];
$general_error = $errors['general_error'];

$login_email = $formData['login_email'];
$login_password = $formData['login_password'];

?>

<!-- Google Login -->
<?php
$client = initGoogleClient();
$auth_url = $client->createAuthUrl();

// Handle Google callback
$google_login_result = handleGoogleCallback($mysqli, $client);
if ($google_login_result) {
    header("location: " . ($google_login_result == "admin" ? "../admin/" : "../user/") . "index.php");
    exit();
}
?>

<!-- <script src="../assets/static/js/initTheme.js"></script> -->
<section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
    <div class="d-flex flex-column w-100 align-items-center" style="max-width: 400px;">
        <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100">
            <!-- Logo -->
            <div class="text-center mb-5">
                <a href="<?php echo WEBROOT; ?>index.php"
                    class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
            </div> <!-- Title -->
            <h2 class="fw-bold text-center mb-1">Welcome Back</h2>
            <p class="text-center text-muted mb-4">Sign in with your login details</p>

            <!-- General Error Display -->
            <?php if (!empty($general_error)): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <?php echo htmlspecialchars($general_error); ?>
            </div>
            <?php endif; ?>

            <form class="form-signin" action="index.php?page=login" method="POST" autocomplete="off">
                <!-- Google Sign In Button -->
                <div class="mb-4 w-100">
                    <a href="<?php echo htmlspecialchars($auth_url); ?>"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon"
                            width="20" height="20">
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
                        placeholder="email@example.com" value="<?php echo $login_email; ?>" autocomplete="off"
                        autofocus>
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
                        <label class="form-check-label" for="remember">Remember email</label>
                    </div>
                    <a href="forgotPassword.php" class="text-decoration-none small text-primary fw-semibold">Forgot
                        password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Log In</button>
            </form> <!-- Sign Up Link -->
            <div class="text-center mt-4">
                <p class="text-muted">Don't have an account?</p>
                <a href="index.php?page=register"
                    class="btn btn-light-subtle w-100 border rounded py-2 fw-semibold">Sign up</a>
            </div>
        </div>
    </div>
</section>