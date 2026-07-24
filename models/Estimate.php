<?php
class Estimate {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT e.*, c.company_name 
                FROM estimates e
                LEFT JOIN contacts c ON e.customer_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['customer_id'])) {
            $sql .= " AND e.customer_id = :customer_id";
            $params[':customer_id'] = $filters['customer_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.estimate_number LIKE :search OR c.company_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT e.*, c.company_name, c.email, c.phone, c.address
                FROM estimates e
                LEFT JOIN contacts c ON e.customer_id = c.id
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getItems($estimate_id) {
        $sql = "SELECT ei.*, i.item_name, i.item_code
                FROM estimate_items ei
                LEFT JOIN items i ON ei.item_id = i.id
                WHERE ei.estimate_id = :estimate_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estimate_id' => $estimate_id]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            $estimate_number = $this->generateEstimateNumber();
            
            $sql = "INSERT INTO estimates (
                        estimate_number, customer_id, estimate_date, expiry_date,
                        subtotal, tax_amount, discount_type, discount_value,
                        discount_amount, total_amount, status, notes, terms, created_by
                    ) VALUES (
                        :estimate_number, :customer_id, :estimate_date, :expiry_date,
                        :subtotal, :tax_amount, :discount_type, :discount_value,
                        :discount_amount, :total_amount, :status, :notes, :terms, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':estimate_number' => $estimate_number,
                ':customer_id' => $data['customer_id'],
                ':estimate_date' => $data['estimate_date'],
                ':expiry_date' => $data['expiry_date'] ?? null,
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax_amount' => $data['tax_amount'] ?? 0,
                ':discount_type' => $data['discount_type'] ?? 'percentage',
                ':discount_value' => $data['discount_value'] ?? 0,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':total_amount' => $data['total_amount'] ?? 0,
                ':status' => $data['status'] ?? 'draft',
                ':notes' => $data['notes'] ?? null,
                ':terms' => $data['terms'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            $estimate_id = $this->db->lastInsertId();
            
            if (!empty($data['items'])) {
                $items = json_decode($data['items'], true);
                foreach ($items as $item) {
                    $this->addItem($estimate_id, $item);
                }
            }
            
            $this->db->commit();
            return $estimate_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function addItem($estimate_id, $item) {
        $sql = "INSERT INTO estimate_items (
                    estimate_id, item_id, description, quantity,
                    unit_price, tax_rate, discount_percent, total
                ) VALUES (
                    :estimate_id, :item_id, :description, :quantity,
                    :unit_price, :tax_rate, :discount_percent, :total
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estimate_id' => $estimate_id,
            ':item_id' => (!empty($item['item_id']) && $item['item_id'] !== 'null') ? $item['item_id'] : null,
            ':description' => $item['description'] ?? '',
            ':quantity' => $item['quantity'],
            ':unit_price' => $item['unit_price'],
            ':tax_rate' => $item['tax_rate'] ?? 0,
            ':discount_percent' => $item['discount_percent'] ?? 0,
            ':total' => $item['total']
        ]);
    }
    
    private function generateEstimateNumber() {
        $prefix = get_setting('estimate_prefix', 'EST-');
        $year = date('Y');
        $month = date('m');
        
        $sql = "SELECT COUNT(*) as count FROM estimates 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year, ':month' => $month]);
        $result = $stmt->fetch();
        
        $sequence = str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}{$year}{$month}-{$sequence}";
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE estimates SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM estimates WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function convertToInvoice($id) {
        try {
            $this->db->beginTransaction();
            
            $estimate = $this->getById($id);
            $items = $this->getItems($id);
            
            // Generate invoice number
            $year = date('Y');
            $month = date('m');
            $sql = "SELECT COUNT(*) as count FROM invoices WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':year' => $year, ':month' => $month]);
            $result = $stmt->fetch();
            $invoice_number = "INV-{$year}{$month}-" . str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
            
            // Create invoice
            $sql = "INSERT INTO invoices (
                        invoice_number, customer_id, invoice_date, due_date,
                        subtotal, tax_amount, discount_type, discount_value,
                        discount_amount, total_amount, status, notes
                    ) VALUES (
                        :invoice_number, :customer_id, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY),
                        :subtotal, :tax_amount, :discount_type, :discount_value,
                        :discount_amount, :total_amount, 'draft', :notes
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':invoice_number' => $invoice_number,
                ':customer_id' => $estimate['customer_id'],
                ':subtotal' => $estimate['subtotal'],
                ':tax_amount' => $estimate['tax_amount'],
                ':discount_type' => $estimate['discount_type'],
                ':discount_value' => $estimate['discount_value'],
                ':discount_amount' => $estimate['discount_amount'],
                ':total_amount' => $estimate['total_amount'],
                ':notes' => $estimate['notes']
            ]);
            
            $invoice_id = $this->db->lastInsertId();
            
            // Copy items
            foreach ($items as $item) {
                $sql = "INSERT INTO invoice_items (invoice_id, item_id, description, quantity, unit_price, total)
                        VALUES (:invoice_id, :item_id, :description, :quantity, :unit_price, :total)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':invoice_id' => $invoice_id,
                    ':item_id' => (!empty($item['item_id']) && $item['item_id'] !== 'null') ? $item['item_id'] : null,
                    ':description' => $item['description'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['unit_price'],
                    ':total' => $item['total']
                ]);
            }
            
            // Update estimate status
            $this->updateStatus($id, 'invoiced');
            
            $this->db->commit();
            return $invoice_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
