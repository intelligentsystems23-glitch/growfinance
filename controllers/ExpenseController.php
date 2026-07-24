<?php
if (!class_exists('Expense')) {
    require_once __DIR__ . '/../models/Expense.php';
}

class ExpenseController {
    private $expenseModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->expenseModel = new Expense($this->db);
    }
    
    public function index() {
        $filters = [];
        
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        if (isset($_GET['payment_status'])) {
            $filters['payment_status'] = $_GET['payment_status'];
        }
        if (isset($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }
        
        $expenses = $this->expenseModel->getAll($filters);
        $categories = $this->expenseModel->getCategories();
        $stats = $this->expenseModel->getStats();
        
        $title = 'Expenses';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/expenses/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $expense = $this->expenseModel->getById($id);
        
        if (!$expense) {
            header('Location: ' . BASE_URL . '/expenses');
            exit;
        }
        
        $title = 'Expense Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/expenses/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function exportPDF($id) {
        $expense = $this->expenseModel->getById($id);
        if (!$expense) {
            header('Location: ' . BASE_URL . '/expenses');
            exit;
        }

        require_once __DIR__ . '/../helpers/PDFHelper.php';
        
        $pdf = new PDFHelper('P', 'mm', 'A4');
        $pdf->SetTitle('Expense - ' . $expense['expense_number']);
        $pdf->AddPage();
        
        $pdf->setReportTitle('EXPENSE VOUCHER', $expense['expense_number']);
        
        require_once __DIR__ . '/../models/Setting.php';
        $settingModel = new Setting($this->db);
        $currency = $settingModel->get('currency', 'Ugx');

        $html = '
        <br/><br/>
        <table cellpadding="6" style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: middle;">
                    <span style="font-size: 10pt; color: #475569; font-weight: bold; text-transform: uppercase;">Expense Transaction Details</span>
                </td>
                <td style="width: 40%; text-align: right;">
                    <table cellpadding="6" style="border: 1px solid #cbd5e1; background-color: #f8fafc;">
                        <tr>
                            <td align="center">
                                <span style="font-size: 8pt; color: #64748b; font-weight: bold;">AMOUNT PAID</span><br/>
                                <span style="font-size: 14pt; font-weight: bold; color: #0f172a;">' . htmlspecialchars($currency) . ' ' . number_format($expense['amount'], 2) . '</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <br/><br/>
        <table border="0.5" cellpadding="6" style="width: 100%; border-color: #e2e8f0;">
            <tr bgcolor="#f8fafc">
                <td width="25%" style="color: #475569; font-weight: bold;">Expense Number</td>
                <td width="75%" style="color: #0f172a; font-weight: bold;">' . htmlspecialchars($expense['expense_number']) . '</td>
            </tr>
            <tr>
                <td style="color: #475569;">Transaction Date</td>
                <td style="color: #0f172a;">' . date('d/m/Y', strtotime($expense['expense_date'])) . '</td>
            </tr>
            <tr bgcolor="#f8fafc">
                <td style="color: #475569;">Category</td>
                <td style="color: #0f172a;">' . htmlspecialchars($expense['category_name'] ?? 'Uncategorized') . '</td>
            </tr>
            <tr>
                <td style="color: #475569;">Vendor / Supplier</td>
                <td style="color: #0f172a;">' . htmlspecialchars($expense['vendor_name'] ?? '-') . '</td>
            </tr>
            <tr bgcolor="#f8fafc">
                <td style="color: #475569;">Payment Method</td>
                <td style="color: #0f172a;">' . htmlspecialchars(ucwords(str_replace('_', ' ', $expense['payment_method'] ?? '-'))) . '</td>
            </tr>
            <tr>
                <td style="color: #475569;">Reference Number</td>
                <td style="color: #0f172a;">' . htmlspecialchars($expense['reference_number'] ?? '-') . '</td>
            </tr>
            <tr bgcolor="#f8fafc">
                <td style="color: #475569;">Status</td>
                <td style="color: #16a34a; font-weight: bold;">' . strtoupper($expense['payment_status']) . '</td>
            </tr>
        </table>
        
        <br/><br/>
        <table border="0.5" cellpadding="8" style="width: 100%; border-color: #e2e8f0;">
            <tr bgcolor="#f8fafc">
                <td style="color: #475569; font-weight: bold;">Description</td>
            </tr>
            <tr>
                <td style="color: #334155; line-height: 1.4;">' . nl2br(htmlspecialchars($expense['description'])) . '</td>
            </tr>
        </table>';
        
        if (!empty($expense['notes'])) {
            $html .= '
            <br/><br/>
            <table border="0.5" cellpadding="8" style="width: 100%; border-color: #e2e8f0;">
                <tr bgcolor="#f8fafc">
                    <td style="color: #475569; font-weight: bold;">Additional Notes</td>
                </tr>
                <tr>
                    <td style="color: #475569; font-style: italic; line-height: 1.4;">' . nl2br(htmlspecialchars($expense['notes'])) . '</td>
                </tr>
            </table>';
        }

        $html .= '
        <br/><br/><br/><br/><br/><br/>
        <table cellpadding="6" style="width: 100%;">
            <tr>
                <td style="width: 45%; border-top: 1px solid #cbd5e1; text-align: center;">
                    <span style="font-size: 8pt; color: #64748b;">Prepared / Submitted By</span><br/>
                    <br/><br/>
                    <span style="font-size: 9pt; font-weight: bold; color: #334155;">___________________________</span>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; border-top: 1px solid #cbd5e1; text-align: center;">
                    <span style="font-size: 8pt; color: #64748b;">Approved & Verified By</span><br/>
                    <br/>';
                    
        if ($expense['approved_by']) {
            $html .= '
                    <span style="font-size: 9pt; font-weight: bold; color: #16a34a;">✔ APPROVED</span><br/>
                    <span style="font-size: 8pt; color: #475569;">' . htmlspecialchars($expense['approved_by_name']) . '</span><br/>
                    <span style="font-size: 7pt; color: #64748b;">Date: ' . date('d/m/Y H:i', strtotime($expense['approved_at'])) . '</span>';
        } else {
            $html .= '
                    <br/>
                    <span style="font-size: 9pt; font-weight: bold; color: #64748b;">___________________________</span><br/>
                    <span style="font-size: 8pt; color: #94a3b8;">Pending Signature</span>';
        }
        
        $html .= '
                </td>
            </tr>
        </table>';
        
        $pdf->writeHTML($html, true, false, false, false, '');
        
        $pdf->Output('expense_' . $expense['expense_number'] . '.pdf', 'I');
        exit;
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $expense_id = $this->expenseModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $expense_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $categories = $this->expenseModel->getCategories();
        $vendors = $this->expenseModel->getVendors();
        
        $title = 'Record Expense';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/expenses/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        $expense = $this->expenseModel->getById($id);
        
        if (!$expense) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Expense not found']);
                return;
            }
            header('Location: ' . BASE_URL . '/expenses');
            exit;
        }
        
        if ($expense['payment_status'] === 'paid') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Paid expenses cannot be edited.']);
                return;
            }
            header('Location: ' . BASE_URL . '/expenses');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->expenseModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $categories = $this->expenseModel->getCategories();
        $vendors = $this->expenseModel->getVendors();
        
        $title = 'Edit Expense';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/expenses/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->expenseModel->delete($id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $this->expenseModel->approve($id, $user_id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }

    public function getDesignations($department_id) {
    header('Content-Type: application/json');
    $designations = $this->employeeModel->getDesignations($department_id);
    echo json_encode($designations);
}
}
