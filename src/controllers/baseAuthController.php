<?php
abstract class BaseAuthController extends Controller {
    protected $userModel;
    
    public function __construct() {
        // Load User model
        // $this->userModel = $this->model('User');
        $this->userModel = $this->model('Model');
    }
}