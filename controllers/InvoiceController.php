<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Product.php';

class InvoiceController extends Controller {
    private $invoiceModel;
    private $customerModel;
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->invoiceModel = new Invoice($this->db);
        $this->customerModel = new Customer($this->db);
        $this->productModel = new Product($this->db);
    }
    
    public function index() {
        require_permission('invoices.view');
        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];
        
        $invoices = $this->invoiceModel->getAll($filters);
        $stats = $this->invoiceModel->getStats();
        
        $data = [
            'title' => 'Invoices',
            'invoices' => $invoices,
            'stats' => $stats,
            'filters' => $filters
        ];
        
        $this->loadView('invoices/index', $data);
    }
    
    public function create() {
        require_permission('invoices.create');
        if ($this->isPost()) {
            header('Content-Type: application/json');
            
            try {
                $invoice_id = $this->invoiceModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $invoice_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $customers = $this->customerModel->getAll();
        $products = $this->productModel->getAll();
        
        // Get tax rates or use default
        $taxes = [
            ['id' => 1, 'tax_name' => 'No Tax', 'tax_rate' => 0],
            ['id' => 2, 'tax_name' => 'VAT (10%)', 'tax_rate' => 10]
        ];
        
        $data = [
            'title' => 'Create Invoice',
            'customers' => $customers,
            'products' => $products,
            'taxes' => $taxes
        ];
        
        $this->loadView('invoices/create', $data);
    }
    
    public function show($id) {
        require_permission('invoices.view');
        $invoice = $this->invoiceModel->getById($id);
        
        if (!$invoice) {
            $this->redirect('invoices');
        }
        
        $items = $this->invoiceModel->getItems($id);
        $payments = $this->invoiceModel->getPayments($id);
        
        $data = [
            'title' => 'Invoice #' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'items' => $items,
            'payments' => $payments
        ];
        
        $this->loadView('invoices/show', $data);
    }
    
    public function edit($id) {
        require_permission('invoices.edit');
        if ($this->isPost()) {
            header('Content-Type: application/json');
            
            try {
                $this->invoiceModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $invoice = $this->invoiceModel->getById($id);
        
        if (!$invoice) {
            $this->redirect('invoices');
        }
        
        $items = $this->invoiceModel->getItems($id);
        $customers = $this->customerModel->getAll();
        $products = $this->productModel->getAll();
        
        $taxes = [
            ['id' => 1, 'tax_name' => 'No Tax', 'tax_rate' => 0],
            ['id' => 2, 'tax_name' => 'VAT (10%)', 'tax_rate' => 10]
        ];
        
        $data = [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'items' => $items,
            'customers' => $customers,
            'products' => $products,
            'taxes' => $taxes
        ];
        
        $this->loadView('invoices/edit', $data);
    }
    
    public function delete($id) {
        require_permission('invoices.delete');
        if ($this->isPost()) {
            header('Content-Type: application/json');
            $result = $this->invoiceModel->delete($id);
            echo json_encode(['success' => $result]);
            return;
        }
    }
    
    public function payment($id) {
        require_permission('invoices.payment');
        if ($this->isPost()) {
            header('Content-Type: application/json');
            
            try {
                $_POST['invoice_id'] = $id;
                $this->invoiceModel->recordPayment($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        $controller = $this;
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $view . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
