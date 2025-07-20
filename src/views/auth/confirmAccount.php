<?php require_once APPROOT . '/views/inc/header.php'; ?>
<!-- Account confirmation page -->
<div class="d-flex flex-column align-items-center">
    <div class="alert alert-info" role="alert">
        Thank you for registering with us. Please check your email for confirmation link.
    </div>
    <a href="<?php echo URLROOT; ?>/AuthController/loginController/login" class="btn btn-primary">Go to login page</a>
</div>
