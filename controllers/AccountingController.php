<?php
if (!class_exists('Accounting')) {
    require_once __DIR__ . '/../models/Accounting.php';
}

class AccountingController {
    private $accountingModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->accountingModel = new Accounting($this->db);
    }
    
    public function index() {
        $title = 'Accounting Dashboard';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    // Chart of Accounts
    public function chartOfAccounts() {
        $filters = [];
        if (isset($_GET['account_type'])) $filters['account_type'] = $_GET['account_type'];
        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
        
        $accounts = $this->accountingModel->getChartOfAccounts($filters);
        
        $title = 'Chart of Accounts';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/chart_of_accounts.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->accountingModel->createAccount($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $parent_accounts = $this->accountingModel->getChartOfAccounts(['is_active' => 1]);
        
        $title = 'Add Account';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/create_account.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    // Journal Entries
    public function journalEntries() {
        $filters = [];
        if (isset($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
        if (isset($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        
        $journals = $this->accountingModel->getJournalEntries($filters);
        
        $title = 'Journal Entries';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/journal_entries.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createJournal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $journal_id = $this->accountingModel->createJournalEntry($_POST);
                echo json_encode(['success' => true, 'id' => $journal_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $accounts = $this->accountingModel->getAccountsByType();
        
        $title = 'New Journal Entry';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/create_journal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function viewJournal($id) {
        $journal = $this->accountingModel->getJournalEntryById($id);
        $items = $this->accountingModel->getJournalItems($id);
        
        if (!$journal) {
            header('Location: ' . BASE_URL . '/accounting/journal-entries');
            exit;
        }
        
        $title = 'Journal Entry Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/view_journal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function postJournal($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $this->accountingModel->postJournalEntry($id, $user_id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    // Reports
    public function trialBalance() {
        $as_of_date = $_GET['as_of_date'] ?? date('Y-m-d');
        $trial_balance = $this->accountingModel->getTrialBalance($as_of_date);
        
        $title = 'Trial Balance';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/trial_balance.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function balanceSheet() {
        $as_of_date = $_GET['as_of_date'] ?? date('Y-m-d');
        $data = $this->accountingModel->getBalanceSheet($as_of_date);
        
        $title = 'Balance Sheet';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/balance_sheet.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function profitLoss() {
        $date_from = $_GET['date_from'] ?? date('Y-01-01');
        $date_to = $_GET['date_to'] ?? date('Y-m-d');
        $data = $this->accountingModel->getProfitLoss($date_from, $date_to);
        
        $title = 'Profit & Loss';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/accounting/profit_loss.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function editAccount($id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header('Content-Type: application/json');
        try {
            $this->accountingModel->updateAccount($id, $_POST);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        return;
    }
    
    $account = $this->accountingModel->getAccountById($id);
    $parent_accounts = $this->accountingModel->getChartOfAccounts(['is_active' => 1]);
    
    if (!$account) {
        header('Location: ' . BASE_URL . '/accounting/chart-of-accounts');
        exit;
    }
    
    $title = 'Edit Account';
    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/accounting/edit_account.php';
    require_once __DIR__ . '/../views/layouts/footer.php';
}
}
