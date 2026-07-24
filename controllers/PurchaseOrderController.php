<?php
if (!class_exists('PurchaseOrder')) {
    require_once __DIR__ . '/../models/PurchaseOrder.php';
}
if (!class_exists('Product')) {
    require_once __DIR__ . '/../models/Product.php';
}

class PurchaseOrderController {
    private $poModel;
    private $productModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->poModel = new PurchaseOrder($this->db);
        $this->productModel = new Product($this->db);
    }
    
    public function index() {
        $filters = [];
        
        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
        if (isset($_GET['vendor_id'])) $filters['vendor_id'] = $_GET['vendor_id'];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        if (isset($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
        if (isset($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
        
        $purchase_orders = $this->poModel->getAll($filters);
        $vendors = $this->poModel->getVendors();
        $stats = $this->poModel->getStats();
        
        $title = 'Purchase Orders';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/purchase-orders/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $po = $this->poModel->getById($id);
        
        if (!$po) {
            header('Location: ' . BASE_URL . '/purchase-orders');
            exit;
        }
        
        $items = $this->poModel->getItems($id);
        
        $title = 'Purchase Order Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/purchase-orders/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $po_id = $this->poModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $po_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $vendors = $this->poModel->getVendors();
        $products = $this->productModel->getAll();
        
        $title = 'Create Purchase Order';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/purchase-orders/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->poModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $po = $this->poModel->getById($id);
        
        if (!$po) {
            header('Location: ' . BASE_URL . '/purchase-orders');
            exit;
        }
        
        $items = $this->poModel->getItems($id);
        $vendors = $this->poModel->getVendors();
        $products = $this->productModel->getAll();
        
        $title = 'Edit Purchase Order';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/purchase-orders/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->poModel->delete($id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $this->poModel->approve($id, $user_id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function receive($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $_POST['po_id'] = $id;
                $this->poModel->receiveGoods($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function payment($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $_POST['po_id'] = $id;
                $this->poModel->recordPayment($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
}
