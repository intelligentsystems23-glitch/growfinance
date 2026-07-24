<?php
class Banking {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Account Methods
    public function getAllAccounts($filters = []) {
        $sql = "SELECT * FROM bank_accounts WHERE 1=1";
        $params = [];
        
        if (isset($filters['is_active'])) {
            $sql .= " AND is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        if (!empty($filters['account_type'])) {
            $sql .= " AND account_type = :account_type";
            $params[':account_type'] = $filters['account_type'];
        }
        
        $sql .= " ORDER BY bank_name, account_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getAccountById($id) {
        $sql = "SELECT * FROM bank_accounts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function createAccount($data) {
        $accountNumber = !empty($data['account_number']) ? trim($data['account_number']) : ('ACC-' . date('YmdHis') . '-' . rand(100, 999));
        $openingBalance = (isset($data['opening_balance']) && $data['opening_balance'] !== '') ? (float)$data['opening_balance'] : 0.00;
        $currentBalance = (isset($data['current_balance']) && $data['current_balance'] !== '') ? (float)$data['current_balance'] : $openingBalance;

        $sql = "INSERT INTO bank_accounts (
                    account_number, account_name, bank_name, branch_name,
                    account_type, currency, opening_balance, current_balance,
                    as_of_date, account_holder, swift_code, routing_number,
                    iban, notes, is_active
                ) VALUES (
                    :account_number, :account_name, :bank_name, :branch_name,
                    :account_type, :currency, :opening_balance, :current_balance,
                    :as_of_date, :account_holder, :swift_code, :routing_number,
                    :iban, :notes, :is_active
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':account_number' => $accountNumber,
            ':account_name' => trim($data['account_name'] ?? 'Primary Account'),
            ':bank_name' => trim($data['bank_name'] ?? 'Bank'),
            ':branch_name' => !empty($data['branch_name']) ? trim($data['branch_name']) : null,
            ':account_type' => !empty($data['account_type']) ? trim($data['account_type']) : 'checking',
            ':currency' => !empty($data['currency']) ? trim($data['currency']) : 'USD',
            ':opening_balance' => $openingBalance,
            ':current_balance' => $currentBalance,
            ':as_of_date' => !empty($data['as_of_date']) ? $data['as_of_date'] : date('Y-m-d'),
            ':account_holder' => !empty($data['account_holder']) ? trim($data['account_holder']) : null,
            ':swift_code' => !empty($data['swift_code']) ? trim($data['swift_code']) : null,
            ':routing_number' => !empty($data['routing_number']) ? trim($data['routing_number']) : null,
            ':iban' => !empty($data['iban']) ? trim($data['iban']) : null,
            ':notes' => !empty($data['notes']) ? trim($data['notes']) : null,
            ':is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);
    }
    
    public function updateAccount($id, $data) {
        $sql = "UPDATE bank_accounts SET
                    account_name = :account_name,
                    bank_name = :bank_name,
                    branch_name = :branch_name,
                    account_type = :account_type,
                    account_holder = :account_holder,
                    routing_number = :routing_number,
                    notes = :notes,
                    is_active = :is_active
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':account_name' => $data['account_name'],
            ':bank_name' => $data['bank_name'],
            ':branch_name' => $data['branch_name'] ?? null,
            ':account_type' => $data['account_type'],
            ':account_holder' => $data['account_holder'] ?? null,
            ':routing_number' => $data['routing_number'] ?? null,
            ':notes' => $data['notes'] ?? null,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    public function deleteAccount($id) {
        $sql = "UPDATE bank_accounts SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function updateBalance($account_id, $amount, $type = 'add') {
        if ($type == 'add') {
            $sql = "UPDATE bank_accounts SET current_balance = current_balance + :amount WHERE id = :id";
        } else {
            $sql = "UPDATE bank_accounts SET current_balance = current_balance - :amount WHERE id = :id";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $account_id, ':amount' => $amount]);
    }
    
    // Transaction Methods
    public function getAllTransactions($filters = []) {
        $sql = "SELECT t.*, ba.account_name, ba.bank_name, u.username as created_by_name
                FROM bank_transactions t
                LEFT JOIN bank_accounts ba ON t.bank_account_id = ba.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['account_id'])) {
            $sql .= " AND t.bank_account_id = :account_id";
            $params[':account_id'] = $filters['account_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND t.transaction_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND t.transaction_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        if (isset($filters['reconciled'])) {
            $sql .= " AND t.reconciled = :reconciled";
            $params[':reconciled'] = $filters['reconciled'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (t.description LIKE :search OR t.payee_payer LIKE :search OR t.transaction_number LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY t.transaction_date DESC, t.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getTransactionById($id) {
        $sql = "SELECT t.*, ba.account_name, ba.bank_name
                FROM bank_transactions t
                LEFT JOIN bank_accounts ba ON t.bank_account_id = ba.id
                WHERE t.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function createTransaction($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate transaction number
            $transaction_number = $this->generateTransactionNumber();
            
            // Get current balance
            $account = $this->getAccountById($data['bank_account_id']);
            $running_balance = $account['current_balance'];
            
            // Calculate new running balance
            if (in_array($data['transaction_type'], ['deposit', 'receipt', 'interest'])) {
                $running_balance += $data['amount'];
            } else {
                $running_balance -= $data['amount'];
            }
            
            $sql = "INSERT INTO bank_transactions (
                        transaction_number, bank_account_id, transaction_date,
                        transaction_type, amount, running_balance, reference_type,
                        reference_id, payee_payer, description, check_number, created_by
                    ) VALUES (
                        :transaction_number, :bank_account_id, :transaction_date,
                        :transaction_type, :amount, :running_balance, :reference_type,
                        :reference_id, :payee_payer, :description, :check_number, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':transaction_number' => $transaction_number,
                ':bank_account_id' => $data['bank_account_id'],
                ':transaction_date' => $data['transaction_date'],
                ':transaction_type' => $data['transaction_type'],
                ':amount' => $data['amount'],
                ':running_balance' => $running_balance,
                ':reference_type' => $data['reference_type'] ?? null,
                ':reference_id' => $data['reference_id'] ?? null,
                ':payee_payer' => $data['payee_payer'] ?? null,
                ':description' => $data['description'] ?? null,
                ':check_number' => $data['check_number'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            // Update account balance
            $this->updateBalance($data['bank_account_id'], $data['amount'], 
                in_array($data['transaction_type'], ['deposit', 'receipt', 'interest']) ? 'add' : 'subtract');
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function generateTransactionNumber() {
        return 'TRX-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
    
    public function getAccountBalance($account_id) {
        $sql = "SELECT current_balance FROM bank_accounts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $account_id]);
        $result = $stmt->fetch();
        return $result['current_balance'] ?? 0;
    }
    
    public function getTotalBalance() {
        $sql = "SELECT SUM(current_balance) as total FROM bank_accounts WHERE is_active = 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    // Transfer Methods
    public function createTransfer($data) {
        try {
            $this->db->beginTransaction();
            
            $transfer_number = 'TRF-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            $sql = "INSERT INTO bank_transfers (
                        transfer_number, from_account_id, to_account_id,
                        transfer_date, amount, exchange_rate, fee,
                        description, reference_number, created_by
                    ) VALUES (
                        :transfer_number, :from_account_id, :to_account_id,
                        :transfer_date, :amount, :exchange_rate, :fee,
                        :description, :reference_number, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':transfer_number' => $transfer_number,
                ':from_account_id' => $data['from_account_id'],
                ':to_account_id' => $data['to_account_id'],
                ':transfer_date' => $data['transfer_date'],
                ':amount' => $data['amount'],
                ':exchange_rate' => $data['exchange_rate'] ?? 1.0000,
                ':fee' => $data['fee'] ?? 0,
                ':description' => $data['description'] ?? null,
                ':reference_number' => $data['reference_number'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            // Create withdrawal transaction
            $this->createTransaction([
                'bank_account_id' => $data['from_account_id'],
                'transaction_date' => $data['transfer_date'],
                'transaction_type' => 'transfer',
                'amount' => $data['amount'],
                'payee_payer' => 'Transfer to ' . $data['to_account_name'],
                'description' => $data['description'],
                'reference_type' => 'transfer',
                'reference_id' => $this->db->lastInsertId()
            ]);
            
            // Create deposit transaction
            $this->createTransaction([
                'bank_account_id' => $data['to_account_id'],
                'transaction_date' => $data['transfer_date'],
                'transaction_type' => 'transfer',
                'amount' => $data['amount'],
                'payee_payer' => 'Transfer from ' . $data['from_account_name'],
                'description' => $data['description'],
                'reference_type' => 'transfer',
                'reference_id' => $this->db->lastInsertId()
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getTransfers($filters = []) {
        $sql = "SELECT t.*, 
                fa.account_name as from_account_name, fa.bank_name as from_bank,
                ta.account_name as to_account_name, ta.bank_name as to_bank,
                u.username as created_by_name
                FROM bank_transfers t
                LEFT JOIN bank_accounts fa ON t.from_account_id = fa.id
                LEFT JOIN bank_accounts ta ON t.to_account_id = ta.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND t.transfer_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND t.transfer_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY t.transfer_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function reconcileTransactions($ids, $reconciled = 1) {
        if (empty($ids)) return true;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE bank_transactions SET reconciled = ? WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $params = array_merge([$reconciled], $ids);
        return $stmt->execute($params);
    }
    
    public function toggleReconciliation($id) {
        $sql = "UPDATE bank_transactions SET reconciled = 1 - reconciled WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
