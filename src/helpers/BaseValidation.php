<?php
abstract class BaseValidation {
    protected $data;
    protected $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    protected function validatePassword() {
        if(empty($this->data['password'])) {
            $this->errors['password_err'] = 'Please enter password';
        } else if(strlen($this->data['password']) < 8) {
            $this->errors['password_err'] = 'Password must be at least 8 characters long';
        }
        return $this;
    }

    protected function validateConfirmPassword() {
        if(empty($this->data['confirm_password'])) {
            $this->errors['confirm_password_err'] = 'Please confirm password';
        } else if($this->data['password'] != $this->data['confirm_password']) {
            $this->errors['confirm_password_err'] = 'Passwords do not match';
        }
        return $this;
    }

    protected function validateEmail() {
        if(empty($this->data['email'])) {
            $this->errors['email_err'] = 'Please enter email';
        } else if(!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email_err'] = 'Please enter a valid email';
        }
        return $this;
    }
}