<?php
if (!class_exists('Banking')) {
    require_once __DIR__ . '/../models/Banking.php';
}

class BankingController {
    private $bankingModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->bankingModel = new Banking($this->db);
    }
    
    public function index() {
        $accounts = $this->bankingModel->getAllAccounts(['is_active' => 1]);
        $total_balance = $this->bankingModel->getTotalBalance();
        
        // Recent transactions
        $transactions = $this->bankingModel->getAllTransactions(['limit' => 10]);
        
        $title = 'Banking Dashboard';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function accounts() {
        $accounts = $this->bankingModel->getAllAccounts();
        $total_balance = $this->bankingModel->getTotalBalance();
        
        $title = 'Bank Accounts';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/accounts.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->bankingModel->createAccount($_POST);
                echo json_encode(['success' => true, 'message' => 'Account created successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $title = 'Add Bank Account';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/create_account.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function editAccount($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->bankingModel->updateAccount($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $account = $this->bankingModel->getAccountById($id);
        
        $title = 'Edit Bank Account';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/edit_account.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function transactions() {
        $filters = [
            'account_id' => $_GET['account_id'] ?? null,
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t')
        ];
        
        $transactions = $this->bankingModel->getAllTransactions($filters);
        $accounts = $this->bankingModel->getAllAccounts(['is_active' => 1]);
        
        $title = 'Bank Transactions';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/transactions.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createTransaction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->bankingModel->createTransaction($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $accounts = $this->bankingModel->getAllAccounts(['is_active' => 1]);
        
        $title = 'Record Transaction';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/create_transaction.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function transfers() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t')
        ];
        
        $transfers = $this->bankingModel->getTransfers($filters);
        
        $title = 'Fund Transfers';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/transfers.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createTransfer() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->bankingModel->createTransfer($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $accounts = $this->bankingModel->getAllAccounts(['is_active' => 1]);
        
        $title = 'Transfer Funds';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/create_transfer.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function reconciliation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                if (isset($_POST['ids']) && is_array($_POST['ids'])) {
                    $ids = array_map('intval', $_POST['ids']);
                    $reconciled = isset($_POST['reconcile']) ? (int)$_POST['reconcile'] : 1;
                    $this->bankingModel->reconcileTransactions($ids, $reconciled);
                    echo json_encode(['success' => true]);
                } elseif (isset($_POST['id'])) {
                    $id = (int)$_POST['id'];
                    $this->bankingModel->toggleReconciliation($id);
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $accounts = $this->bankingModel->getAllAccounts(['is_active' => 1]);
        $account_id = $_GET['account_id'] ?? ($accounts[0]['id'] ?? null);
        
        $unreconciled_transactions = [];
        $reconciled_transactions = [];
        $selected_account = null;
        
        if ($account_id) {
            $selected_account = $this->bankingModel->getAccountById($account_id);
            
            // Get unreconciled transactions
            $unreconciled_transactions = $this->bankingModel->getAllTransactions([
                'account_id' => $account_id,
                'reconciled' => 0
            ]);
            
            // Get reconciled transactions
            $reconciled_transactions = $this->bankingModel->getAllTransactions([
                'account_id' => $account_id,
                'reconciled' => 1
            ]);
        }
        
        $currency = 'USD';
        try {
            $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'currency' LIMIT 1");
            $currency = $stmt->fetchColumn() ?: 'USD';
        } catch (Exception $e) {}
        
        $title = 'Bank Reconciliation';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/banking/reconciliation.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
