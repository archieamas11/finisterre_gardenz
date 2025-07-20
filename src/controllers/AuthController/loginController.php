<?php
require_once APPROOT . '/controllers/baseAuthController.php';

class LoginController extends baseAuthController {
    // Login method
    public function login() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if POST request
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = array_map('htmlspecialchars', $_POST);

            // Initialize data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Load validator
            require_once APPROOT . '/helpers/AuthValidation/LoginValidation.php';
            $validator = new ValidateLogin($data);
            $errors = $validator->validateLogin();
            
            // Update data with errors
            $data = array_merge($data, $errors);

            // Check for user/email
            $userData = $this->userModel->get('tbl_users', ['email' => $data['email']]);
            $user = !empty($userData) ? $userData[0] : null;
            // echo '<pre>';
            // var_dump($user);
            // echo '</pre>';
            
            if($user) {
                // Check if account is active
                if(isset($user->status) && $user->status != 'active') {
                    $data['email_err'] = 'Account is not active. Please check your email for confirmation link.';
                    $data['password'] = '';
                }
            } else {
                // User not found
                $data['email_err'] = 'Incorrect Username or Password';
                $data['password'] = '';
            }

            // Make sure errors are empty
            if(!$validator->hasErrors()) {
                // Validated
                // Check and set logged in user
                if(isset($user->password) && password_verify($data['password'], $user->password)) {
                    // Create session and redirect to dashboard
                    // $this->createUserSession($user);
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['name'] = $user->first_name . ' ' . $user->last_name;
                    $_SESSION['role'] = $user->role;
                    $_SESSION['status'] = $user->status;
                    $_SESSION['created'] = $user->created_at;
                    // $this->setFlashMessage('login_success', 'You are now logged in', 'alert alert-success');
                    $this->redirect('Pages/dashboard');
                } else {
                    $data['email_err'] = 'Incorrect Email or Password';
                    $data['password'] = '';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            // Load view if the user clicked login button and no session is set
            if(!empty($_SESSION)) {
                $this->redirect('Pages/dashboard');
            } else {
                $this->view('auth/login', $data);
            }
        }
    }

    public function googleLogin() {
        echo 'Temporary Google Login Page. Will be updated later.'; 
    }
}