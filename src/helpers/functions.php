<?php
/**
 * Helper functions for the application
 */

/**
 * Redirect to a specific page
 * 
 * @param string $page The page to redirect to
 * @return void
 */
function redirect($page) {
    header('Location: ' . URLROOT . '/' . $page);
    exit;
}

/**
 * Flash message helper
 * 
 * @param string $name Name of the session variable
 * @param string $message Message to display
 * @param string $class CSS class for styling
 * @return void
 */
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            
            if(!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
 * Check if user is logged in
 * 
 * @return boolean
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
} 