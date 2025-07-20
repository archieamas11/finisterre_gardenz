<?php 
require_once APPROOT . '/controllers/baseAuthController.php';

class ForgotPasswordController extends baseAuthController {
    public function forgotPassword() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];

            // Load validator
            require_once APPROOT . '/helpers/AuthValidation/LoginValidation.php';
            $validator = new ValidateLogin($data);
            $errors = $validator->validateForgotPassword();
            
            // Update data with errors
            $data = array_merge($data, $errors);
            
            if(empty($errors)) {
                // Check email exists
                $userData = $this->userModel->get('tbl_users', ['email' => $data['email']]);
                $user = !empty($userData) ? $userData[0] : null;
                if(!$user) {
                    $data['email_err'] = 'No account found with that email';
                }
            }

            if(empty($data['email_err'])) {
                // Generate and save reset token
                $token = bin2hex(random_bytes(50));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $updated = $this->userModel->update('tbl_users', ['reset_token' => $token, 'token_expiry' => $expiry], ['email' => $data['email']]);
                if($updated) {
                    // Send password reset email
                    require_once APPROOT . '/helpers/EmailSender.php';
                    
                    $subject = 'Password Reset Request - ' . SITENAME;
                    $resetLink = URLROOT . '/AuthController/resetPasswordController/reset_password/' . $token;
                    
                    $body = '<h2>Password Reset Request</h2>';
                    $body .= '<p>You have requested to reset your password. Please click the link below to proceed:</p>';
                    $body .= '<p><a href="' . $resetLink . '">Reset Your Password</a></p>';
                    $body .= '<p>If you did not request this password reset, please ignore this email.</p>';
                    $body .= '<p>Regards,<br>' . SITENAME . ' Team</p>';
                    
                    $emailSender = new EmailSender();
                    if($emailSender->send($data['email'], $subject, $body)) {
                        $this->setFlashMessage('reset_success', 'Password reset link has been sent to your email', 'alert alert-success');
                        $this->redirect('AuthController/loginController/login');
                    } else {
                        $this->setFlashMessage('reset_error', 'Something went wrong sending the email', 'alert alert-danger');
                        $this->view('auth/forgot_password', $data);
                    }
                } else {
                    $this->setFlashMessage('reset_error', 'Something went wrong generating reset token', 'alert alert-danger');
                    $this->view('auth/forgot_password', $data);
                }
            } else {
                $this->view('auth/forgot_password', $data);
            }
        } else {
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            $this->view('auth/forgot_password', $data);
        }
    }
}
