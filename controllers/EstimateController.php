<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Estimate.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Product.php';

class EstimateController extends Controller {
    private $estimateModel;
    private $customerModel;
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->estimateModel = new Estimate($this->db);
        $this->customerModel = new Customer($this->db);
        $this->productModel = new Product($this->db);
    }
    
    public function index() {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $estimates = $this->estimateModel->getAll($filters);
        
        $data = [
            'title' => 'Estimates',
            'estimates' => $estimates
        ];
        
        $this->render('estimates/index', $data);
    }
    
    public function create() {
        if ($this->isPost()) {
            header('Content-Type: application/json');
            
            try {
                // Debug: Log received data
                error_log("POST data: " . print_r($_POST, true));
                
                $estimate_id = $this->estimateModel->create($_POST);
                
                echo json_encode(['success' => true, 'id' => $estimate_id]);
            } catch (Exception $e) {
                error_log("Error creating estimate: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $customers = $this->customerModel->getAll();
        $products = $this->productModel->getAll();
        
        $data = [
            'title' => 'Create Estimate',
            'customers' => $customers,
            'products' => $products
        ];
        
        $this->render('estimates/create', $data);
    }
    
    public function view($id) {
        $estimate = $this->estimateModel->getById($id);
        
        if (!$estimate) {
            $this->redirect('estimates');
        }
        
        $items = $this->estimateModel->getItems($id);
        
        $data = [
            'title' => 'Estimate #' . $estimate['estimate_number'],
            'estimate' => $estimate,
            'items' => $items
        ];
        
        $this->render('estimates/view', $data);
    }
    
    public function updateStatus($id) {
        if ($this->isPost()) {
            header('Content-Type: application/json');
            
            $status = $_POST['status'] ?? '';
            $result = $this->estimateModel->updateStatus($id, $status);
            
            echo json_encode(['success' => $result]);
            return;
        }
    }
    
    public function convert($id) {
        try {
            $invoice_id = $this->estimateModel->convertToInvoice($id);
            $this->redirect('invoices/show/' . $invoice_id);
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('estimates/view/' . $id);
        }
    }
    
    public function delete($id) {
        if ($this->isPost()) {
            header('Content-Type: application/json');
            $result = $this->estimateModel->delete($id);
            echo json_encode(['success' => $result]);
            return;
        }
    }
}
