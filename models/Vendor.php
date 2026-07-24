<?php
class Vendor {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT c.*, vc.category_name,
                (SELECT COUNT(*) FROM purchase_orders WHERE vendor_id = c.id) as total_pos,
                (SELECT SUM(total_amount) FROM purchase_orders WHERE vendor_id = c.id) as total_purchases,
                (SELECT COALESCE(SUM(balance), 0) FROM vendor_transactions WHERE vendor_id = c.id ORDER BY id DESC LIMIT 1) as outstanding_balance
                FROM contacts c
                LEFT JOIN vendor_categories vc ON c.vendor_category_id = vc.id
                WHERE c.type IN ('vendor', 'both')";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (c.company_name LIKE :search OR c.vendor_code LIKE :search OR c.email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND c.vendor_category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        
        if (isset($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY c.company_name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT c.*, vc.category_name,
                (SELECT COUNT(*) FROM purchase_orders WHERE vendor_id = c.id) as total_pos,
                (SELECT SUM(total_amount) FROM purchase_orders WHERE vendor_id = c.id AND status = 'received') as total_purchases,
                (SELECT COALESCE(SUM(amount), 0) FROM vendor_transactions WHERE vendor_id = c.id AND transaction_type = 'payment') as total_paid
                FROM contacts c
                LEFT JOIN vendor_categories vc ON c.vendor_category_id = vc.id
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate vendor code
            $vendor_code = $this->generateVendorCode();
            
            $sql = "INSERT INTO contacts (
                        vendor_code, type, vendor_category_id, company_name,
                        contact_person, email, phone, mobile, website,
                        address, city, state, postal_code, country,
                        tax_id, payment_terms, credit_limit,
                        bank_name, bank_account_number, bank_routing_number,
                        notes, status, created_at
                    ) VALUES (
                        :vendor_code, :type, :vendor_category_id, :company_name,
                        :contact_person, :email, :phone, :mobile, :website,
                        :address, :city, :state, :postal_code, :country,
                        :tax_id, :payment_terms, :credit_limit,
                        :bank_name, :bank_account_number, :bank_routing_number,
                        :notes, :status, NOW()
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':vendor_code' => $vendor_code,
                ':type' => $data['type'] ?? 'vendor',
                ':vendor_category_id' => !empty($data['vendor_category_id']) ? $data['vendor_category_id'] : null,
                ':company_name' => $data['company_name'],
                ':contact_person' => $data['contact_person'],
                ':email' => $data['email'],
                ':phone' => $data['phone'] ?? null,
                ':mobile' => $data['mobile'] ?? null,
                ':website' => $data['website'] ?? null,
                ':address' => $data['address'] ?? null,
                ':city' => $data['city'] ?? null,
                ':state' => $data['state'] ?? null,
                ':postal_code' => $data['postal_code'] ?? null,
                ':country' => $data['country'] ?? 'USA',
                ':tax_id' => $data['tax_id'] ?? null,
                ':payment_terms' => $data['payment_terms'] ?? 'Net 30',
                ':credit_limit' => $data['credit_limit'] ?? 0,
                ':bank_name' => $data['bank_name'] ?? null,
                ':bank_account_number' => $data['bank_account_number'] ?? null,
                ':bank_routing_number' => $data['bank_routing_number'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':status' => $data['status'] ?? 1
            ]);
            
            $vendor_id = $this->db->lastInsertId();
            
            $this->db->commit();
            return $vendor_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE contacts SET
                    vendor_category_id = :vendor_category_id,
                    company_name = :company_name,
                    contact_person = :contact_person,
                    email = :email,
                    phone = :phone,
                    mobile = :mobile,
                    website = :website,
                    address = :address,
                    city = :city,
                    state = :state,
                    postal_code = :postal_code,
                    country = :country,
                    tax_id = :tax_id,
                    payment_terms = :payment_terms,
                    credit_limit = :credit_limit,
                    bank_name = :bank_name,
                    bank_account_number = :bank_account_number,
                    bank_routing_number = :bank_routing_number,
                    notes = :notes,
                    status = :status
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':vendor_category_id' => !empty($data['vendor_category_id']) ? $data['vendor_category_id'] : null,
            ':company_name' => $data['company_name'],
            ':contact_person' => $data['contact_person'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':mobile' => $data['mobile'] ?? null,
            ':website' => $data['website'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':state' => $data['state'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
            ':country' => $data['country'] ?? 'USA',
            ':tax_id' => $data['tax_id'] ?? null,
            ':payment_terms' => $data['payment_terms'] ?? 'Net 30',
            ':credit_limit' => $data['credit_limit'] ?? 0,
            ':bank_name' => $data['bank_name'] ?? null,
            ':bank_account_number' => $data['bank_account_number'] ?? null,
            ':bank_routing_number' => $data['bank_routing_number'] ?? null,
            ':notes' => $data['notes'] ?? null,
            ':status' => $data['status'] ?? 1
        ]);
    }
    
    public function delete($id) {
        $sql = "UPDATE contacts SET status = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    private function generateVendorCode() {
        $sql = "SELECT COUNT(*) as count FROM contacts WHERE type IN ('vendor', 'both')";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        
        return 'VEND-' . str_pad($result['count'] + 1, 5, '0', STR_PAD_LEFT);
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM vendor_categories WHERE is_active = 1 ORDER BY category_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getVendorTransactions($vendor_id) {
        $sql = "SELECT * FROM vendor_transactions 
                WHERE vendor_id = :vendor_id 
                ORDER BY transaction_date DESC, id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vendor_id' => $vendor_id]);
        return $stmt->fetchAll();
    }
    
    public function getVendorPOs($vendor_id) {
        $sql = "SELECT * FROM purchase_orders 
                WHERE vendor_id = :vendor_id 
                ORDER BY po_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vendor_id' => $vendor_id]);
        return $stmt->fetchAll();
    }
    
    public function getStats() {
        $stats = [];
        
        // Total vendors
        $sql = "SELECT COUNT(*) as total FROM contacts WHERE type IN ('vendor', 'both') AND status = 1";
        $stmt = $this->db->query($sql);
        $stats['total'] = $stmt->fetch()['total'];
        
        // Active vendors (with POs in last 30 days)
        $sql = "SELECT COUNT(DISTINCT vendor_id) as active 
                FROM purchase_orders 
                WHERE po_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->db->query($sql);
        $stats['active'] = $stmt->fetch()['active'] ?? 0;
        
        // New vendors this month
        $sql = "SELECT COUNT(*) as new_vendors 
                FROM contacts 
                WHERE type IN ('vendor', 'both') 
                AND MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW())";
        $stmt = $this->db->query($sql);
        $stats['new_this_month'] = $stmt->fetch()['new_vendors'] ?? 0;
        
        // Total outstanding payables
        $sql = "SELECT COALESCE(SUM(balance), 0) as outstanding FROM vendor_transactions WHERE transaction_type = 'purchase'";
        $stmt = $this->db->query($sql);
        $stats['outstanding'] = $stmt->fetch()['outstanding'] ?? 0;
        
        return $stats;
    }
}
