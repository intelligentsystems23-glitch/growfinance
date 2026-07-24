<?php
class Invoice {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT i.*, c.company_name 
                FROM invoices i
                LEFT JOIN contacts c ON i.customer_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND i.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (i.invoice_number LIKE :search OR c.company_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND i.invoice_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND i.invoice_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY i.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT i.*, c.company_name, c.contact_person, c.email, c.phone, c.address, u.full_name as created_by_name 
                FROM invoices i
                LEFT JOIN contacts c ON i.customer_id = c.id
                LEFT JOIN users u ON i.created_by = u.id
                WHERE i.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getItems($invoice_id) {
        $sql = "SELECT ii.*, i.item_name, i.item_code 
                FROM invoice_items ii
                LEFT JOIN items i ON ii.item_id = i.id
                WHERE ii.invoice_id = :invoice_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate invoice number
            $invoice_number = $this->generateInvoiceNumber();
            
            // Insert invoice
            $sql = "INSERT INTO invoices (
                        invoice_number, customer_id, invoice_date, due_date,
                        subtotal, tax_amount, discount_amount, total_amount,
                        status, notes, created_by
                    ) VALUES (
                        :invoice_number, :customer_id, :invoice_date, :due_date,
                        :subtotal, :tax_amount, :discount_amount, :total_amount,
                        :status, :notes, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':invoice_number' => $invoice_number,
                ':customer_id' => $data['customer_id'],
                ':invoice_date' => $data['invoice_date'],
                ':due_date' => $data['due_date'],
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax_amount' => $data['tax_amount'] ?? 0,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':total_amount' => $data['total_amount'] ?? 0,
                ':status' => $data['status'] ?? 'draft',
                ':notes' => $data['notes'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            $invoice_id = $this->db->lastInsertId();
            
            // Insert items
            if (!empty($data['items'])) {
                $items = json_decode($data['items'], true);
                foreach ($items as $item) {
                    $this->addItem($invoice_id, $item);
                }
            }
            
            $this->db->commit();
            return $invoice_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function addItem($invoice_id, $item) {
        $sql = "INSERT INTO invoice_items (invoice_id, item_id, description, quantity, unit_price, total)
                VALUES (:invoice_id, :item_id, :description, :quantity, :unit_price, :total)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':invoice_id' => $invoice_id,
            ':item_id' => (!empty($item['item_id']) && $item['item_id'] !== 'null') ? $item['item_id'] : null,
            ':description' => $item['description'] ?? '',
            ':quantity' => $item['quantity'],
            ':unit_price' => $item['unit_price'],
            ':total' => $item['total']
        ]);
    }
    
    private function generateInvoiceNumber() {
        $prefix = get_setting('invoice_prefix', 'INV-');
        $year = date('Y');
        $month = date('m');
        
        $sql = "SELECT COUNT(*) as count FROM invoices 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year, ':month' => $month]);
        $result = $stmt->fetch();
        
        $sequence = str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}{$year}{$month}-{$sequence}";
    }
    
    public function update($id, $data) {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE invoices SET
                        customer_id = :customer_id,
                        invoice_date = :invoice_date,
                        due_date = :due_date,
                        subtotal = :subtotal,
                        tax_amount = :tax_amount,
                        discount_amount = :discount_amount,
                        total_amount = :total_amount,
                        status = :status,
                        notes = :notes
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':customer_id' => $data['customer_id'],
                ':invoice_date' => $data['invoice_date'],
                ':due_date' => $data['due_date'],
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax_amount' => $data['tax_amount'] ?? 0,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':total_amount' => $data['total_amount'] ?? 0,
                ':status' => $data['status'] ?? 'draft',
                ':notes' => $data['notes'] ?? null
            ]);
            
            // Delete old items
            $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE invoice_id = :id");
            $stmt->execute([':id' => $id]);
            
            // Add new items
            if (!empty($data['items'])) {
                $items = json_decode($data['items'], true);
                foreach ($items as $item) {
                    $this->addItem($id, $item);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM invoices WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function recordPayment($data) {
        try {
            $this->db->beginTransaction();
            
            $payment_number = 'PAY-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $sql = "INSERT INTO payments (
                        payment_number, invoice_id, customer_id, payment_date,
                        amount, payment_method, reference_number, notes, created_by
                    ) VALUES (
                        :payment_number, :invoice_id, :customer_id, :payment_date,
                        :amount, :payment_method, :reference_number, :notes, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':payment_number' => $payment_number,
                ':invoice_id' => $data['invoice_id'],
                ':customer_id' => $data['customer_id'],
                ':payment_date' => $data['payment_date'],
                ':amount' => $data['amount'],
                ':payment_method' => $data['payment_method'] ?? null,
                ':reference_number' => $data['reference_number'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            // Update invoice paid amount
            $sql = "UPDATE invoices SET 
                        paid_amount = paid_amount + :amount,
                        status = CASE 
                            WHEN paid_amount + :amount >= total_amount THEN 'paid'
                            ELSE status
                        END
                    WHERE id = :invoice_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':amount' => $data['amount'],
                ':invoice_id' => $data['invoice_id']
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getPayments($invoice_id) {
        $sql = "SELECT * FROM payments WHERE invoice_id = :invoice_id ORDER BY payment_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }
    
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total_count,
                    SUM(total_amount) as total_amount,
                    SUM(paid_amount) as paid_amount,
                    SUM(total_amount - paid_amount) as outstanding_amount,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_count,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent_count,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_count
                FROM invoices";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
