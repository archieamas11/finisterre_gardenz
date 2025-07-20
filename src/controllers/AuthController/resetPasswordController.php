<?php
require_once APPROOT . '/controllers/baseAuthController.php';

class resetPasswordController extends baseAuthController {
    public function reset_password($token = null) {
        if($token === null) {
            $this->redirect('AuthController/loginController/login');
            return;
        }

        // Verify token validity before showing the form
        $userData = $this->userModel->get('tbl_users', ['reset_token' => $token], ['token_expiry' => 'NOW()']);
        $user = !empty($userData) ? $userData[0] : null;
        if(!$user) {
            $this->setFlashMessage('password_error', 'Invalid or expired reset token', 'alert alert-danger');
            $this->redirect('AuthController/loginController/login');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'token' => $token,
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load validator
            require_once APPROOT . '/helpers/AuthValidation/LoginValidation.php';
            $validator = new ValidateLogin($data);
            $errors = $validator->validatePasswordReset();
            
            // Update data with errors
            $data = array_merge($data, $errors);

            if(empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Update user password
                $updated = $this->userModel->update('tbl_users', ['password' => $data['password']], ['reset_token' => $token]);
                if($updated) {
                    // Reset token
                    $this->userModel->update('tbl_users', ['reset_token' => null, 'token_expiry' => null], ['reset_token' => $token]);
                    $this->setFlashMessage('password_success', 'Your password has been reset! You can now log in.', 'alert alert-success');
                    // Redirect to login page
                    $this->redirect('AuthController/loginController/login');
                } else {
                    // If update fails, flash error message will display and redirect to reset password page
                    $this->setFlashMessage('password_error', 'Something went wrong updating your password', 'alert alert-danger');
                    $this->redirect('AuthController/resetPasswordController/reset_password/' . $token);
                }
            } else {
                $this->view('auth/reset_password', $data);
            }
        } else {
            $data = [
                'token' => $token,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('auth/reset_password', $data);
        }
    }
}