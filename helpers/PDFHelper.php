<?php
/**
 * PDF Helper - Generates PDF reports using TCPDF
 * Location: C:\xampp\htdocs\erp_system\helpers\PDFHelper.php
 */

// Check multiple possible TCPDF locations
$tcpdf_paths = [
    __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php',      // Composer installation
    __DIR__ . '/../libraries/tcpdf/TCPDF-main/tcpdf.php',   // Manual extraction
    __DIR__ . '/../libraries/tcpdf/tcpdf.php',              // Standard location
];

$tcpdf_loaded = false;
foreach ($tcpdf_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $tcpdf_loaded = true;
        break;
    }
}

if (!$tcpdf_loaded) {
    die('TCPDF library not found. Please install TCPDF.');
}

class PDFHelper extends TCPDF {
    
    private $company_name = 'ERP System';
    private $company_address = '123 Business Street, City, Country';
    private $company_phone = '+1 234 567 8900';
    private $company_email = 'info@erpsystem.com';
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        parent::__construct($orientation, $unit, $format, true, 'UTF-8', false);
        
        try {
            require_once __DIR__ . '/../config/database.php';
            require_once __DIR__ . '/../models/Setting.php';
            $db = getConnection();
            $settingModel = new Setting($db);
            $this->company_name = $settingModel->get('company_name', $settingModel->get('site_title', 'ERP System'));
            $this->company_address = $settingModel->get('company_address', '123 Business Street, City, Country');
            $this->company_phone = $settingModel->get('company_phone', '+1 234 567 8900');
            $this->company_email = $settingModel->get('company_email', 'info@erpsystem.com');
        } catch (Exception $e) {
            // Fallback default values
        }

        // Set document information
        $this->SetCreator($this->company_name);
        $this->SetAuthor($this->company_name);
        $this->SetTitle('Report');
        
        // Set margins
        $this->SetMargins(15, 15, 15);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(10);
        
        // Set auto page breaks
        $this->SetAutoPageBreak(true, 25);
        
        // Set font
        $this->SetFont('helvetica', '', 10);
    }
    
    // Page header
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, $this->company_name, 0, 1, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 4, $this->company_address, 0, 1, 'L');
        $this->Cell(0, 4, 'Phone: ' . $this->company_phone . ' | Email: ' . $this->company_email, 0, 1, 'L');
        
        // Line
        $this->Line(15, 35, 195, 35);
        $this->Ln(5);
    }
    
    // Page footer
    public function Footer() {
        $this->SetY(-20);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
        $this->Cell(0, 10, 'Generated on ' . date('d/m/Y H:i:s'), 0, 0, 'R');
    }
    
    public function setReportTitle($title, $date_range = '') {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, $title, 0, 1, 'C');
        if ($date_range) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 6, $date_range, 0, 1, 'C');
        }
        $this->Ln(5);
    }
    
    public function addSummaryBox($data) {
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Summary', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        
        $html = '<table border="0.5" cellpadding="4">';
        $html .= '<tr bgcolor="#f2f2f2">';
        foreach (array_keys($data) as $key) {
            $html .= '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        foreach ($data as $value) {
            $html .= '<td>' . $value . '</td>';
        }
        $html .= '</tr></table>';
        
        $this->writeHTML($html, true, false, false, false, '');
        $this->Ln(10);
    }
    
    public function addTable($headers, $data, $widths = []) {
        $html = '<table border="0.5" cellpadding="4">';
        
        // Headers
        $html .= '<tr bgcolor="#4e73df" style="color: #ffffff;">';
        foreach ($headers as $i => $header) {
            $width = isset($widths[$i]) ? ' width="' . $widths[$i] . '"' : '';
            $html .= '<th' . $width . '>' . $header . '</th>';
        }
        $html .= '</tr>';
        
        // Data
        $bg = false;
        foreach ($data as $row) {
            $bgColor = $bg ? '#f9f9f9' : '#ffffff';
            $html .= '<tr bgcolor="' . $bgColor . '">';
            foreach ($row as $cell) {
                $html .= '<td>' . $cell . '</td>';
            }
            $html .= '</tr>';
            $bg = !$bg;
        }
        
        $html .= '</table>';
        
        $this->writeHTML($html, true, false, false, false, '');
    }
}

class ReportPDF {
    private $db;
    private $currency;
    
    public function __construct($db) {
        $this->db = $db;
        $this->currency = get_setting('currency_symbol', get_setting('currency', 'USD'));
    }
    
    public function generateSalesReport($filters = []) {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $date_range = '';
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $date_range = date('d M Y', strtotime($filters['date_from'])) . ' - ' . 
                         date('d M Y', strtotime($filters['date_to']));
        }
        
        $pdf->setReportTitle('SALES REPORT', $date_range);
        
        // Get summary
        $sql = "SELECT 
                    COUNT(*) as total_invoices,
                    COALESCE(SUM(total_amount), 0) as total_sales,
                    COALESCE(SUM(paid_amount), 0) as total_paid,
                    COALESCE(SUM(total_amount - paid_amount), 0) as outstanding
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
        $summary = $stmt->fetch();
        
        $pdf->addSummaryBox([
            'Total Invoices' => number_format($summary['total_invoices']),
            'Total Sales' => $this->currency . ' ' . number_format($summary['total_sales'], 2),
            'Total Paid' => $this->currency . ' ' . number_format($summary['total_paid'], 2),
            'Outstanding' => $this->currency . ' ' . number_format($summary['outstanding'], 2)
        ]);
        
        // Get data
        $sql = "SELECT 
                    i.invoice_number,
                    DATE_FORMAT(i.invoice_date, '%d/%m/%Y') as invoice_date,
                    c.company_name,
                    i.total_amount,
                    i.paid_amount,
                    (i.total_amount - i.paid_amount) as balance,
                    i.status
                FROM invoices i
                LEFT JOIN contacts c ON i.customer_id = c.id
                WHERE 1=1";
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND i.invoice_date >= :date_from";
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND i.invoice_date <= :date_to";
        }
        
        $sql .= " ORDER BY i.invoice_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = ['Invoice #', 'Date', 'Customer', 'Total', 'Paid', 'Balance', 'Status'];
        $tableData = [];
        
        foreach ($data as $row) {
            $tableData[] = [
                $row['invoice_number'],
                $row['invoice_date'],
                substr($row['company_name'], 0, 30),
                $this->currency . ' ' . number_format($row['total_amount'], 2),
                $this->currency . ' ' . number_format($row['paid_amount'], 2),
                $this->currency . ' ' . number_format($row['balance'], 2),
                ucfirst($row['status'])
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Sales Details', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->addTable($headers, $tableData, ['15%', '12%', '25%', '12%', '12%', '12%', '12%']);
        
        $pdf->Output('sales_report.pdf', 'I');
    }
    
    public function generateExpenseReport($filters = []) {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $date_range = '';
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $date_range = date('d M Y', strtotime($filters['date_from'])) . ' - ' . 
                         date('d M Y', strtotime($filters['date_to']));
        }
        
        $pdf->setReportTitle('EXPENSE REPORT', $date_range);
        
        // Get summary
        $sql = "SELECT 
                    COUNT(*) as total_expenses,
                    COALESCE(SUM(amount), 0) as total_amount,
                    COALESCE(AVG(amount), 0) as average_amount
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
        $summary = $stmt->fetch();
        
        $pdf->addSummaryBox([
            'Total Expenses' => number_format($summary['total_expenses']),
            'Total Amount' => $this->currency . ' ' . number_format($summary['total_amount'], 2),
            'Average Expense' => $this->currency . ' ' . number_format($summary['average_amount'], 2)
        ]);
        
        // Get data
        $sql = "SELECT 
                    e.expense_number,
                    DATE_FORMAT(e.expense_date, '%d/%m/%Y') as expense_date,
                    ec.category_name,
                    c.company_name as vendor_name,
                    e.description,
                    e.amount,
                    e.payment_status
                FROM expenses e
                LEFT JOIN expense_categories ec ON e.category_id = ec.id
                LEFT JOIN contacts c ON e.vendor_id = c.id
                WHERE 1=1";
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND e.expense_date >= :date_from";
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND e.expense_date <= :date_to";
        }
        
        $sql .= " ORDER BY e.expense_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = ['Expense #', 'Date', 'Category', 'Vendor', 'Description', 'Amount', 'Status'];
        $tableData = [];
        
        foreach ($data as $row) {
            $tableData[] = [
                $row['expense_number'],
                $row['expense_date'],
                $row['category_name'] ?? '-',
                $row['vendor_name'] ?? '-',
                substr($row['description'], 0, 30) . '...',
                $this->currency . ' ' . number_format($row['amount'], 2),
                ucfirst($row['payment_status'])
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Expense Details', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->addTable($headers, $tableData, ['12%', '12%', '15%', '18%', '18%', '12%', '13%']);
        
        $pdf->Output('expense_report.pdf', 'I');
    }
    
    public function generatePurchaseReport($filters = []) {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $date_range = '';
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $date_range = date('d M Y', strtotime($filters['date_from'])) . ' - ' . 
                         date('d M Y', strtotime($filters['date_to']));
        }
        
        $pdf->setReportTitle('PURCHASE REPORT', $date_range);
        
        // Get summary
        $sql = "SELECT 
                    COUNT(*) as total_pos,
                    COALESCE(SUM(total_amount), 0) as total_purchases,
                    COALESCE(SUM(paid_amount), 0) as total_paid,
                    COALESCE(SUM(total_amount - paid_amount), 0) as outstanding
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
        $summary = $stmt->fetch();
        
        $pdf->addSummaryBox([
            'Total POs' => number_format($summary['total_pos']),
            'Total Purchases' => $this->currency . ' ' . number_format($summary['total_purchases'], 2),
            'Total Paid' => $this->currency . ' ' . number_format($summary['total_paid'], 2),
            'Outstanding' => $this->currency . ' ' . number_format($summary['outstanding'], 2)
        ]);
        
        // Get data
        $sql = "SELECT 
                    po.po_number,
                    DATE_FORMAT(po.po_date, '%d/%m/%Y') as po_date,
                    c.company_name as vendor_name,
                    po.total_amount,
                    po.paid_amount,
                    (po.total_amount - po.paid_amount) as balance,
                    po.status,
                    po.payment_status
                FROM purchase_orders po
                LEFT JOIN contacts c ON po.vendor_id = c.id
                WHERE 1=1";
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND po.po_date >= :date_from";
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND po.po_date <= :date_to";
        }
        
        $sql .= " ORDER BY po.po_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = ['PO #', 'Date', 'Vendor', 'Total', 'Paid', 'Balance', 'Status', 'Payment'];
        $tableData = [];
        
        foreach ($data as $row) {
            $tableData[] = [
                $row['po_number'],
                $row['po_date'],
                substr($row['vendor_name'], 0, 25),
                $this->currency . ' ' . number_format($row['total_amount'], 2),
                $this->currency . ' ' . number_format($row['paid_amount'], 2),
                $this->currency . ' ' . number_format($row['balance'], 2),
                ucfirst($row['status']),
                ucfirst($row['payment_status'])
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Purchase Details', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->addTable($headers, $tableData, ['12%', '10%', '20%', '11%', '11%', '11%', '10%', '15%']);
        
        $pdf->Output('purchase_report.pdf', 'I');
    }
    
    public function generateProfitLossReport($filters = []) {
        $pdf = new PDFHelper('P', 'mm', 'A4');
        $pdf->AddPage();
        
        $date_range = '';
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $date_range = date('d M Y', strtotime($filters['date_from'])) . ' - ' . 
                         date('d M Y', strtotime($filters['date_to']));
        }
        
        $pdf->setReportTitle('PROFIT & LOSS STATEMENT', $date_range);
        
        // Get data
        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report($this->db);
        $pl_data = $reportModel->getProfitLoss($filters);
        
        // Income section
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(40, 167, 69);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 8, 'INCOME', 0, 1, 'L', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        
        $html = '<table cellpadding="5">';
        $html .= '<tr><td width="80%">Sales Revenue</td><td width="20%" align="right">' . $this->currency . ' ' . number_format($pl_data['total_income'], 2) . '</td></tr>';
        $html .= '<tr bgcolor="#f2f2f2"><td><strong>Total Income</strong></td><td align="right"><strong>' . $this->currency . ' ' . number_format($pl_data['total_income'], 2) . '</strong></td></tr>';
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Ln(5);
        
        // Expenses section
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(220, 53, 69);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 8, 'EXPENSES', 0, 1, 'L', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        
        $html = '<table cellpadding="5">';
        foreach ($pl_data['expenses'] as $expense) {
            if ($expense['amount'] > 0) {
                $html .= '<tr><td width="80%">' . htmlspecialchars($expense['category_name']) . '</td>';
                $html .= '<td width="20%" align="right">' . $this->currency . ' ' . number_format($expense['amount'], 2) . '</td></tr>';
            }
        }
        $html .= '<tr bgcolor="#f2f2f2"><td><strong>Total Expenses</strong></td><td align="right"><strong>' . $this->currency . ' ' . number_format($pl_data['total_expenses'], 2) . '</strong></td></tr>';
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Ln(5);
        
        // Net Profit
        $pdf->SetFont('helvetica', 'B', 12);
        $bgColor = $pl_data['net_profit'] >= 0 ? [40, 167, 69] : [220, 53, 69];
        $pdf->SetFillColor($bgColor[0], $bgColor[1], $bgColor[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, 'NET PROFIT (LOSS): ' . $this->currency . ' ' . number_format($pl_data['net_profit'], 2), 0, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Output('profit_loss_report.pdf', 'I');
    }
    
    public function generateTaxReport($filters = []) {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $date_range = '';
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $date_range = date('d M Y', strtotime($filters['date_from'])) . ' - ' . 
                         date('d M Y', strtotime($filters['date_to']));
        }
        
        $pdf->setReportTitle('TAX REPORT', $date_range);
        
        // Get tax data
        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report($this->db);
        $tax_data = $reportModel->getTaxReport($filters);
        
        $total_tax = array_sum(array_column($tax_data, 'total_tax'));
        $total_sales = array_sum(array_column($tax_data, 'total_sales'));
        
        $pdf->addSummaryBox([
            'Total Tax Collected' => $this->currency . ' ' . number_format($total_tax, 2),
            'Total Taxable Sales' => $this->currency . ' ' . number_format($total_sales, 2),
            'Average Tax Rate' => $total_sales > 0 ? number_format(($total_tax / $total_sales) * 100, 2) . '%' : '0%'
        ]);
        
        $headers = ['Month', 'Invoices', 'Sales (Excl. Tax)', 'Tax Amount', 'Total (Incl. Tax)'];
        $tableData = [];
        
        foreach ($tax_data as $row) {
            $tableData[] = [
                $row['month_name'],
                number_format($row['invoice_count']),
                $this->currency . ' ' . number_format($row['total_sales'], 2),
                $this->currency . ' ' . number_format($row['total_tax'], 2),
                $this->currency . ' ' . number_format($row['total_with_tax'], 2)
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Monthly Tax Summary', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->addTable($headers, $tableData, ['20%', '15%', '25%', '20%', '20%']);
        
        $pdf->Output('tax_report.pdf', 'I');
    }
    
    public function generateInventoryReport() {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $pdf->setReportTitle('INVENTORY REPORT', date('d M Y'));
        
        // Get summary
        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report($this->db);
        $summary = $reportModel->getInventorySummary();
        
        $pdf->addSummaryBox([
            'Total Products' => number_format($summary['total_products']),
            'Total Stock' => number_format($summary['total_stock']),
            'Cost Value' => $this->currency . ' ' . number_format($summary['total_cost_value'], 2),
            'Sale Value' => $this->currency . ' ' . number_format($summary['total_sale_value'], 2),
            'Low Stock' => number_format($summary['low_stock'])
        ]);
        
        // Get data
        $inventory = $reportModel->getInventoryReport();
        
        $headers = ['Item Code', 'Product Name', 'Category', 'Stock', 'Cost Price', 'Sale Price', 'Stock Value', 'Status'];
        $tableData = [];
        
        foreach ($inventory as $item) {
            $status = $item['current_stock'] == 0 ? 'Out of Stock' : ($item['current_stock'] <= $item['reorder_level'] ? 'Low Stock' : 'In Stock');
            $tableData[] = [
                $item['item_code'],
                substr($item['item_name'], 0, 30),
                $item['category_name'] ?? '-',
                number_format($item['current_stock']),
                $this->currency . ' ' . number_format($item['purchase_price'], 2),
                $this->currency . ' ' . number_format($item['sale_price'], 2),
                $this->currency . ' ' . number_format($item['stock_value'] ?? 0, 2),
                $status
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Inventory Details', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->addTable($headers, $tableData, ['12%', '20%', '12%', '10%', '10%', '10%', '12%', '14%']);
        
        $pdf->Output('inventory_report.pdf', 'I');
    }
    
    public function generateCustomerReport() {
        $pdf = new PDFHelper('L', 'mm', 'A4');
        $pdf->AddPage();
        
        $pdf->setReportTitle('CUSTOMER REPORT', date('d M Y'));
        
        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report($this->db);
        $customers = $reportModel->getCustomerReport();
        
        $total_customers = count($customers);
        $total_revenue = array_sum(array_column($customers, 'total_purchases'));
        $total_outstanding = array_sum(array_column($customers, 'outstanding'));
        
        $pdf->addSummaryBox([
            'Total Customers' => number_format($total_customers),
            'Total Revenue' => $this->currency . ' ' . number_format($total_revenue, 2),
            'Total Outstanding' => $this->currency . ' ' . number_format($total_outstanding, 2)
        ]);
        
        $headers = ['Customer', 'Contact', 'Email', 'Invoices', 'Total Purchases', 'Paid', 'Outstanding', 'Last Purchase'];
        $tableData = [];
        
        foreach ($customers as $row) {
            $tableData[] = [
                substr($row['company_name'], 0, 20),
                substr($row['contact_person'], 0, 15),
                substr($row['email'], 0, 20),
                number_format($row['total_invoices']),
                $this->currency . ' ' . number_format($row['total_purchases'], 2),
                $this->currency . ' ' . number_format($row['total_paid'], 2),
                $this->currency . ' ' . number_format($row['outstanding'], 2),
                $row['last_purchase'] ? date('d/m/Y', strtotime($row['last_purchase'])) : 'Never'
            ];
        }
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Customer Details', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->addTable($headers, $tableData, ['15%', '12%', '18%', '8%', '12%', '10%', '10%', '15%']);
        
        $pdf->Output('customer_report.pdf', 'I');
    }
}
?>