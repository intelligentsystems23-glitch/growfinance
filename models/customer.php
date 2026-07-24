<?php
/**
 * Customer Model
 * Location: C:\xampp\htdocs\erp_system\models\Customer.php
 */

class Customer {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM invoices WHERE customer_id = c.id) as total_invoices,
                (SELECT SUM(total_amount) FROM invoices WHERE customer_id = c.id) as total_revenue,
                (SELECT SUM(total_amount - paid_amount) FROM invoices WHERE customer_id = c.id AND status != 'paid') as outstanding_balance
                FROM contacts c 
                WHERE c.type IN ('customer', 'both')";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (c.company_name LIKE :search 
                      OR c.contact_person LIKE :search 
                      OR c.email LIKE :search 
                      OR c.customer_code LIKE :search
                      OR c.phone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND c.city = :city";
            $params[':city'] = $filters['city'];
        }
        
        if (!empty($filters['country'])) {
            $sql .= " AND c.country = :country";
            $params[':country'] = $filters['country'];
        }
        
        $sql .= " ORDER BY c.company_name ASC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$filters['limit'];
        }
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            if ($key == ':limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT c.*,
                (SELECT COUNT(*) FROM invoices WHERE customer_id = c.id) as total_invoices,
                (SELECT SUM(total_amount) FROM invoices WHERE customer_id = c.id) as total_revenue,
                (SELECT SUM(total_amount - paid_amount) FROM invoices WHERE customer_id = c.id AND status IN ('sent', 'overdue')) as outstanding_balance,
                (SELECT SUM(paid_amount) FROM invoices WHERE customer_id = c.id) as total_paid
                FROM contacts c 
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate customer code
            $customer_code = $this->generateCustomerCode();
            
            $sql = "INSERT INTO contacts (
                        customer_code, type, company_name, contact_person,
                        email, phone, mobile, website, address, city,
                        state, postal_code, country, currency, payment_terms,
                        credit_limit, tax_number, notes, customer_since, status, created_at
                    ) VALUES (
                        :customer_code, :type, :company_name, :contact_person,
                        :email, :phone, :mobile, :website, :address, :city,
                        :state, :postal_code, :country, :currency, :payment_terms,
                        :credit_limit, :tax_number, :notes, :customer_since, :status, NOW()
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':customer_code' => $customer_code,
                ':type' => $data['type'] ?? 'customer',
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
                ':currency' => $data['currency'] ?? 'USD',
                ':payment_terms' => $data['payment_terms'] ?? 'Net 30',
                ':credit_limit' => $data['credit_limit'] ?? 0,
                ':tax_number' => $data['tax_number'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':customer_since' => $data['customer_since'] ?? date('Y-m-d'),
                ':status' => $data['status'] ?? 1
            ]);
            
            $customer_id = $this->db->lastInsertId();
            
            // Add additional contacts if provided
            if (!empty($data['contacts'])) {
                $this->addContacts($customer_id, $data['contacts']);
            }
            
            // Log activity
            $this->logActivity($customer_id, 'created', 'Customer created');
            
            $this->db->commit();
            return $customer_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE contacts SET
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
                        currency = :currency,
                        payment_terms = :payment_terms,
                        credit_limit = :credit_limit,
                        tax_number = :tax_number,
                        notes = :notes,
                        status = :status
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
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
                ':currency' => $data['currency'] ?? 'USD',
                ':payment_terms' => $data['payment_terms'] ?? 'Net 30',
                ':credit_limit' => $data['credit_limit'] ?? 0,
                ':tax_number' => $data['tax_number'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':status' => $data['status'] ?? 1
            ]);
            
            // Log activity
            $this->logActivity($id, 'updated', 'Customer information updated');
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function delete($id) {
        // Soft delete - just change status to 0
        $sql = "UPDATE contacts SET status = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function hardDelete($id) {
        // Actually delete the record
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    private function generateCustomerCode() {
        $year = date('Y');
        
        $sql = "SELECT COUNT(*) as count FROM contacts WHERE type IN ('customer', 'both')";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        
        $sequence = str_pad($result['count'] + 1, 5, '0', STR_PAD_LEFT);
        return "CUST-{$year}-{$sequence}";
    }
    
    private function addContacts($customer_id, $contacts) {
        $sql = "INSERT INTO customer_contacts (customer_id, contact_name, position, email, phone, mobile, is_primary) 
                VALUES (:customer_id, :contact_name, :position, :email, :phone, :mobile, :is_primary)";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($contacts as $contact) {
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':contact_name' => $contact['contact_name'],
                ':position' => $contact['position'] ?? null,
                ':email' => $contact['email'] ?? null,
                ':phone' => $contact['phone'] ?? null,
                ':mobile' => $contact['mobile'] ?? null,
                ':is_primary' => $contact['is_primary'] ?? 0
            ]);
        }
    }
    
    private function logActivity($customer_id, $type, $description, $ref_id = null, $ref_type = null) {
        $sql = "INSERT INTO customer_activities (customer_id, activity_type, description, reference_id, reference_type, created_by) 
                VALUES (:customer_id, :activity_type, :description, :reference_id, :reference_type, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customer_id,
            ':activity_type' => $type,
            ':description' => $description,
            ':reference_id' => $ref_id,
            ':reference_type' => $ref_type,
            ':created_by' => $_SESSION['user_id'] ?? 1
        ]);
    }
    
    public function getCustomerInvoices($customer_id) {
        $sql = "SELECT i.*, 
                (i.total_amount - i.paid_amount) as balance
                FROM invoices i 
                WHERE i.customer_id = :customer_id 
                ORDER BY i.invoice_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':customer_id' => $customer_id]);
        return $stmt->fetchAll();
    }
    
    public function getCustomerPayments($customer_id) {
        $sql = "SELECT p.*, p.payment_method as method_name, i.invoice_number
                FROM payments p
                LEFT JOIN invoices i ON p.invoice_id = i.id
                WHERE p.customer_id = :customer_id
                ORDER BY p.payment_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':customer_id' => $customer_id]);
        return $stmt->fetchAll();
    }
    
    public function getCustomerContacts($customer_id) {
        $sql = "SELECT * FROM customer_contacts WHERE customer_id = :customer_id ORDER BY is_primary DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':customer_id' => $customer_id]);
        return $stmt->fetchAll();
    }
    
    public function getCustomerNotes($customer_id) {
        $sql = "SELECT n.*, u.username 
                FROM customer_notes n
                LEFT JOIN users u ON n.created_by = u.id
                WHERE n.customer_id = :customer_id
                ORDER BY n.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':customer_id' => $customer_id]);
        return $stmt->fetchAll();
    }
    
    public function addNote($customer_id, $note) {
        $sql = "INSERT INTO customer_notes (customer_id, note, created_by) VALUES (:customer_id, :note, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':customer_id' => $customer_id,
            ':note' => $note,
            ':created_by' => $_SESSION['user_id'] ?? 1
        ]);
    }
    
    public function getCustomerActivities($customer_id, $limit = 20) {
        $sql = "SELECT a.*, u.username 
                FROM customer_activities a
                LEFT JOIN users u ON a.created_by = u.id
                WHERE a.customer_id = :customer_id
                ORDER BY a.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCustomerStats() {
        $stats = [];
        
        // Total customers
        $sql = "SELECT COUNT(*) as total FROM contacts WHERE type IN ('customer', 'both') AND status = 1";
        $stmt = $this->db->query($sql);
        $stats['total'] = $stmt->fetch()['total'];
        
        // Active customers (with invoices in last 30 days)
        $sql = "SELECT COUNT(DISTINCT customer_id) as active 
                FROM invoices 
                WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->db->query($sql);
        $stats['active'] = $stmt->fetch()['active'];
        
        // New customers this month
        $sql = "SELECT COUNT(*) as new_customers 
                FROM contacts 
                WHERE type IN ('customer', 'both') 
                AND MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW())";
        $stmt = $this->db->query($sql);
        $stats['new_this_month'] = $stmt->fetch()['new_customers'];
        
        // Total outstanding balance
        $sql = "SELECT SUM(i.total_amount - i.paid_amount) as outstanding
                FROM invoices i
                WHERE i.status IN ('sent', 'overdue')";
        $stmt = $this->db->query($sql);
        $stats['outstanding'] = $stmt->fetch()['outstanding'] ?? 0;
        
        // Top customers by revenue
        $sql = "SELECT c.id, c.company_name, SUM(i.total_amount) as revenue
                FROM contacts c
                JOIN invoices i ON c.id = i.customer_id
                WHERE i.status = 'paid'
                GROUP BY c.id
                ORDER BY revenue DESC
                LIMIT 5";
        $stmt = $this->db->query($sql);
        $stats['top_customers'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    public function getCities() {
        $sql = "SELECT DISTINCT city FROM contacts WHERE city IS NOT NULL AND city != '' ORDER BY city";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getCountries() {
        $sql = "SELECT DISTINCT country FROM contacts WHERE country IS NOT NULL ORDER BY country";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
