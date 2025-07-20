<?php
session_start();
require_once "../models/Model.php";
require_once "../views/tabs/delete_record.php";
require_once "../views/tabs/update_record.php";
require_once "../views/tabs/retrieve_record.php";

$model = new Model();

$content = '../views/dashboard.php';

$view = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

switch ($view) {
    case 'dashboard':
        $title     = "Dashboard";
        $content   = '../views/dashboard.php';
        $dashboard = 'active';
        break;

    case 'insert':
        $title   = "New Record";
        $content = '../views/tabs/insert_record.php';
        $record  = 'active';
        break;

    case 'logout':
        $title    = "Logout";
        $content  = 'logout.php';
        $activity = 'active';
        break;

    case 'midterm':
        $title    = "Midterm";
        $content  = '../views/tabs/midterm.php';
        $midterm   = 'active';
        break;

    default:
        $title     = "Dashboard";
        $content   = '../views/dashboard.php';
        $dashboard = 'active';
}

include "../views/template/template.php";

?>


<?php
class Pages extends Controller {
    public function __construct() {
        // Initialize session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Default method - landing page
    public function index() {
        $data = [
            'title' => SITENAME,
            'description' => 'Split bills effortlessly with friends and family'
        ];
        
        $this->view('pages/homepage/index', $data);
    }

    public function dashboard() {
        // // Check if user is logged in
        // if(!isset($_SESSION['user_id'])) {
        //     redirect('users/login');
        //     return;
        // }
        // Get user data for the dashboard
        $data = [
            'title' => 'Dashboard - ' . SITENAME,   
                    // Redirect to Pages controller's dashboard method
                    $data['user_id'] = $_SESSION['user_id'],
                    $data['user_email'] = $_SESSION['user_email'],
                    $data['name'] = $_SESSION['name']
        ];
        
        $this->view('pages/admin/dashboard', $data);
    }
}
