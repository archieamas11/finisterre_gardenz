<?php
require_once APPROOT . '/controllers/baseAuthController.php';

class RegisterController extends baseAuthController {
    public function register() {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            // Initialize data
            $data = [
                'fname' => trim($_POST['fname']),
                'lname' => trim($_POST['lname']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'fname_err' => '',
                'lname_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
    
            // Load validator
            require_once APPROOT . '/helpers/AuthValidation/RegisterValidation.php';
            $validator = new ValidateRegister($data);
            $errors = $validator->validateRegistration();


            $emailExist = $this->userModel->get('tbl_users', ['email' => $data['email']]);
            $user = !empty($emailExist) ? $emailExist[0] : null;
            // Check if email already exists
            if (!isset($errors['email_err']) && $user) {
                $errors['email_err'] = 'Email is already taken';
            }
    
            // Merge the errors with data
            $data = array_merge($data, $errors);
    
            // If no errors, proceed with registration
            if (empty($errors)) {
                // Hash the password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    
                // Generate confirmation token
                $token = bin2hex(random_bytes(50));
    
                // Prepare the user data for insertion
                $userData = [
                    'first_name' => $data['fname'],
                    'last_name' => $data['lname'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'token' => $token,
                ];
    
                // Register user
                $insertUser = $this->userModel->insert('tbl_users', $userData);
                if ($insertUser) {
                    // Send confirmation email
                    if ($this->sendConfirmationEmail($data['email'], $token, $data['fname'] . ' ' . $data['lname'])) {
                        $this->setFlashMessage('register_success', 'Registration successful! Please check your email for confirmation.', 'alert alert-success');
                        $this->view('auth/confirmAccount');
                    } else {
                        $this->setFlashMessage('register_error', 'Something went wrong sending the confirmation email.', 'alert alert-danger');
                        $this->view('auth/register', $data);
                    }
                } else {
                    // Database insert failed
                    $this->setFlashMessage('register_error', 'Something went wrong during registration.', 'alert alert-danger');
                    $this->view('auth/register', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }
        } else {
            // Init data for GET request
            $data = [
                'fname' => '',
                'lname' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'fname_err' => '',
                'lname_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            // Load view for the register page
            $this->view('auth/register', $data);
        }
    }

    public function confirm($token) {
        if (empty($token)) {
            $this->setFlashMessage('confirm_error', 'Invalid or expired confirmation link.', 'alert alert-danger');
            $this->redirect('AuthController/loginController/login');
            return;
        }
    
        $userData = $this->userModel->get('tbl_users', ['token' => $token]);
        $user = !empty($userData) ? $userData[0] : null;
    
        if ($user && $user->token_expiry && strtotime($user->token_expiry) > time()) {
            $updated = $this->userModel->update('tbl_users', [
                'status' => 'active',
                'token' => null,
                'token_expiry' => null
            ], ['token' => $token]);
    
            if ($updated) {
                $this->setFlashMessage('confirm_success', 'Your account has been activated! You can now log in.', 'alert alert-success');
            } else {
                $this->setFlashMessage('confirm_error', 'Something went wrong while activating your account.', 'alert alert-danger');
            }
        } else {
            $this->setFlashMessage('confirm_error', 'Invalid or expired confirmation link.', 'alert alert-danger');
        }
        $this->redirect('AuthController/loginController/login');
    }

    // Send confirmation email
    protected function sendConfirmationEmail($email, $token,  $name) {
        require_once APPROOT . '/helpers/EmailSender.php';
            
        $subject = 'Confirm Your Account - ' . SITENAME;
        $confirmLink = URLROOT . '/AuthController/registerController/confirm/' . $token;
            
        $body = '<h2>Welcome to ' . SITENAME . '!</h2>';
        $body .= '<p>Hello ' . $name . ',</p>';
        $body .= '<p>Thank you for registering with us. Please click the link below to confirm your email address:</p>';
        $body .= '<p><a href="' . $confirmLink . '">Confirm Your Account</a></p>';
        $body .= '<p>If you did not register on our site, please ignore this email.</p>';
        $body .= '<p>Regards,<br>' . SITENAME . ' Team</p>';
            
        $emailSender = new EmailSender();
        return $emailSender->send($email, $subject, $body);
    }
}