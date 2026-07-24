<?php
class Accounting {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Chart of Accounts Methods
    public function getChartOfAccounts($filters = []) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM chart_of_accounts WHERE parent_id = c.id) as has_children,
                p.account_name as parent_name
                FROM chart_of_accounts c
                LEFT JOIN chart_of_accounts p ON c.parent_id = p.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['account_type'])) {
            $sql .= " AND c.account_type = :account_type";
            $params[':account_type'] = $filters['account_type'];
        }
        
        if (isset($filters['is_active'])) {
            $sql .= " AND c.is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (c.account_name LIKE :search OR c.account_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY c.account_code";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getAccountById($id) {
        $sql = "SELECT * FROM chart_of_accounts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function createAccount($data) {
        $sql = "INSERT INTO chart_of_accounts (
                    account_code, account_name, account_type, parent_id,
                    description, opening_balance, current_balance, is_active
                ) VALUES (
                    :account_code, :account_name, :account_type, :parent_id,
                    :description, :opening_balance, :current_balance, :is_active
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':account_code' => $data['account_code'],
            ':account_name' => $data['account_name'],
            ':account_type' => $data['account_type'],
            ':parent_id' => $data['parent_id'] ?? null,
            ':description' => $data['description'] ?? null,
            ':opening_balance' => $data['opening_balance'] ?? 0,
            ':current_balance' => $data['current_balance'] ?? $data['opening_balance'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    public function updateAccount($id, $data) {
        $sql = "UPDATE chart_of_accounts SET
                    account_name = :account_name,
                    account_type = :account_type,
                    parent_id = :parent_id,
                    description = :description,
                    is_active = :is_active
                WHERE id = :id AND is_system = 0";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':account_name' => $data['account_name'],
            ':account_type' => $data['account_type'],
            ':parent_id' => $data['parent_id'] ?? null,
            ':description' => $data['description'] ?? null,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    public function getAccountsByType($type = null) {
        $sql = "SELECT id, account_code, account_name FROM chart_of_accounts WHERE is_active = 1";
        if ($type) {
            $sql .= " AND account_type = :type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':type' => $type]);
        } else {
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }
    
    // Journal Entry Methods
    public function getJournalEntries($filters = []) {
        $sql = "SELECT j.*, 
                u.username as created_by_name,
                p.username as posted_by_name
                FROM journal_entries j
                LEFT JOIN users u ON j.created_by = u.id
                LEFT JOIN users p ON j.posted_by = p.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND j.journal_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND j.journal_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND j.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY j.journal_date DESC, j.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getJournalEntryById($id) {
        $sql = "SELECT j.*, u.username as created_by_name
                FROM journal_entries j
                LEFT JOIN users u ON j.created_by = u.id
                WHERE j.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getJournalItems($journal_id) {
        $sql = "SELECT ji.*, c.account_code, c.account_name, c.account_type
                FROM journal_items ji
                JOIN chart_of_accounts c ON ji.account_id = c.id
                WHERE ji.journal_id = :journal_id
                ORDER BY ji.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':journal_id' => $journal_id]);
        return $stmt->fetchAll();
    }
    
    public function createJournalEntry($data) {
        try {
            $this->db->beginTransaction();
            
            $journal_number = $this->generateJournalNumber();
            
            $sql = "INSERT INTO journal_entries (
                        journal_number, journal_date, reference_type, reference_id,
                        description, total_debit, total_credit, status, created_by
                    ) VALUES (
                        :journal_number, :journal_date, :reference_type, :reference_id,
                        :description, :total_debit, :total_credit, :status, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':journal_number' => $journal_number,
                ':journal_date' => $data['journal_date'],
                ':reference_type' => $data['reference_type'] ?? null,
                ':reference_id' => $data['reference_id'] ?? null,
                ':description' => $data['description'],
                ':total_debit' => $data['total_debit'],
                ':total_credit' => $data['total_credit'],
                ':status' => $data['status'] ?? 'draft',
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            $journal_id = $this->db->lastInsertId();
            
            // Insert journal items
            $items = json_decode($data['items'], true);
            foreach ($items as $item) {
                $this->addJournalItem($journal_id, $item);
            }
            
            $this->db->commit();
            return $journal_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function addJournalItem($journal_id, $item) {
        $sql = "INSERT INTO journal_items (
                    journal_id, account_id, description, debit, credit, reference_type, reference_id
                ) VALUES (
                    :journal_id, :account_id, :description, :debit, :credit, :reference_type, :reference_id
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':journal_id' => $journal_id,
            ':account_id' => $item['account_id'],
            ':description' => $item['description'] ?? null,
            ':debit' => $item['debit'] ?? 0,
            ':credit' => $item['credit'] ?? 0,
            ':reference_type' => $item['reference_type'] ?? null,
            ':reference_id' => $item['reference_id'] ?? null
        ]);
    }
    
    public function postJournalEntry($id, $user_id) {
        try {
            $this->db->beginTransaction();
            
            // Update journal status
            $sql = "UPDATE journal_entries SET status = 'posted', posted_by = :user_id, posted_at = NOW() WHERE id = :id AND status = 'draft'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $user_id]);
            
            // Get journal items
            $items = $this->getJournalItems($id);
            
            // Update account balances
            foreach ($items as $item) {
                if ($item['debit'] > 0) {
                    $this->updateAccountBalance($item['account_id'], $item['debit'], 'debit');
                }
                if ($item['credit'] > 0) {
                    $this->updateAccountBalance($item['account_id'], $item['credit'], 'credit');
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function updateAccountBalance($account_id, $amount, $type) {
        $account = $this->getAccountById($account_id);
        
        if (in_array($account['account_type'], ['asset', 'expense'])) {
            if ($type == 'debit') {
                $sql = "UPDATE chart_of_accounts SET current_balance = current_balance + :amount WHERE id = :id";
            } else {
                $sql = "UPDATE chart_of_accounts SET current_balance = current_balance - :amount WHERE id = :id";
            }
        } else {
            if ($type == 'credit') {
                $sql = "UPDATE chart_of_accounts SET current_balance = current_balance + :amount WHERE id = :id";
            } else {
                $sql = "UPDATE chart_of_accounts SET current_balance = current_balance - :amount WHERE id = :id";
            }
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $account_id, ':amount' => $amount]);
    }
    
    private function generateJournalNumber() {
        return 'JRNL-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
    
    public function getTrialBalance($as_of_date = null) {
        if (!$as_of_date) {
            $as_of_date = date('Y-m-d');
        }
        
        $sql = "SELECT 
                    c.account_code,
                    c.account_name,
                    c.account_type,
                    COALESCE(SUM(CASE WHEN j.journal_date <= :as_of_date AND ji.debit > 0 THEN ji.debit ELSE 0 END), 0) as total_debit,
                    COALESCE(SUM(CASE WHEN j.journal_date <= :as_of_date AND ji.credit > 0 THEN ji.credit ELSE 0 END), 0) as total_credit,
                    c.opening_balance
                FROM chart_of_accounts c
                LEFT JOIN journal_items ji ON c.id = ji.account_id
                LEFT JOIN journal_entries j ON ji.journal_id = j.id AND j.status = 'posted'
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.account_code";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':as_of_date' => $as_of_date]);
        return $stmt->fetchAll();
    }
    
    public function getBalanceSheet($as_of_date = null) {
        if (!$as_of_date) $as_of_date = date('Y-m-d');
        
        $assets = $this->getAccountsByType('asset');
        $liabilities = $this->getAccountsByType('liability');
        $equity = $this->getAccountsByType('equity');
        
        $trial_balance = $this->getTrialBalance($as_of_date);
        $tb_indexed = [];
        foreach ($trial_balance as $tb) {
            $tb_indexed[$tb['account_code']] = $tb;
        }
        
        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'trial_balance' => $tb_indexed
        ];
    }
    
    public function getProfitLoss($date_from = null, $date_to = null) {
        if (!$date_from) $date_from = date('Y-01-01');
        if (!$date_to) $date_to = date('Y-m-d');
        
        $sql = "SELECT 
                    c.account_code,
                    c.account_name,
                    COALESCE(SUM(ji.credit), 0) as income_amount,
                    COALESCE(SUM(ji.debit), 0) as expense_amount
                FROM chart_of_accounts c
                LEFT JOIN journal_items ji ON c.id = ji.account_id
                LEFT JOIN journal_entries j ON ji.journal_id = j.id AND j.status = 'posted'
                WHERE j.journal_date BETWEEN :date_from AND :date_to
                AND c.account_type IN ('income', 'expense')
                GROUP BY c.id
                ORDER BY c.account_type, c.account_code";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date_from' => $date_from, ':date_to' => $date_to]);
        return $stmt->fetchAll();
    }
}
