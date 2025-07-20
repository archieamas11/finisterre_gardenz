<?php
require_once APPROOT . '/helpers/BaseValidation.php';

class ValidateRegister extends BaseValidation {
    public function validateRegistration() {
        $this->validateFirstName()
             ->validateLastName()
            //  ->validateNickname()
             ->validateEmail()
             ->validatePassword()
             ->validateConfirmPassword();

        return $this->errors;
    }

    private function validateFirstName() {
        if(empty($this->data['fname'])) {
            $this->errors['fname_err'] = 'Please enter First Name';
        } else if(strpos($this->data['fname'], '  ') !== false) {
            $this->errors['fname_err'] = 'Double whitespace is not allowed in First Name';
        }
        return $this;
    }

    private function validateLastName() {
        if(empty($this->data['lname'])) {
            $this->errors['lname_err'] = 'Please enter Last Name';
        } else if(strpos($this->data['lname'], '  ') !== false) {
            $this->errors['lname_err'] = 'Double whitespace is not allowed in Last Name';
        }
        return $this;
    }

    // private function validateNickname() {
    //     if(empty($this->data['nname'])) {
    //         $this->errors['nname_err'] = 'Please enter Nickname';
    //     } else if(strpos($this->data['nname'], '  ') !== false) {
    //         $this->errors['nname_err'] = 'Double whitespace is not allowed in Nickname';
    //     }
    //     return $this;
    // }
}