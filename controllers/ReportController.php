<?php
if (!class_exists('Report')) {
    require_once __DIR__ . '/../models/Report.php';
}

class ReportController {
    private $reportModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->reportModel = new Report($this->db);
    }
    
    public function index() {
        $title = 'Reports Dashboard';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function sales() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t'),
            'customer_id' => $_GET['customer_id'] ?? null,
            'status' => $_GET['status'] ?? null
        ];
        
        $sales = $this->reportModel->getSalesReport($filters);
        $summary = $this->reportModel->getSalesSummary($filters);
        $by_customer = $this->reportModel->getSalesByCustomer($filters);
        $by_product = $this->reportModel->getSalesByProduct($filters);
        $monthly_trend = $this->reportModel->getMonthlySalesTrend();
        $customers = $this->reportModel->getCustomers();
        
        $title = 'Sales Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/sales.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function expenses() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t'),
            'category_id' => $_GET['category_id'] ?? null
        ];
        
        $expenses = $this->reportModel->getExpenseReport($filters);
        $summary = $this->reportModel->getExpenseSummary($filters);
        $by_category = $this->reportModel->getExpensesByCategory($filters);
        $categories = $this->reportModel->getExpenseCategories();
        
        $title = 'Expense Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/expenses.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function purchases() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t'),
            'vendor_id' => $_GET['vendor_id'] ?? null
        ];
        
        $purchases = $this->reportModel->getPurchaseReport($filters);
        $summary = $this->reportModel->getPurchaseSummary($filters);
        $vendors = $this->reportModel->getVendors();
        
        $title = 'Purchase Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/purchases.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function profitLoss() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-01-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t')
        ];
        
        $pl_data = $this->reportModel->getProfitLoss($filters);
        
        $title = 'Profit & Loss Statement';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/profit-loss.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function tax() {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-01-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t')
        ];
        
        $tax_data = $this->reportModel->getTaxReport($filters);
        
        $title = 'Tax Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/tax.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function inventory() {
        $inventory = $this->reportModel->getInventoryReport();
        $summary = $this->reportModel->getInventorySummary();
        
        $title = 'Inventory Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/inventory.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function customers() {
        $customers = $this->reportModel->getCustomerReport();
        
        $title = 'Customer Report';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/reports/customers.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function export($type, $format = 'csv') {
        $filters = [
            'date_from' => $_GET['date_from'] ?? date('Y-m-01'),
            'date_to' => $_GET['date_to'] ?? date('Y-m-t'),
            'customer_id' => $_GET['customer_id'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'vendor_id' => $_GET['vendor_id'] ?? null,
            'status' => $_GET['status'] ?? null
        ];
        
        if ($format == 'pdf') {
            require_once __DIR__ . '/../helpers/PDFHelper.php';
            $pdf = new ReportPDF($this->db);
            
            switch($type) {
                case 'sales':
                    $pdf->generateSalesReport($filters);
                    break;
                case 'expenses':
                    $pdf->generateExpenseReport($filters);
                    break;
                case 'purchases':
                    $pdf->generatePurchaseReport($filters);
                    break;
                case 'profit-loss':
                    $pdf->generateProfitLossReport($filters);
                    break;
                case 'tax':
                    $pdf->generateTaxReport($filters);
                    break;
                case 'inventory':
                    $pdf->generateInventoryReport();
                    break;
                case 'customers':
                    $pdf->generateCustomerReport();
                    break;
                default:
                    header('Location: ' . BASE_URL . '/reports');
            }
            return;
        }
        
        // CSV Export
        switch($type) {
            case 'sales':
                $data = $this->reportModel->getSalesReport($filters);
                $this->exportCSV($data, 'sales_report.csv');
                break;
            case 'expenses':
                $data = $this->reportModel->getExpenseReport($filters);
                $this->exportCSV($data, 'expense_report.csv');
                break;
            case 'purchases':
                $data = $this->reportModel->getPurchaseReport($filters);
                $this->exportCSV($data, 'purchase_report.csv');
                break;
            case 'inventory':
                $data = $this->reportModel->getInventoryReport();
                $this->exportCSV($data, 'inventory_report.csv');
                break;
            case 'customers':
                $data = $this->reportModel->getCustomerReport();
                $this->exportCSV($data, 'customer_report.csv');
                break;
        }
    }
    
    private function exportCSV($data, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
}
