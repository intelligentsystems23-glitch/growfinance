<?php
require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $data = $this->getDashboardData();
        
        $data['title'] = 'Dashboard';
        
        $this->render('dashboard/index', $data);
    }
    
    private function getDashboardData() {
        $data = [];
        
        // Financial Overview
        $data['financial'] = $this->getFinancialOverview();
        
        // Sales Statistics
        $data['sales'] = $this->getSalesStats();
        
        // Recent Activities
        $data['recent_invoices'] = $this->getRecentInvoices();
        $data['recent_payments'] = $this->getRecentPayments();
        $data['recent_customers'] = $this->getRecentCustomers();
        
        // Charts Data
        $data['monthly_sales'] = $this->getMonthlySales();
        $data['top_customers'] = $this->getTopCustomers();
        $data['invoice_status'] = $this->getInvoiceStatusDistribution();
        
        // Counts
        $data['counts'] = $this->getCounts();
        
        // Alerts
        $data['alerts'] = $this->getAlerts();
        
        // Onboarding Checklist
        $data['onboarding'] = $this->getOnboardingData();

        return $data;
    }
    
    private function getFinancialOverview() {
        // Current month
        $month = date('m');
        $year = date('Y');
        
        // Total Revenue (Paid Invoices)
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices WHERE status = 'paid'";
        $stmt = $this->db->query($sql);
        $total_revenue = $stmt->fetch()['total'];
        
        // This Month Revenue
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices 
                WHERE status = 'paid' AND MONTH(invoice_date) = :month AND YEAR(invoice_date) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':month' => $month, ':year' => $year]);
        $month_revenue = $stmt->fetch()['total'];
        
        // Outstanding Payments
        $sql = "SELECT COALESCE(SUM(total_amount - paid_amount), 0) as total 
                FROM invoices WHERE status IN ('sent', 'overdue')";
        $stmt = $this->db->query($sql);
        $outstanding = $stmt->fetch()['total'];
        
        // Overdue Payments
        $sql = "SELECT COALESCE(SUM(total_amount - paid_amount), 0) as total 
                FROM invoices WHERE status = 'overdue'";
        $stmt = $this->db->query($sql);
        $overdue = $stmt->fetch()['total'];
        
        // Total Expenses
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses";
        $stmt = $this->db->query($sql);
        $expenses = $stmt->fetch()['total'];
        
        // This Month Expenses
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses 
                WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':month' => $month, ':year' => $year]);
        $month_expenses = $stmt->fetch()['total'];
        
        // Net Profit
        $net_profit = $total_revenue - $expenses;
        $month_profit = $month_revenue - $month_expenses;
        
        // Total Cash / Float from active bank accounts
        $sql = "SELECT COALESCE(SUM(current_balance), 0) as total FROM bank_accounts WHERE is_active = 1";
        $stmt = $this->db->query($sql);
        $total_cash = $stmt->fetch()['total'];

        // Pending Auditor Checks (pending approvals)
        $sql_exp = "SELECT COUNT(*) as count FROM expenses WHERE approved_by IS NULL";
        $stmt_exp = $this->db->query($sql_exp);
        $pending_expenses = $stmt_exp->fetch()['count'];

        $sql_po = "SELECT COUNT(*) as count FROM purchase_orders WHERE approved_by IS NULL";
        $stmt_po = $this->db->query($sql_po);
        $pending_po = $stmt_po->fetch()['count'];

        $sql_leave = "SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'";
        $stmt_leave = $this->db->query($sql_leave);
        $pending_leave = $stmt_leave->fetch()['count'];

        $pending_auditor_checks = $pending_expenses + $pending_po + $pending_leave;

        return [
            'total_revenue' => $total_revenue,
            'month_revenue' => $month_revenue,
            'outstanding' => $outstanding,
            'overdue' => $overdue,
            'total_expenses' => $expenses,
            'month_expenses' => $month_expenses,
            'net_profit' => $net_profit,
            'month_profit' => $month_profit,
            'total_cash' => $total_cash,
            'pending_auditor_checks' => $pending_auditor_checks
        ];
    }
    
    private function getSalesStats() {
        $stats = [];
        
        // Today's sales
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices WHERE DATE(invoice_date) = CURDATE()";
        $stmt = $this->db->query($sql);
        $stats['today'] = $stmt->fetch()['total'];
        
        // This week
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices 
                WHERE YEARWEEK(invoice_date) = YEARWEEK(NOW())";
        $stmt = $this->db->query($sql);
        $stats['week'] = $stmt->fetch()['total'];
        
        // This month
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices 
                WHERE MONTH(invoice_date) = MONTH(NOW()) AND YEAR(invoice_date) = YEAR(NOW())";
        $stmt = $this->db->query($sql);
        $stats['month'] = $stmt->fetch()['total'];
        
        // This year
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM invoices 
                WHERE YEAR(invoice_date) = YEAR(NOW())";
        $stmt = $this->db->query($sql);
        $stats['year'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getRecentInvoices() {
        $sql = "SELECT i.*, c.company_name 
                FROM invoices i
                LEFT JOIN contacts c ON i.customer_id = c.id
                ORDER BY i.created_at DESC
                LIMIT 10";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getRecentPayments() {
        // FIXED: Removed payment_method_id join that doesn't exist
        $sql = "SELECT p.*, c.company_name 
                FROM payments p
                LEFT JOIN contacts c ON p.customer_id = c.id
                ORDER BY p.payment_date DESC
                LIMIT 10";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getRecentCustomers() {
        $sql = "SELECT * FROM contacts 
                WHERE type IN ('customer', 'both') 
                ORDER BY created_at DESC 
                LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getMonthlySales() {
        $sql = "SELECT 
                    DATE_FORMAT(invoice_date, '%b %Y') as month,
                    SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END) as paid,
                    SUM(CASE WHEN status IN ('sent', 'overdue') THEN total_amount ELSE 0 END) as pending
                FROM invoices
                WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
                ORDER BY invoice_date ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getTopCustomers() {
        $sql = "SELECT 
                    c.id,
                    c.company_name,
                    COUNT(i.id) as invoice_count,
                    SUM(i.total_amount) as total_amount
                FROM contacts c
                JOIN invoices i ON c.id = i.customer_id
                WHERE i.status = 'paid'
                GROUP BY c.id
                ORDER BY total_amount DESC
                LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getInvoiceStatusDistribution() {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count,
                    SUM(total_amount) as total
                FROM invoices
                GROUP BY status";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    private function getCounts() {
        $counts = [];
        
        // Total Customers
        $sql = "SELECT COUNT(*) as total FROM contacts WHERE type IN ('customer', 'both') AND status = 1";
        $stmt = $this->db->query($sql);
        $counts['customers'] = $stmt->fetch()['total'];
        
        // Total Invoices
        $sql = "SELECT COUNT(*) as total FROM invoices";
        $stmt = $this->db->query($sql);
        $counts['invoices'] = $stmt->fetch()['total'];
        
        // Paid Invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE status = 'paid'";
        $stmt = $this->db->query($sql);
        $counts['paid_count'] = $stmt->fetch()['total'];
        
        // Draft Invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE status = 'draft'";
        $stmt = $this->db->query($sql);
        $counts['draft_count'] = $stmt->fetch()['total'];
        
        // Sent Invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE status = 'sent'";
        $stmt = $this->db->query($sql);
        $counts['sent_count'] = $stmt->fetch()['total'];
        
        // Overdue Invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE status = 'overdue'";
        $stmt = $this->db->query($sql);
        $counts['overdue_count'] = $stmt->fetch()['total'];
        
        // Total Products
        $sql = "SELECT COUNT(*) as total FROM items";
        $stmt = $this->db->query($sql);
        $counts['products'] = $stmt->fetch()['total'];
        
        // Total Vendors
        $sql = "SELECT COUNT(*) as total FROM contacts WHERE type IN ('vendor', 'both') AND status = 1";
        $stmt = $this->db->query($sql);
        $counts['vendors'] = $stmt->fetch()['total'];
        
        // Low Stock Items
        $sql = "SELECT COUNT(*) as total FROM items WHERE current_stock <= reorder_level";
        $stmt = $this->db->query($sql);
        $counts['low_stock'] = $stmt->fetch()['total'];
        
        return $counts;
    }
    
    private function getAlerts() {
        $alerts = [];
        
        // Overdue Invoices
        $sql = "SELECT COUNT(*) as count, SUM(total_amount - paid_amount) as amount 
                FROM invoices WHERE status = 'overdue'";
        $stmt = $this->db->query($sql);
        $overdue = $stmt->fetch();
        if ($overdue['count'] > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => $overdue['count'] . ' overdue invoice(s) totaling $' . number_format($overdue['amount'], 2)
            ];
        }
        
        // Low Stock
        $sql = "SELECT COUNT(*) as count FROM items WHERE current_stock <= reorder_level";
        $stmt = $this->db->query($sql);
        $low_stock = $stmt->fetch();
        if ($low_stock['count'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'box-open',
                'message' => $low_stock['count'] . ' item(s) low in stock'
            ];
        }
        
        return $alerts;
    }

    private function getOnboardingData() {
        $step_company = !empty(get_setting('company_email')) || (get_setting('company_name') !== 'My Business Ltd');
        
        $bankCount = (int)$this->db->query("SELECT COUNT(*) FROM bank_accounts")->fetchColumn();
        $step_banking = ($bankCount > 0);
        
        $contactCount = (int)$this->db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
        $step_contacts = ($contactCount > 0);
        
        $itemCount = (int)$this->db->query("SELECT COUNT(*) FROM items")->fetchColumn();
        $step_products = ($itemCount > 0);
        
        $invoiceCount = (int)$this->db->query("SELECT COUNT(*) FROM invoices")->fetchColumn();
        $step_invoices = ($invoiceCount > 0);

        $steps = [
            'company' => $step_company,
            'banking' => $step_banking,
            'contacts' => $step_contacts,
            'products' => $step_products,
            'invoices' => $step_invoices,
        ];

        $completed = count(array_filter($steps));
        $total = count($steps);

        return [
            'steps' => $steps,
            'completed' => $completed,
            'total' => $total,
            'percentage' => round(($completed / $total) * 100)
        ];
    }
    
}
