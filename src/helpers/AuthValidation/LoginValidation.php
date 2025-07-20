<?php
require_once APPROOT . '/helpers/BaseValidation.php';


class ValidateLogin extends BaseValidation {
    public function validateLogin() {
        $this->validateLoginEmail()
             ->validateLoginPassword();

        return $this->errors;
    }

    public function validatePasswordReset() {
        $this->validatePassword()->validateConfirmPassword();

        return $this->errors;
    }

    public function validateForgotPassword() {
        $this->validateEmail();
        return $this->errors;
    }

    private function validateLoginEmail() {
        if(empty($this->data['email'])) {
            $this->errors['email_err'] = 'Email is required';
        } else if(strpos($this->data['email'], '  ') !== false) {
            $this->errors['email_err'] = 'Double whitespace is not allowed';
        }
        return $this;
    }

    private function validateLoginPassword() {
        if(empty($this->data['password'])) {
            $this->errors['password_err'] = 'Password is required';
        }
        return $this;
    }
}