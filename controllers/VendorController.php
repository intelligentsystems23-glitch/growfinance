<?php
if (!class_exists('Vendor')) {
    require_once __DIR__ . '/../models/Vendor.php';
}

class VendorController {
    private $vendorModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->vendorModel = new Vendor($this->db);
    }
    
    public function index() {
        $filters = [];
        
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        $vendors = $this->vendorModel->getAll($filters);
        $categories = $this->vendorModel->getCategories();
        $stats = $this->vendorModel->getStats();
        
        $title = 'Vendors';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/vendors/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $vendor = $this->vendorModel->getById($id);
        
        if (!$vendor) {
            header('Location: ' . BASE_URL . '/vendors');
            exit;
        }
        
        $transactions = $this->vendorModel->getVendorTransactions($id);
        $purchase_orders = $this->vendorModel->getVendorPOs($id);
        
        $title = 'Vendor Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/vendors/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $vendor_id = $this->vendorModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $vendor_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $categories = $this->vendorModel->getCategories();
        
        $title = 'Add New Vendor';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/vendors/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->vendorModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $vendor = $this->vendorModel->getById($id);
        $categories = $this->vendorModel->getCategories();
        
        if (!$vendor) {
            header('Location: ' . BASE_URL . '/vendors');
            exit;
        }
        
        $title = 'Edit Vendor';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/vendors/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->vendorModel->delete($id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
}
