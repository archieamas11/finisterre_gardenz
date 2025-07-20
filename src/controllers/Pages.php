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
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
            return;
        }

        $tab = $_GET['tab'] ?? 'dashboard'; // default tab
        $content = '';
        $dashboard = '';
        $deceased = ''; 

        switch ($tab) {
            case 'dashboard':
                $content = 'src/views/pages/admin/dashboard.php';
                $dashboard = 'active';
                break;
            case 'deceased':
                $content = 'src/views/pages/admin/deceased.php';
                $deceased = 'active';
                break;
            case 'logout':
                session_destroy();
                redirect('');
                break;
            default:
                $content = 'src/views/pages/admin/dashboard.php';
        }
    
        $data = [
            'title' => 'Dashboard - ' . SITENAME,
            'user_id' => $_SESSION['user_id'],
            'user_email' => $_SESSION['user_email'],
            'name' => $_SESSION['name'],
            'role' => $_SESSION['role'],
            'status' => $_SESSION['status'],
            'created' => $_SESSION['created'],
            'content' => $content,
            'dashboard' => $dashboard,
            'deceased' => $deceased
        ];

        $this->identifyUserRole($data);
    }
    
    private function identifyUserRole($data) {
        if ($_SESSION['role'] === 'admin') {
            $this->view('pages/admin/template/adminTemplate', $data); // admin template
        } else {
            $this->view('pages/users/template/userTemplate', $data); // user template
        }
    }
}