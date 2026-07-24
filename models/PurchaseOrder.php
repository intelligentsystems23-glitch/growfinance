<?php
class PurchaseOrder {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT po.*, 
                c.company_name as vendor_name,
                u.username as created_by_name,
                (SELECT SUM(amount) FROM vendor_payments WHERE po_id = po.id) as total_paid
                FROM purchase_orders po
                LEFT JOIN contacts c ON po.vendor_id = c.id
                LEFT JOIN users u ON po.created_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (po.po_number LIKE :search OR c.company_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['vendor_id'])) {
            $sql .= " AND po.vendor_id = :vendor_id";
            $params[':vendor_id'] = $filters['vendor_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND po.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['payment_status'])) {
            $sql .= " AND po.payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND po.po_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND po.po_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY po.po_date DESC, po.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT po.*, 
                c.company_name as vendor_name,
                c.contact_person,
                c.email as vendor_email,
                c.phone as vendor_phone,
                c.address as vendor_address,
                u.username as created_by_name
                FROM purchase_orders po
                LEFT JOIN contacts c ON po.vendor_id = c.id
                LEFT JOIN users u ON po.created_by = u.id
                WHERE po.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getItems($po_id) {
        $sql = "SELECT poi.*, i.item_name, i.item_code, i.unit
                FROM purchase_order_items poi
                LEFT JOIN items i ON poi.item_id = i.id
                WHERE poi.po_id = :po_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':po_id' => $po_id]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate PO number
            $po_number = $this->generatePONumber();
            
            $sql = "INSERT INTO purchase_orders (
                        po_number, vendor_id, po_date, expected_date,
                        shipping_address, subtotal, tax_amount, shipping_cost,
                        discount_type, discount_value, discount_amount,
                        total_amount, status, payment_status, notes, created_by
                    ) VALUES (
                        :po_number, :vendor_id, :po_date, :expected_date,
                        :shipping_address, :subtotal, :tax_amount, :shipping_cost,
                        :discount_type, :discount_value, :discount_amount,
                        :total_amount, :status, :payment_status, :notes, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':po_number' => $po_number,
                ':vendor_id' => $data['vendor_id'],
                ':po_date' => $data['po_date'],
                ':expected_date' => $data['expected_date'] ?? null,
                ':shipping_address' => $data['shipping_address'] ?? null,
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax_amount' => $data['tax_amount'] ?? 0,
                ':shipping_cost' => $data['shipping_cost'] ?? 0,
                ':discount_type' => $data['discount_type'] ?? 'percentage',
                ':discount_value' => $data['discount_value'] ?? 0,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':total_amount' => $data['total_amount'] ?? 0,
                ':status' => $data['status'] ?? 'draft',
                ':payment_status' => 'unpaid',
                ':notes' => $data['notes'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            $po_id = $this->db->lastInsertId();
            
            // Add items
            if (!empty($data['items'])) {
                $items = json_decode($data['items'], true);
                $this->addItems($po_id, $items);
            }
            
            $this->db->commit();
            return $po_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE purchase_orders SET
                        vendor_id = :vendor_id,
                        po_date = :po_date,
                        expected_date = :expected_date,
                        shipping_address = :shipping_address,
                        subtotal = :subtotal,
                        tax_amount = :tax_amount,
                        shipping_cost = :shipping_cost,
                        discount_type = :discount_type,
                        discount_value = :discount_value,
                        discount_amount = :discount_amount,
                        total_amount = :total_amount,
                        status = :status,
                        notes = :notes
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':vendor_id' => $data['vendor_id'],
                ':po_date' => $data['po_date'],
                ':expected_date' => $data['expected_date'] ?? null,
                ':shipping_address' => $data['shipping_address'] ?? null,
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax_amount' => $data['tax_amount'] ?? 0,
                ':shipping_cost' => $data['shipping_cost'] ?? 0,
                ':discount_type' => $data['discount_type'] ?? 'percentage',
                ':discount_value' => $data['discount_value'] ?? 0,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':total_amount' => $data['total_amount'] ?? 0,
                ':status' => $data['status'] ?? 'draft',
                ':notes' => $data['notes'] ?? null
            ]);
            
            // Update items
            if (!empty($data['items'])) {
                // Delete existing items
                $stmt = $this->db->prepare("DELETE FROM purchase_order_items WHERE po_id = :po_id");
                $stmt->execute([':po_id' => $id]);
                
                // Add new items
                $items = json_decode($data['items'], true);
                $this->addItems($id, $items);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function addItems($po_id, $items) {
        $sql = "INSERT INTO purchase_order_items (
                    po_id, item_id, description, quantity,
                    unit_price, tax_rate, total
                ) VALUES (
                    :po_id, :item_id, :description, :quantity,
                    :unit_price, :tax_rate, :total
                )";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($items as $item) {
            $stmt->execute([
                ':po_id' => $po_id,
                ':item_id' => (!empty($item['item_id']) && $item['item_id'] !== 'null') ? $item['item_id'] : null,
                ':description' => $item['description'],
                ':quantity' => $item['quantity'],
                ':unit_price' => $item['unit_price'],
                ':tax_rate' => $item['tax_rate'] ?? 0,
                ':total' => $item['total']
            ]);
        }
    }
    
    private function generatePONumber() {
        $prefix = get_setting('po_prefix', 'PO-');
        $year = date('Y');
        $month = date('m');
        
        $sql = "SELECT COUNT(*) as count FROM purchase_orders 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year, ':month' => $month]);
        $result = $stmt->fetch();
        
        $sequence = str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}{$year}{$month}-{$sequence}";
    }
    
    public function delete($id) {
        $sql = "UPDATE purchase_orders SET status = 'cancelled' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function approve($id, $approved_by) {
        $sql = "UPDATE purchase_orders SET 
                    status = 'sent',
                    approved_by = :approved_by,
                    approved_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':approved_by' => $approved_by
        ]);
    }
    
    public function receiveGoods($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate GRN number
            $grn_number = 'GRN-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $sql = "INSERT INTO goods_receipts (
                        grn_number, po_id, receipt_date, received_by, notes, status
                    ) VALUES (
                        :grn_number, :po_id, :receipt_date, :received_by, :notes, 'completed'
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':grn_number' => $grn_number,
                ':po_id' => $data['po_id'],
                ':receipt_date' => $data['receipt_date'],
                ':received_by' => $_SESSION['user_id'] ?? 1,
                ':notes' => $data['notes'] ?? null
            ]);
            
            $grn_id = $this->db->lastInsertId();
            
            // Add received items
            $items = json_decode($data['items'], true);
            foreach ($items as $item) {
                $sql = "INSERT INTO goods_receipt_items (grn_id, po_item_id, quantity_received)
                        VALUES (:grn_id, :po_item_id, :quantity)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':grn_id' => $grn_id,
                    ':po_item_id' => $item['po_item_id'],
                    ':quantity' => $item['quantity']
                ]);
                
                // Update PO item received quantity
                $sql = "UPDATE purchase_order_items 
                        SET received_quantity = received_quantity + :quantity 
                        WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':quantity' => $item['quantity'],
                    ':id' => $item['po_item_id']
                ]);
                
                // Update product stock
                if (!empty($item['item_id'])) {
                    $sql = "UPDATE items 
                            SET current_stock = current_stock + :quantity 
                            WHERE id = :item_id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':quantity' => $item['quantity'],
                        ':item_id' => $item['item_id']
                    ]);
                }
            }
            
            // Check if all items received
            $sql = "SELECT 
                        COUNT(*) as total_items,
                        SUM(CASE WHEN poi.received_quantity >= poi.quantity THEN 1 ELSE 0 END) as fully_received
                    FROM purchase_order_items poi
                    WHERE poi.po_id = :po_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':po_id' => $data['po_id']]);
            $result = $stmt->fetch();
            
            if ($result['total_items'] == $result['fully_received']) {
                $sql = "UPDATE purchase_orders SET status = 'received' WHERE id = :po_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':po_id' => $data['po_id']]);
            }
            
            $this->db->commit();
            return $grn_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function recordPayment($data) {
        try {
            $this->db->beginTransaction();
            
            $payment_number = 'PAY-V-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $sql = "INSERT INTO vendor_payments (
                        payment_number, vendor_id, po_id, payment_date,
                        amount, payment_method, reference_number, notes, created_by
                    ) VALUES (
                        :payment_number, :vendor_id, :po_id, :payment_date,
                        :amount, :payment_method, :reference_number, :notes, :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':payment_number' => $payment_number,
                ':vendor_id' => $data['vendor_id'],
                ':po_id' => $data['po_id'],
                ':payment_date' => $data['payment_date'],
                ':amount' => $data['amount'],
                ':payment_method' => $data['payment_method'],
                ':reference_number' => $data['reference_number'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':created_by' => $_SESSION['user_id'] ?? 1
            ]);
            
            // Update PO paid amount
            $sql = "UPDATE purchase_orders SET 
                        paid_amount = paid_amount + :amount,
                        payment_status = CASE 
                            WHEN paid_amount + :amount >= total_amount THEN 'paid'
                            WHEN paid_amount + :amount > 0 THEN 'partial'
                            ELSE 'unpaid'
                        END
                    WHERE id = :po_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':amount' => $data['amount'],
                ':po_id' => $data['po_id']
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getStats() {
        $stats = [];
        
        $sql = "SELECT 
                    COUNT(*) as total_pos,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'received' THEN 1 ELSE 0 END) as received,
                    SUM(total_amount) as total_amount,
                    SUM(paid_amount) as paid_amount
                FROM purchase_orders";
        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch();
        
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
