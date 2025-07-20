<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- Main Section-->
<section class="d-flex justify-content-center align-items-start vh-100 py-5 px-3 px-md-0">

    <!-- Login Form-->
    <div class="d-flex flex-column w-100 align-items-center">

        <!-- Logo-->
        <!-- <a href="./index.html" class="d-table mt-5 mb-4 mx-auto">
            <div class="d-flex align-items-center">
                <svg class="f-w-5 me-2 text-primary d-flex align-self-center lh-1" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 203.58 182">
                    <path
                        d="M101.66,41.34C94.54,58.53,88.89,72.13,84,83.78A21.2,21.2,0,0,1,69.76,96.41,94.86,94.86,0,0,0,26.61,122.3L81.12,0h41.6l55.07,123.15c-12-12.59-26.38-21.88-44.25-26.81a21.22,21.22,0,0,1-14.35-12.69c-4.71-11.35-10.3-24.86-17.53-42.31Z"
                        fill="currentColor" fill-rule="evenodd" fill-opacity="0.5" />
                    <path
                        d="M0,182H29.76a21.3,21.3,0,0,0,18.56-10.33,63.27,63.27,0,0,1,106.94,0A21.3,21.3,0,0,0,173.82,182h29.76c-22.66-50.84-49.5-80.34-101.79-80.34S22.66,131.16,0,182Z"
                        fill="currentColor" fill-rule="evenodd" />
                </svg>
                <span class="fw-black text-uppercase tracking-wide fs-6 lh-1">Apollo</span>
            </div>
        </a> -->
        <!-- Logo-->

        <div class="shadow-lg rounded p-4 p-sm-5 bg-white form mb-4">
            <h3 class="fw-bold mb-3">Register</h3>
            <a href="<?php echo URLROOT; ?>/AuthController/loginController/googleLogin" class="btn btn-google d-block mb-2 text-white"><i class="ri-google-fill align-bottom"></i> Login with Google</a>
            <span class="text-muted text-center d-block fw-bolder my-4">OR</span>
            <!-- Register Form-->
            <form class="mt-4" action="<?php echo URLROOT; ?>/AuthController/registerController/register" method="post">
                <!-- first name input -->
                <div class="form-group mb-2">
                    <label class="form-label" for="register-fname">First name</label>
                    <input type="text" name="fname" placeholder="Enter your first name" class="form-control <?php echo (!empty($data['fname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['fname']; ?>">
                    <span class="invalid-feedback"><?php echo $data['fname_err']; ?></span>
                </div>
                <!-- last name input -->
                <div class="form-group mb-2">
                    <label class="form-label" for="register-lname">Last name</label>
                    <input type="text" name="lname" placeholder="Enter your last name" class="form-control <?php echo (!empty($data['lname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['lname']; ?>">
                    <span class="invalid-feedback"><?php echo $data['lname_err']; ?></span>
                </div>
                <!-- email input -->
                <div class="form-group mb-2">
                    <label class="form-label" for="register-email">Email address</label>
                    <input type="email" name="email" placeholder="name@gmail.com" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <!-- password input -->
                <div class="form-group mb-2">
                    <label class="form-label" for="register-password">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
                <!-- confirm password input -->
                <div class="form-group mb-2">
                    <label class="form-label" for="register-confirm-password">Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Enter your password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                </div>
                <button type="submit" class="btn btn-primary d-block w-100 my-4">Sign Up</button>
            </form>
            <!-- / Register Form-->

            <p class="d-block text-center text-muted small">Already registered? <a class="text-muted text-decoration-underline" href="<?php echo URLROOT; ?>/AuthController/loginController/login">Login here.</a></p>
        </div>
    </div>
    <!-- / Login Form-->

</section>