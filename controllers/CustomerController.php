<?php
/**
 * Customer Controller
 * Location: C:\xampp\htdocs\erp_system\controllers\CustomerController.php
 */

if (!class_exists('Customer')) {
    require_once __DIR__ . '/../models/Customer.php';
}

class CustomerController {
    private $customerModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->customerModel = new Customer($this->db);
    }
    
    public function index() {
        $filters = [];
        
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['city'])) {
            $filters['city'] = $_GET['city'];
        }
        if (isset($_GET['country'])) {
            $filters['country'] = $_GET['country'];
        }
        
        $customers = $this->customerModel->getAll($filters);
        $stats = $this->customerModel->getCustomerStats();
        $cities = $this->customerModel->getCities();
        $countries = $this->customerModel->getCountries();
        
        $title = 'Customers';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $customer = $this->customerModel->getById($id);
        
        if (!$customer) {
            header('Location: ' . BASE_URL . '/customers');
            exit;
        }
        
        $invoices = $this->customerModel->getCustomerInvoices($id);
        $payments = $this->customerModel->getCustomerPayments($id);
        $contacts = $this->customerModel->getCustomerContacts($id);
        $notes = $this->customerModel->getCustomerNotes($id);
        $activities = $this->customerModel->getCustomerActivities($id);
        
        $title = 'Customer Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $customer_id = $this->customerModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $customer_id, 'message' => 'Customer created successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $title = 'Add New Customer';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->customerModel->update($id, $_POST);
                echo json_encode(['success' => true, 'message' => 'Customer updated successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $customer = $this->customerModel->getById($id);
        
        if (!$customer) {
            header('Location: ' . BASE_URL . '/customers');
            exit;
        }
        
        $title = 'Edit Customer';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->customerModel->delete($id);
                echo json_encode(['success' => true, 'message' => 'Customer deleted successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        header('Location: ' . BASE_URL . '/customers');
    }
    
    public function addNote($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $note = $_POST['note'] ?? '';
                $this->customerModel->addNote($id, $note);
                echo json_encode(['success' => true, 'message' => 'Note added successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
}
