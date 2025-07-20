<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- Forgot Password View -->
<section class="d-flex justify-content-center align-items-center vh-100 py-5 px-3 px-md-0">
    <div class="d-flex flex-column w-100 align-items-center">
        <div class="shadow-lg rounded p-4 p-sm-5 bg-white form">
            <!-- back to login button -->
            <a href="<?php echo URLROOT; ?>/AuthController/loginController/login" class="btn btn-outline-light text-dark my-3" style="border-radius: 0.5wrem; background-color: rgba(0, 0, 0, 0.05);"><i class="ri-arrow-left-s-line align-bottom"></i> Back to login</a>
            <h3 class="fw-bold">Forgotten password</h3>
            <p class="text-muted">Please enter your email below and we will send you a secure link to reset your password.</p>
            <!-- Form-->
            <form class="mt-4" action="<?php echo URLROOT; ?>/AuthController/forgotPasswordController/forgotPassword" method="post">
                <div class="form-group">
                    <label for="forgot-password" class="form-label">Email address</label>
                    <input type="email" name="email" placeholder="name@gmail.com" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email'] ?? ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                </div>
                <button type="submit" class="btn btn-primary d-block w-100 my-4">Send Reset Link</button>
            </form>
        </div>
    </div>
</section>