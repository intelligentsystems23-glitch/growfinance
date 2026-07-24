<?php
if (!class_exists('Setting')) {
    require_once __DIR__ . '/../models/Setting.php';
}

class SettingController {
    private $settingModel;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $db = getConnection();
        $this->settingModel = new Setting($db);
    }
    
    public function index() {
        require_permission('settings.manage');
        $settings = $this->settingModel->getAllGrouped();
        
        $title = 'Settings';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/settings/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function save() {
        require_permission('settings.manage');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isJson = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
                   || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            
            $settings = $_POST['settings'] ?? [];
            $result = true;
            
            if (!empty($settings) && is_array($settings)) {
                $result = $this->settingModel->updateBatch($settings);
            }
            
            if (!empty($_FILES)) {
                $this->settingModel->handleFileUploads($_FILES);
            }
            
            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => (bool)$result,
                    'message' => $result ? 'Settings updated successfully!' : 'Error updating settings.'
                ]);
                exit;
            } else {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_message'] = $result ? 'Settings updated successfully!' : 'Error updating settings.';
                $_SESSION['flash_type'] = $result ? 'success' : 'error';
                header('Location: ' . BASE_URL . '/settings');
                exit;
            }
        }
    }
}
