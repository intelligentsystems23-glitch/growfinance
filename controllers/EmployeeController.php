<?php
if (!class_exists('Employee')) {
    require_once __DIR__ . '/../models/Employee.php';
}

class EmployeeController {
    private $employeeModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->employeeModel = new Employee($this->db);
    }
    
    public function index() {
        $filters = [];
        if (isset($_GET['department_id'])) $filters['department_id'] = $_GET['department_id'];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
        
        $employees = $this->employeeModel->getAll($filters);
        $departments = $this->employeeModel->getDepartments();
        
        $title = 'Employees';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/employees/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $employee = $this->employeeModel->getById($id);
        
        if (!$employee) {
            header('Location: ' . BASE_URL . '/employees');
            exit;
        }
        
        $title = 'Employee Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/employees/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->employeeModel->create($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $departments = $this->employeeModel->getDepartments();
        $managers = $this->employeeModel->getManagers();
        
        $title = 'Add Employee';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/employees/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->employeeModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $employee = $this->employeeModel->getById($id);
        $departments = $this->employeeModel->getDepartments();
        $designations = $this->employeeModel->getDesignations($employee['department_id']);
        $managers = $this->employeeModel->getManagers();
        
        $title = 'Edit Employee';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/employees/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function leaves() {
        $filters = [];
        if (isset($_GET['employee_id'])) $filters['employee_id'] = $_GET['employee_id'];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        
        $leave_requests = $this->employeeModel->getLeaveRequests($filters);
        $employees = $this->employeeModel->getAll(['status' => 'active']);
        $leave_types = $this->employeeModel->getLeaveTypes();
        
        $title = 'Leave Requests';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/employees/leaves.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function createLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $this->employeeModel->createLeaveRequest($_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function approveLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $this->employeeModel->approveLeave($id, $user_id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function rejectLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            try {
                $user_id = $_SESSION['user_id'] ?? 1;
                $reason = $_POST['reason'] ?? '';
                $this->employeeModel->rejectLeave($id, $user_id, $reason);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
}
