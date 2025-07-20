<?php
class App {
    protected $controller = 'Pages';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if controller exists (support for nested controllers)
        $controllerPath = '';
        $controllerName = ucwords($url[0]);
        
        // Check if it's a nested controller
        if(isset($url[1]) && is_dir('src/controllers/' . $controllerName)) {
            $controllerPath = $controllerName . '/';
            $controllerName = ucwords($url[1]);
            unset($url[1]);
            unset($url[0]);
        } else {
            unset($url[0]);
        }

        // Check if controller file exists
        if(file_exists('src/controllers/' . $controllerPath . $controllerName . '.php')) {
            $this->controller = $controllerName;
        }

        // Require the controller
        require_once 'src/controllers/' . $controllerPath . $this->controller . '.php';

        // Instantiate controller
        $this->controller = new $this->controller;

        // Check for method in controller
        if(isset($url[2]) && $controllerPath !== '') {
            if(method_exists($this->controller, $url[2])) {
                $this->method = $url[2];
                unset($url[2]);
            }
        } else if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call the method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Parse URL to get controller, method and parameters
    public function parseUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        
        return ['Pages', 'index'];
    }
}