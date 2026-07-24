<?php
if (!class_exists('User')) {
    require_once __DIR__ . '/../models/User.php';
}

class UserController {
    private $userModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->userModel = new User($this->db);
    }
    
    public function index() {
        require_permission('users.manage');

        $filters = [];
        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
        if (isset($_GET['is_active'])) $filters['is_active'] = $_GET['is_active'];
        
        $users = $this->userModel->getAll($filters);
        
        $title = 'User Management';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/users/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if (!is_admin()) {
            require_permission('users.create_admin_only');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->userModel->create($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $roles = $this->userModel->getAllRoles();
        
        $title = 'Add User';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/users/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if (!is_admin()) {
            require_permission('users.manage_admin_only');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->userModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $user = $this->userModel->getById($id);
        $roles = $this->userModel->getAllRoles();
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/users');
            exit;
        }
        
        $user['role_ids'] = explode(',', $user['role_ids'] ?? '');
        
        $title = 'Edit User';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/users/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if (!is_admin()) {
            require_permission('users.delete_admin_only');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->userModel->delete($id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function roles() {
        require_permission('roles.manage');

        $roles = $this->userModel->getAllRoles();
        $permissions = $this->userModel->getAllPermissions();
        
        // Group permissions by module
        $grouped_permissions = [];
        foreach ($permissions as $perm) {
            $grouped_permissions[$perm['module']][] = $perm;
        }
        
        $title = 'Roles & Permissions';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/users/roles.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function getRolePermissions($id) {
        require_permission('roles.manage');

        header('Content-Type: application/json');
        $permissions = $this->userModel->getRolePermissions($id);
        echo json_encode($permissions);
    }
    
    public function updateRolePermissions($id) {
        require_permission('roles.manage');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $permissions = $_POST['permissions'] ?? [];
                $this->userModel->updateRolePermissions($id, $permissions);
                echo json_encode(['success' => true, 'message' => 'Permissions updated successfully!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function profile() {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $this->userModel->update($user_id, $_POST);
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['full_name'] = $_POST['full_name'];
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $user_id = $_SESSION['user_id'] ?? 1;
        $user = $this->userModel->getById($user_id);
        
        $title = 'My Profile';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/users/profile.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
