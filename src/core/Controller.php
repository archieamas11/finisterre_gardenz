<?php
class Controller {
    // Load model
    public function model($model) {
        // Require model file
        require_once 'src/models/' . $model . '.php';
        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if(file_exists('src/views/' . $view . '.php')) {
            require_once 'src/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }

    public function content($view) {
        // Check for view file
        if(file_exists('src/views/' . $view . '.php')) {
            require_once 'src/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }

    // Redirect to another page
    public function redirect($page) {
        header('location: ' . URLROOT . '/' . $page);
    }

    // Flash message helper
    public function setFlashMessage($name, $message, $class = 'alert alert-success') {
        if(!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        $_SESSION['flash_messages'][$name] = [
            'message' => $message,
            'class' => $class
        ];
    }

    // Get flash message
    public function getFlashMessage($name) {
        if(isset($_SESSION['flash_messages'][$name])) {
            $flash = $_SESSION['flash_messages'][$name];
            unset($_SESSION['flash_messages'][$name]);
            return $flash;
        }
        
        return null;
    }
}