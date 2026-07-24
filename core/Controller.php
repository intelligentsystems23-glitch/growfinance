<?php
/**
 * Base Controller Class
 * All controllers should extend this class
 */

class Controller {
    protected $db;
    protected $session;
    
    public function __construct() {
        // Load database connection
        if (file_exists(__DIR__ . '/../config/database.php')) {
            require_once __DIR__ . '/../config/database.php';
            $this->db = getConnection();
        }
        
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Load a model
     */
    protected function model($model) {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model($this->db);
        }
        
        throw new Exception("Model {$model} not found");
    }
    
    /**
     * Load a view with layout
     */
    protected function render($view, $data = []) {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Extract data to variables
            extract($data);
            
            // Make controller instance available in views
            $controller = $this;
            
            // Load header
            require_once __DIR__ . '/../views/layouts/header.php';
            
            // Load main view
            require_once $viewFile;
            
            // Load footer
            require_once __DIR__ . '/../views/layouts/footer.php';
        } else {
            throw new Exception("View {$view} not found");
        }
    }
    
    /**
     * Check if current URL matches any of the given modules
     * Used for sidebar active states
     */
    public function isActiveModule($modules) {
        $current_url = $_GET['url'] ?? '';
        
        // If it's an array, check multiple modules
        if (is_array($modules)) {
            foreach ($modules as $module) {
                if (strpos($current_url, $module) === 0) {
                    return true;
                }
            }
            return false;
        }
        
        // Single module check
        return strpos($current_url, $modules) === 0;
    }
    
    /**
     * Check if current URL exactly matches
     */
    public function isActive($url) {
        $current_url = $_GET['url'] ?? '';
        return $current_url === $url;
    }
    
    /**
     * Get current URL
     */
    public function getCurrentUrl() {
        return $_GET['url'] ?? '';
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Get input from request
     */
    protected function input($key = null, $default = null) {
        if ($key === null) {
            return $_REQUEST;
        }
        return $_REQUEST[$key] ?? $default;
    }
    
    /**
     * Get POST data
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET data
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Require login - redirect to login if not logged in
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }
    
    /**
     * Get current user ID
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user data
     */
    protected function getUser() {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ];
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get flash message
     */
    protected function getFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission($permission) {
        return has_permission($permission);
    }

    /**
     * Require permission - abort with 403 if unauthorized
     */
    protected function requirePermission($permission) {
        require_permission($permission);
    }
}
