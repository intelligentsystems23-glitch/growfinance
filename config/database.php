<?php
// Database configuration for XAMPP
define('DB_HOST', 'localhost');
define('DB_NAME', 'erp_system');
define('DB_USER', 'root');
define('DB_PASS', '');  // XAMPP default is empty password
define('DB_CHARSET', 'utf8mb4');
define('BASE_URL', 'http://localhost/erp_system');
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/helpers/setting_helper.php';
require_once BASE_PATH . '/helpers/auth_helper.php';

function getConnection()
{
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
}

function authenticateUser($username, $password)
{
    if (!class_exists('User')) {
        require_once BASE_PATH . '/models/User.php';
    }
    $db = getConnection();
    $userModel = new User($db);
    $user = $userModel->authenticate($username, $password);
    if ($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['permissions'] = explode(',', $user['permissions'] ?? '');
        return true;
    }
    return false;
}
