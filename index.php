<?php
/**
 * ERP System - Main Entry Point (Front Controller)
 * Location: C:\xampp\htdocs\erp_system\index.php
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once 'config/database.php';

// Get the requested URL
$request = $_GET['url'] ?? 'dashboard';
$request = trim($request, '/');
$parts = explode('/', $request);

$controllerName = $parts[0] ?? 'dashboard';
$action = $parts[1] ?? 'index';
$id = $parts[2] ?? null;
$format_segment = $parts[3] ?? null;

// Require authentication for non-public routes
$publicRoutes = ['login'];
if (!is_logged_in() && !in_array($controllerName, $publicRoutes)) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Route to appropriate controller
switch ($controllerName) {
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'login':
        require_once 'views/auth/login.php';
        break;
        
    case 'logout':
        session_destroy();
        header('Location: login');
        exit;
        break;
        
    case 'invoices':
        // Check if controller file exists
        $controllerFile = 'controllers/InvoiceController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new InvoiceController();
            
            // Route to appropriate action
            if (($action == 'view' || $action == 'show') && $id) {
                $controller->show($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } elseif ($action == 'payment' && $id) {
                $controller->payment($id);
            } else {
                $controller->index();
            }
        } else {
            // If InvoiceController doesn't exist yet, show a simple message
            echo "<h2>Invoice Module</h2>";
            echo "<p>InvoiceController is being set up. Please wait...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
        
    case 'customers':
        $controllerFile = 'controllers/CustomerController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new CustomerController();
            
            if (($action == 'view' || $action == 'show') && $id) {
                $controller->view($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Customer Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
        
    case 'products':
        $controllerFile = 'controllers/ProductController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new ProductController();
            
            if (($action == 'view' || $action == 'show') && $id) {
                $controller->view($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'adjustStock' && $id) {
                $controller->adjustStock($id);
            } elseif ($action == 'activate' && $id) {
                $controller->activate($id);
            } elseif ($action == 'duplicate' && $id) {
                $controller->duplicate($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Product Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
        
    case 'vendors':
        $controllerFile = 'controllers/VendorController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new VendorController();
            
            if ($action == 'view' && $id) {
                $controller->view($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Vendor Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'expenses':
        $controllerFile = 'controllers/ExpenseController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new ExpenseController();
            
            if ($action == 'view' && $id) {
                $controller->view($id);
            } elseif ($action == 'pdf' && $id) {
                $controller->exportPDF($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } elseif ($action == 'approve' && $id) {
                $controller->approve($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Expense Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'banking':
        $controllerFile = 'controllers/BankingController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new BankingController();
            
            if ($action == 'accounts') {
                $controller->accounts();
            } elseif ($action == 'createAccount' || $action == 'create-account' || $action == 'create_account' || $action == 'create') {
                $controller->createAccount();
            } elseif (($action == 'editAccount' || $action == 'edit-account' || $action == 'edit_account' || $action == 'edit') && $id) {
                $controller->editAccount($id);
            } elseif ($action == 'transactions') {
                $controller->transactions();
            } elseif ($action == 'createTransaction' || $action == 'create-transaction' || $action == 'create_transaction') {
                $controller->createTransaction();
            } elseif ($action == 'transfers') {
                $controller->transfers();
            } elseif ($action == 'createTransfer' || $action == 'create-transfer' || $action == 'create_transfer') {
                $controller->createTransfer();
            } elseif ($action == 'reconciliation') {
                $controller->reconciliation();
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Banking Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'accounting':
        $controllerFile = 'controllers/AccountingController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new AccountingController();
            
            if ($action == 'chart-of-accounts' || $action == 'chartOfAccounts') {
                $controller->chartOfAccounts();
            } elseif ($action == 'create-account' || $action == 'createAccount') {
                $controller->createAccount();
            } elseif ($action == 'edit-account' || $action == 'editAccount') {
                $controller->editAccount($id);
            } elseif ($action == 'journal-entries' || $action == 'journalEntries') {
                $controller->journalEntries();
            } elseif ($action == 'create-journal' || $action == 'createJournal') {
                $controller->createJournal();
            } elseif ($action == 'view-journal' || $action == 'viewJournal') {
                $controller->viewJournal($id);
            } elseif ($action == 'post-journal' || $action == 'postJournal') {
                $controller->postJournal($id);
            } elseif ($action == 'trial-balance' || $action == 'trialBalance') {
                $controller->trialBalance();
            } elseif ($action == 'balance-sheet' || $action == 'balanceSheet') {
                $controller->balanceSheet();
            } elseif ($action == 'profit-loss' || $action == 'profitLoss') {
                $controller->profitLoss();
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Accounting Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'employees':
        $controllerFile = 'controllers/EmployeeController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new EmployeeController();
            
            if ($action == 'view' && $id) {
                $controller->view($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } elseif ($action == 'leaves') {
                $controller->leaves();
            } elseif ($action == 'createLeave') {
                $controller->createLeave();
            } elseif ($action == 'approveLeave' && $id) {
                $controller->approveLeave($id);
            } elseif ($action == 'rejectLeave' && $id) {
                $controller->rejectLeave($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Employee Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'purchase-orders':
        $controllerFile = 'controllers/PurchaseOrderController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new PurchaseOrderController();
            
            if ($action == 'view' && $id) {
                $controller->view($id);
            } elseif ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } elseif ($action == 'approve' && $id) {
                $controller->approve($id);
            } elseif ($action == 'receive' && $id) {
                $controller->receive($id);
            } elseif ($action == 'payment' && $id) {
                $controller->payment($id);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Purchase Order Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
    
    case 'settings':
        $controllerFile = 'controllers/SettingController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new SettingController();
            
            if ($action == 'save') {
                $controller->save();
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Settings Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;

    case 'users':
        $controllerFile = 'controllers/UserController.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new UserController();
            
            if ($action == 'create') {
                $controller->create();
            } elseif ($action == 'edit' && $id) {
                $controller->edit($id);
            } elseif ($action == 'delete' && $id) {
                $controller->delete($id);
            } elseif ($action == 'roles') {
                $controller->roles();
            } elseif ($action == 'getRolePermissions' && $id) {
                $controller->getRolePermissions($id);
            } elseif ($action == 'updateRolePermissions' && $id) {
                $controller->updateRolePermissions($id);
            } elseif ($action == 'profile') {
                $controller->profile();
            } else {
                $controller->index();
            }
        }
        break;

    case 'roles':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->roles();
        break;

    case 'profile':
        require_once 'controllers/UserController.php';
        $controller = new UserController();
        $controller->profile();
        break;
    
    case 'reports':
        $controllerFile = 'controllers/ReportController.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new ReportController();
            
            if ($action == 'sales') {
                $controller->sales();
            } elseif ($action == 'expenses') {
                $controller->expenses();
            } elseif ($action == 'purchases') {
                $controller->purchases();
            } elseif ($action == 'profit-loss' || $action == 'profitLoss') {
                $controller->profitLoss();
            } elseif ($action == 'tax') {
                $controller->tax();
            } elseif ($action == 'inventory') {
                $controller->inventory();
            } elseif ($action == 'customers') {
                $controller->customers();
            } elseif ($action == 'export' && $id) {
                $format = $format_segment ?? $_GET['format'] ?? 'csv';
                $controller->export($id, $format);
            } else {
                $controller->index();
            }
        } else {
            echo "<h2>Reports Module</h2>";
            echo "<p>Coming soon...</p>";
            echo "<a href='dashboard'>Back to Dashboard</a>";
        }
        break;
        
    case 'test':
        // Test database connection
        require_once 'test.php';
        break;
        
    default:
        // 404 Page
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page you requested does not exist.</p>";
        echo "<a href='dashboard'>Go to Dashboard</a>";
        break;
}
