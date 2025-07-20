<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="">

    <!-- Main Section-->
    <section class="d-flex justify-content-center align-items-center vh-100 py-5 px-3 px-md-0">
        <div class="d-flex flex-column w-100 align-items-center">
            <div class="shadow-lg rounded p-4 p-sm-5 bg-white form">
                <h3 class="fw-bold">Login</h3>
                <p class="text-muted">Welcome back!</p>
                <!-- Login Form-->
                <form class="mt-4" action="<?php echo URLROOT; ?>/AuthController/loginController/login" method="post">
                    <div class="form-group">
                        <label class="form-label" for="login-email">Email address</label>
                        <input type="email" name="email" id="login-email" placeholder="name@gmail.com" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="login-password"
                            class="form-label d-flex justify-content-between align-items-center">
                            Password
                            <a href="<?php echo URLROOT; ?>/AuthController/forgotPasswordController/forgotPassword" class="text-muted small ms-2 text-decoration-underline">Forgotten password?</a>
                        </label>
                        <input type="password" name="password" id="login-password" placeholder="Enter your password" class="form-control  <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    </div>
                    <button type="submit" value="Login" class="btn btn-primary d-block w-100 my-4">Login</button>
                </form>
                <!-- / Login Form -->
                <p class="d-block text-center text-muted small">New customer? 
                    <a class="text-muted text-decoration-underline" href="<?php echo URLROOT; ?>/AuthController/registerController/register">Sign up for an account</a>
                </p>
            </div>
        </div>
        <!-- / Login Form-->
    </section>