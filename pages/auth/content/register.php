<?php
// Initialize Google client early
$client = initGoogleClient();
$auth_url = $client->createAuthUrl();

// Handle Google callback before any output
if (isset($_GET['code'])) {
    $google_login_result = handleGoogleCallback($mysqli, $client);
    if ($google_login_result) {
        header("location: ../user/index.php");
        exit();
    }
}

// Handle registration
$registrationResult = $authController->register();
$errors = $registrationResult['errors'];
$formData = $registrationResult['data'];

?>
<section class="d-flex justify-content-center align-items-center min-vh-100 py-5 px-3 px-md-0">
    <div class="d-flex flex-column w-100 align-items-center" style="max-width: 500px;">
        <div class="shadow-lg rounded-4 p-4 p-sm-5 bg-white w-100">
            <!-- Logo -->
            <div class="text-center mb-5">
                <a href="<?php echo WEBROOT; ?>index.php"
                    class="px-2 py-1 bg-dark text-white fw-bold rounded">CEMETEREASE</a>
            </div> <!-- Title -->
            <h2 class="fw-bold text-center mb-1">Sign Up</h2>
            <p class="text-center text-muted mb-4">Create Cemeterease account</p>

            <!-- General Error Display -->
            <?php if (isset($errors['general_error']) && !empty($errors['general_error'])): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <?php echo htmlspecialchars($errors['general_error']); ?>
            </div>
            <?php endif; ?>

            <form class="form-signup" action="index.php?page=register" method="POST">
                <!-- Google Sign In Button -->
                <div class="mb-4 w-100">
                    <a href="<?php echo htmlspecialchars($auth_url); ?>"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon"
                            width="20" height="20">
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
                            <input type="text" id="inputFirstname"
                                class="form-control <?php echo (!empty($errors['firstname_error'])) ? 'is-invalid' : ''; ?>"
                                name="inputFirstname" value="<?php echo $formData['user_firstname']; ?>"
                                placeholder="e.g. Juan">
                            <span class="error-feedback text-danger"
                                <?php if (empty($errors['firstname_error'])) { echo 'hidden'; } ?>><?php echo $errors['firstname_error'] . "<br>" ?></span>
                        </div>
                    </div>

                    <!-- Last Name Input -->
                    <div class="col-md-6">
                        <div class="form-group mb-3 w-100">
                            <label class="fw-semibold mb-1">Last Name</label>
                            <input type="text" id="inputLastname"
                                class="form-control <?php echo (!empty($errors['lastname_error'])) ? 'is-invalid' : ''; ?>"
                                name="inputLastname" value="<?php echo $formData['user_lastname']; ?>"
                                placeholder="e.g. Delacruz">
                            <span class="error-feedback text-danger"
                                <?php if (empty($errors['lastname_error'])) { echo 'hidden'; } ?>><?php echo $errors['lastname_error'] . "<br>" ?></span>
                        </div>
                    </div>
                </div> <!-- username field -->
                <div class="form-group mb-3 w-100">
                    <label class="fw-semibold mb-1">Username</label>
                    <input type="text" id="inputUsername"
                        class="form-control <?php echo (!empty($errors['username_error'])) ? 'is-invalid' : ''; ?>"
                        name="inputUsername" value="<?php echo $formData['user_username']; ?>"
                        placeholder="Enter your username">
                    <span class="error-feedback text-danger"
                        <?php if (empty($errors['username_error'])) { echo 'hidden'; } ?>><?php echo $errors['username_error'] . "<br>" ?></span>
                </div> <!-- Email Field -->
                <div class="form-group mb-3 w-100">
                    <label class="fw-semibold mb-1">Email address</label>
                    <input type="email" id="inputEmail"
                        class="form-control <?php echo (!empty($errors['email_error'])) ? 'is-invalid' : ''; ?>"
                        name="inputEmail" value="<?php echo $formData['user_email']; ?>" placeholder="email@gmail.com">
                    <span class="error-feedback text-danger"
                        <?php if (empty($errors['email_error'])) { echo 'hidden'; } ?>><?php echo $errors['email_error'] . "<br>" ?></span>
                </div> <!-- Password Field -->
                <div class="form-group mb-3 w-100 position-relative">
                    <label class="fw-semibold mb-1">Password</label>
                    <input type="password" id="inputPassword"
                        class="form-control <?php echo (!empty($errors['password_error'])) ? 'is-invalid' : ''; ?>"
                        name="inputPassword" value="<?php echo $formData['user_password']; ?>"
                        placeholder="Enter your password">
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
                    <span class="error-feedback text-danger"
                        <?php if (empty($errors['password_error'])) { echo 'hidden'; } ?>><?php echo $errors['password_error'] . "<br>" ?></span>
                </div> <!-- Confirm Password Field -->
                <div class="form-group mb-4 w-100 position-relative">
                    <label class="fw-semibold mb-1">Confirm Password</label>
                    <input type="password" id="inputConfirmpassword"
                        class="form-control <?php echo (!empty($errors['confirm_password_error'])) ? 'is-invalid' : ''; ?>"
                        name="inputConfirmpassword" value="<?php echo $formData['user_confirm_password']; ?>"
                        placeholder="Confirm your password">
                    <span class="error-feedback text-danger"
                        <?php if (empty($errors['confirm_password_error'])) { echo 'hidden'; } ?>><?php echo $errors['confirm_password_error'] . "<br>" ?></span>
                </div> <!-- Terms Checkbox -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">I agree to the <a href="#"
                            class="text-decoration-none">Terms of Service</a> and <a href="#"
                            class="text-decoration-none">Privacy Policy</a></label>
                    <span class="error-feedback text-danger"
                        <?php if (empty($errors['terms_error'])) { echo 'hidden'; } ?>><?php echo $errors['terms_error'] . "<br>" ?></span>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Create Account</button>
            </form>
            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="text-muted">Already have an account?</p>
                <a href="index.php?page=login" class="btn btn-light-subtle w-100 border rounded py-2 fw-semibold">Log
                    In</a>
            </div>
        </div>
    </div>
</section>