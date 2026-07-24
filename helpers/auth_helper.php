<?php
/**
 * Global Authentication & Authorization (RBAC) Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('current_user')) {
    function current_user() {
        if (!is_logged_in()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'role' => $_SESSION['role'] ?? 'employee',
            'permissions' => $_SESSION['permissions'] ?? []
        ];
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return is_logged_in() && isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin';
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission) {
        if (!is_logged_in()) {
            return false;
        }

        // Administrators bypass all permission restrictions
        if (is_admin()) {
            return true;
        }

        // Check permissions array stored in session
        if (isset($_SESSION['permissions']) && is_array($_SESSION['permissions'])) {
            if (in_array($permission, $_SESSION['permissions'], true)) {
                return true;
            }
        }

        // Database fallback check
        try {
            if (function_exists('getConnection')) {
                $db = getConnection();
                $userId = $_SESSION['user_id'];
                
                $sql = "SELECT COUNT(*) as cnt 
                        FROM user_roles ur
                        JOIN role_permissions rp ON ur.role_id = rp.role_id
                        JOIN permissions p ON rp.permission_id = p.id
                        WHERE ur.user_id = :user_id AND p.name = :permission";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([':user_id' => $userId, ':permission' => $permission]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row && $row['cnt'] > 0) {
                    // Cache in session
                    if (!isset($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
                        $_SESSION['permissions'] = [];
                    }
                    if (!in_array($permission, $_SESSION['permissions'])) {
                        $_SESSION['permissions'][] = $permission;
                    }
                    return true;
                }
            }
        } catch (Exception $e) {
            // Silence DB check errors
        }

        return false;
    }
}

if (!function_exists('require_permission')) {
    function require_permission($permission) {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (!has_permission($permission)) {
            $isJson = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                   || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

            if ($isJson) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Access Denied: You do not have permission to perform this action.'
                ]);
                exit;
            } else {
                http_response_code(403);
                $title = '403 Access Denied';
                $required_perm = $permission;
                
                require_once BASE_PATH . '/views/layouts/header.php';
                require_once BASE_PATH . '/views/errors/403.php';
                require_once BASE_PATH . '/views/layouts/footer.php';
                exit;
            }
        }
    }
}
