<?php require APPROOT . '/views/inc/navbar.php'; ?>
<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container py-5">
    <?php printSession(); ?>
    <div class="row align-items-center">
        <div class="col-lg-6 text-center text-lg-start">
            <h1 class="display-4 fw-bold mb-4"><?php echo $data['description']; ?></h1>
            <p class="lead mb-4">Track shared expenses, split bills with friends, and manage group finances with ease. Never worry about who owes what again!</p>
            <?php if(!isset($_SESSION['id'])) : ?>
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-start justify-content-center">
                    <a href="<?php echo URLROOT; ?>/AuthController/registerController/register" class="btn btn-primary btn-lg px-4 me-sm-3">Get Started</a>
                    <a href="<?php echo URLROOT; ?>/AuthController/loginController/login" class="btn btn-outline-dark btn-lg px-4">Sign In</a>
                </div>
            <?php else : ?>
                <div class="alert alert-success">
                    <h4 class="alert-heading">Welcome back, <?php echo $_SESSION['name']; ?>!</h4>
                    <p class="mb-0">Ready to manage your shared expenses?</p>
                    <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-success mt-3">Go to Dashboard</a>
                </div>
        <?php endif; ?>
        </div>
        <div class="col-lg-6 d-none d-lg-block text-center">
            <svg class="img-fluid" width="500" height="400" viewBox="0 0 500 400">
                <rect x="50" y="50" width="400" height="300" fill="#f8f9fa" rx="10"/>
                <circle cx="250" cy="200" r="100" fill="#0d6efd" opacity="0.1"/>
                <rect x="100" y="150" width="300" height="40" fill="#0d6efd" rx="5" opacity="0.2"/>
                <rect x="100" y="210" width="200" height="40" fill="#0d6efd" rx="5" opacity="0.3"/>
            </svg>
        </div>
    </div>
</div>
</div>

