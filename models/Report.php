<?php
class Report {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Sales Reports
    public function getSalesReport($filters = []) {
        $sql = "SELECT 
                    i.id,
                    i.invoice_number,
                    i.invoice_date,
                    c.company_name as customer_name,
                    i.subtotal,
                    i.tax_amount,
                    i.discount_amount,
                    i.total_amount,
                    i.paid_amount,
                    (i.total_amount - i.paid_amount) as balance,
                    i.status
                FROM invoices i
                LEFT JOIN contacts c ON i.customer_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND i.invoice_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND i.invoice_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        if (!empty($filters['customer_id'])) {
            $sql .= " AND i.customer_id = :customer_id";
            $params[':customer_id'] = $filters['customer_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND i.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY i.invoice_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getSalesSummary($filters = []) {
        $sql = "SELECT 
                    COUNT(*) as total_invoices,
                    COALESCE(SUM(total_amount), 0) as total_sales,
                    COALESCE(SUM(paid_amount), 0) as total_paid,
                    COALESCE(SUM(total_amount - paid_amount), 0) as total_outstanding,
                    COALESCE(SUM(tax_amount), 0) as total_tax,
                    COALESCE(SUM(discount_amount), 0) as total_discount,
                    COALESCE(AVG(total_amount), 0) as average_sale
                FROM invoices
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND invoice_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND invoice_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function getSalesByCustomer($filters = []) {
        $sql = "SELECT 
                    c.id,
                    c.company_name,
                    COUNT(i.id) as invoice_count,
                    COALESCE(SUM(i.total_amount), 0) as total_sales,
                    COALESCE(SUM(i.paid_amount), 0) as total_paid,
                    COALESCE(SUM(i.total_amount - i.paid_amount), 0) as outstanding
                FROM contacts c
                LEFT JOIN invoices i ON c.id = i.customer_id
                WHERE c.type IN ('customer', 'both')";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND (i.invoice_date >= :date_from OR i.invoice_date IS NULL)";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND (i.invoice_date <= :date_to OR i.invoice_date IS NULL)";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY c.id ORDER BY total_sales DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getSalesByProduct($filters = []) {
        $sql = "SELECT 
                    i.id,
                    i.item_name,
                    i.item_code,
                    COUNT(ii.id) as times_sold,
                    COALESCE(SUM(ii.quantity), 0) as total_quantity,
                    COALESCE(SUM(ii.total), 0) as total_revenue
                FROM items i
                LEFT JOIN invoice_items ii ON i.id = ii.item_id
                LEFT JOIN invoices inv ON ii.invoice_id = inv.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND (inv.invoice_date >= :date_from OR inv.invoice_date IS NULL)";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND (inv.invoice_date <= :date_to OR inv.invoice_date IS NULL)";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY i.id ORDER BY total_revenue DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getMonthlySalesTrend() {
        $sql = "SELECT 
                    DATE_FORMAT(invoice_date, '%Y-%m') as month,
                    DATE_FORMAT(invoice_date, '%b %Y') as month_name,
                    COUNT(*) as invoice_count,
                    COALESCE(SUM(total_amount), 0) as total_sales,
                    COALESCE(SUM(paid_amount), 0) as total_paid
                FROM invoices
                WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
                ORDER BY month ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Expense Reports
    public function getExpenseReport($filters = []) {
        $sql = "SELECT 
                    e.*,
                    ec.category_name,
                    c.company_name as vendor_name
                FROM expenses e
                LEFT JOIN expense_categories ec ON e.category_id = ec.id
                LEFT JOIN contacts c ON e.vendor_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND e.expense_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND e.expense_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND e.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        
        $sql .= " ORDER BY e.expense_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getExpenseSummary($filters = []) {
        $sql = "SELECT 
                    COUNT(*) as total_expenses,
                    COALESCE(SUM(amount), 0) as total_amount,
                    COALESCE(AVG(amount), 0) as average_amount,
                    COALESCE(MAX(amount), 0) as max_amount,
                    COALESCE(MIN(amount), 0) as min_amount
                FROM expenses
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND expense_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND expense_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function getExpensesByCategory($filters = []) {
        $sql = "SELECT 
                    ec.id,
                    ec.category_name,
                    COUNT(e.id) as expense_count,
                    COALESCE(SUM(e.amount), 0) as total_amount
                FROM expense_categories ec
                LEFT JOIN expenses e ON ec.id = e.category_id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND (e.expense_date >= :date_from OR e.expense_date IS NULL)";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND (e.expense_date <= :date_to OR e.expense_date IS NULL)";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY ec.id ORDER BY total_amount DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Purchase Reports
    public function getPurchaseReport($filters = []) {
        $sql = "SELECT 
                    po.*,
                    c.company_name as vendor_name,
                    (SELECT COALESCE(SUM(amount), 0) FROM vendor_payments WHERE po_id = po.id) as total_paid
                FROM purchase_orders po
                LEFT JOIN contacts c ON po.vendor_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND po.po_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND po.po_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        if (!empty($filters['vendor_id'])) {
            $sql .= " AND po.vendor_id = :vendor_id";
            $params[':vendor_id'] = $filters['vendor_id'];
        }
        
        $sql .= " ORDER BY po.po_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getPurchaseSummary($filters = []) {
        $sql = "SELECT 
                    COUNT(*) as total_pos,
                    COALESCE(SUM(total_amount), 0) as total_purchases,
                    COALESCE(SUM(paid_amount), 0) as total_paid,
                    COALESCE(SUM(total_amount - paid_amount), 0) as total_outstanding
                FROM purchase_orders
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND po_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND po_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    // Profit & Loss Statement
    public function getProfitLoss($filters = []) {
        $data = [];
        
        // Income
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total_income 
                FROM invoices 
                WHERE status = 'paid'";
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND invoice_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND invoice_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data['total_income'] = $stmt->fetch()['total_income'];
        
        // Expenses by category
        $sql = "SELECT 
                    ec.category_name,
                    COALESCE(SUM(e.amount), 0) as amount
                FROM expense_categories ec
                LEFT JOIN expenses e ON ec.id = e.category_id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND (e.expense_date >= :date_from OR e.expense_date IS NULL)";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND (e.expense_date <= :date_to OR e.expense_date IS NULL)";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY ec.id ORDER BY amount DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data['expenses'] = $stmt->fetchAll();
        
        // Total expenses
        $data['total_expenses'] = array_sum(array_column($data['expenses'], 'amount'));
        
        // Net profit
        $data['net_profit'] = $data['total_income'] - $data['total_expenses'];
        $data['profit_margin'] = $data['total_income'] > 0 ? 
            ($data['net_profit'] / $data['total_income']) * 100 : 0;
        
        return $data;
    }
    
    // Tax Report
    public function getTaxReport($filters = []) {
        $sql = "SELECT 
                    DATE_FORMAT(invoice_date, '%Y-%m') as month,
                    DATE_FORMAT(invoice_date, '%b %Y') as month_name,
                    COUNT(*) as invoice_count,
                    COALESCE(SUM(subtotal), 0) as total_sales,
                    COALESCE(SUM(tax_amount), 0) as total_tax,
                    COALESCE(SUM(total_amount), 0) as total_with_tax
                FROM invoices
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND invoice_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND invoice_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY DATE_FORMAT(invoice_date, '%Y-%m') ORDER BY month DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Inventory Report
    public function getInventoryReport() {
        $sql = "SELECT 
                    i.*,
                    c.category_name,
                    (SELECT COALESCE(SUM(quantity), 0) FROM stock_movements WHERE item_id = i.id AND movement_type = 'sale') as total_sold,
                    (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM stock_movements WHERE item_id = i.id AND movement_type = 'sale') as total_sales_value,
                    (current_stock * purchase_price) as stock_value,
                    (current_stock * sale_price) as potential_value
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE i.type = 'product' AND i.is_active = 1
                ORDER BY i.current_stock ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getInventorySummary() {
        $sql = "SELECT 
                    COUNT(*) as total_products,
                    COALESCE(SUM(current_stock), 0) as total_stock,
                    COALESCE(SUM(current_stock * purchase_price), 0) as total_cost_value,
                    COALESCE(SUM(current_stock * sale_price), 0) as total_sale_value,
                    SUM(CASE WHEN current_stock = 0 THEN 1 ELSE 0 END) as out_of_stock,
                    SUM(CASE WHEN current_stock <= reorder_level THEN 1 ELSE 0 END) as low_stock
                FROM items
                WHERE type = 'product' AND is_active = 1";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    // Customer Report
    public function getCustomerReport() {
        $sql = "SELECT 
                    c.id,
                    c.company_name,
                    c.contact_person,
                    c.email,
                    c.phone,
                    COUNT(i.id) as total_invoices,
                    COALESCE(SUM(i.total_amount), 0) as total_purchases,
                    COALESCE(SUM(i.paid_amount), 0) as total_paid,
                    COALESCE(SUM(i.total_amount - i.paid_amount), 0) as outstanding,
                    MAX(i.invoice_date) as last_purchase
                FROM contacts c
                LEFT JOIN invoices i ON c.id = i.customer_id
                WHERE c.type IN ('customer', 'both') AND c.status = 1
                GROUP BY c.id
                ORDER BY total_purchases DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get customers for dropdown
    public function getCustomers() {
        $sql = "SELECT id, company_name FROM contacts 
                WHERE type IN ('customer', 'both') AND status = 1 
                ORDER BY company_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get expense categories
    public function getExpenseCategories() {
        $sql = "SELECT * FROM expense_categories WHERE is_active = 1 ORDER BY category_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get vendors for dropdown
    public function getVendors() {
        $sql = "SELECT id, company_name FROM contacts 
                WHERE type IN ('vendor', 'both') AND status = 1 
                ORDER BY company_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
