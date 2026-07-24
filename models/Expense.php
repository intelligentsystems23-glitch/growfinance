<?php
class Expense {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT e.*, 
                ec.category_name,
                c.company_name as vendor_name,
                u.username as approved_by_name
                FROM expenses e
                LEFT JOIN expense_categories ec ON e.category_id = ec.id
                LEFT JOIN contacts c ON e.vendor_id = c.id
                LEFT JOIN users u ON e.approved_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.description LIKE :search OR e.expense_number LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND e.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['vendor_id'])) {
            $sql .= " AND e.vendor_id = :vendor_id";
            $params[':vendor_id'] = $filters['vendor_id'];
        }
        
        if (!empty($filters['payment_status'])) {
            $sql .= " AND e.payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND e.expense_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND e.expense_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY e.expense_date DESC, e.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT e.*, 
                ec.category_name,
                c.company_name as vendor_name,
                u.username as approved_by_name
                FROM expenses e
                LEFT JOIN expense_categories ec ON e.category_id = ec.id
                LEFT JOIN contacts c ON e.vendor_id = c.id
                LEFT JOIN users u ON e.approved_by = u.id
                WHERE e.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate expense number
            $expense_number = $this->generateExpenseNumber();
            
            $sql = "INSERT INTO expenses (
                        expense_number, expense_date, category_id,
                        vendor_id, description, amount, payment_method,
                        reference_number, payment_status, notes, created_at
                    ) VALUES (
                        :expense_number, :expense_date, :category_id,
                        :vendor_id, :description, :amount, :payment_method,
                        :reference_number, :payment_status, :notes, NOW()
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':expense_number' => $expense_number,
                ':expense_date' => $data['expense_date'],
                ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
                ':vendor_id' => !empty($data['vendor_id']) ? $data['vendor_id'] : null,
                ':description' => $data['description'],
                ':amount' => $data['amount'],
                ':payment_method' => !empty($data['payment_method']) ? $data['payment_method'] : null,
                ':reference_number' => !empty($data['reference_number']) ? $data['reference_number'] : null,
                ':payment_status' => $data['payment_status'] ?? 'pending',
                ':notes' => !empty($data['notes']) ? $data['notes'] : null
            ]);
            
            $expense_id = $this->db->lastInsertId();
            
            $this->db->commit();
            return $expense_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE expenses SET
                    expense_date = :expense_date,
                    category_id = :category_id,
                    vendor_id = :vendor_id,
                    description = :description,
                    amount = :amount,
                    payment_method = :payment_method,
                    reference_number = :reference_number,
                    payment_status = :payment_status,
                    notes = :notes
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':expense_date' => $data['expense_date'],
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':vendor_id' => !empty($data['vendor_id']) ? $data['vendor_id'] : null,
            ':description' => $data['description'],
            ':amount' => $data['amount'],
            ':payment_method' => !empty($data['payment_method']) ? $data['payment_method'] : null,
            ':reference_number' => !empty($data['reference_number']) ? $data['reference_number'] : null,
            ':payment_status' => $data['payment_status'],
            ':notes' => !empty($data['notes']) ? $data['notes'] : null
        ]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM expenses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function approve($id, $approved_by) {
        $sql = "UPDATE expenses SET 
                    approved_by = :approved_by,
                    approved_at = NOW(),
                    payment_status = 'paid'
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':approved_by' => $approved_by
        ]);
    }
    
    private function generateExpenseNumber() {
        $prefix = get_setting('expense_prefix', 'EXP-');
        $year = date('Y');
        $month = date('m');
        
        $sql = "SELECT COUNT(*) as count FROM expenses 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year, ':month' => $month]);
        $result = $stmt->fetch();
        
        $sequence = str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}{$year}{$month}-{$sequence}";
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM expense_categories WHERE is_active = 1 ORDER BY category_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getStats() {
        $stats = [];
        
        // Total expenses
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses";
        $stmt = $this->db->query($sql);
        $stats['total'] = $stmt->fetch()['total'];
        
        // This month
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses 
                WHERE MONTH(expense_date) = MONTH(NOW()) AND YEAR(expense_date) = YEAR(NOW())";
        $stmt = $this->db->query($sql);
        $stats['this_month'] = $stmt->fetch()['total'];
        
        // Pending approval (count and amount)
        $sql = "SELECT COUNT(*) as cnt, COALESCE(SUM(amount), 0) as total FROM expenses WHERE approved_by IS NULL";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetch();
        $stats['pending_count'] = $res['cnt'];
        $stats['pending_amount'] = $res['total'];

        // Paid expenses
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE payment_status = 'paid'";
        $stmt = $this->db->query($sql);
        $stats['paid_amount'] = $stmt->fetch()['total'];

        // Total count
        $sql = "SELECT COUNT(*) as cnt FROM expenses";
        $stmt = $this->db->query($sql);
        $stats['total_count'] = $stmt->fetch()['cnt'];

        // By category
        $sql = "SELECT ec.category_name, COALESCE(SUM(e.amount), 0) as total
                FROM expense_categories ec
                LEFT JOIN expenses e ON ec.id = e.category_id
                GROUP BY ec.id
                ORDER BY total DESC
                LIMIT 5";
        $stmt = $this->db->query($sql);
        $stats['by_category'] = $stmt->fetchAll();
        
        // Monthly trend
        $sql = "SELECT 
                    DATE_FORMAT(expense_date, '%Y-%m') as month,
                    SUM(amount) as total
                FROM expenses
                WHERE expense_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month";
        $stmt = $this->db->query($sql);
        $stats['monthly'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    public function getVendors() {
        $sql = "SELECT id, company_name FROM contacts 
                WHERE type IN ('vendor', 'both') AND status = 1 
                ORDER BY company_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
