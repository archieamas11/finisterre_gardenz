<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- Reset Password View -->
<section class="d-flex justify-content-center align-items-center vh-100 py-5 px-3">
    <div class="d-flex flex-column w-100 align-items-center">
        <div class="shadow-lg rounded p-4 p-sm-5 bg-white form">
            <div class="row">
                <a href="<?php echo URLROOT; ?>/AuthController/loginController/login" class="btn btn-outline-light text-dark my-3" style="border-radius: 0.5wrem; background-color: rgba(0, 0, 0, 0.05);"><i class="ri-arrow-left-s-line align-bottom"></i> Back to login</a>
                <h3 class="fw-bold">Reset password</h3>
                <p class="text-muted">Please enter your new password.</p>
                <form action="<?php echo URLROOT; ?>/AuthController/resetPasswordController/reset_password/<?php echo $data['token']; ?>" method="post">
                    <!-- Input for password -->
                    <div class="form-group mb-3">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password'] ?? ''; ?>">
                        <span class="invalid-feedback d-block"><?php echo $data['password_err'] ?? ''; ?></span>
                    </div>
                    <!-- Input for confirm password -->
                    <div class="form-group mb-3">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password'] ?? ''; ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary d-block w-100 my-4">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>



