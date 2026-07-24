<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User($this->db);
    }
    
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($this->isPost()) {
            $email = $this->post('email');
            $password = $this->post('password');
            $remember = $this->post('remember') ? true : false;
            
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Remember me - set cookie
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
                }
                
                $this->redirect('dashboard');
            } else {
                $error = 'Invalid email or password. Please try again.';
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }
    
    public function register() {
        // Public registration is disabled. User account creation is restricted to Administrators.
        $this->redirect('login');
    }
    
    public function logout() {
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        $this->redirect('login');
    }
}
