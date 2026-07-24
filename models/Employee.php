<?php
class Employee {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT e.*, d.department_name, des.designation_name,
                CONCAT(e.first_name, ' ', e.last_name) as full_name
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN designations des ON e.designation_id = des.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['department_id'])) {
            $sql .= " AND e.department_id = :department_id";
            $params[':department_id'] = $filters['department_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search OR e.email LIKE :search OR e.employee_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY e.first_name, e.last_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT e.*, d.department_name, des.designation_name,
                CONCAT(m.first_name, ' ', m.last_name) as manager_name,
                CONCAT(e.first_name, ' ', e.last_name) as full_name
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN designations des ON e.designation_id = des.id
                LEFT JOIN employees m ON e.reporting_to = m.id
                WHERE e.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $employee_code = $this->generateEmployeeCode();
        
        $sql = "INSERT INTO employees (
                    employee_code, first_name, last_name, email, phone, mobile,
                    date_of_birth, gender, marital_status, nationality,
                    address, city, state, postal_code, country,
                    department_id, designation_id, employment_type,
                    joining_date, reporting_to, bank_name, bank_account_number,
                    bank_routing_number, tax_id, basic_salary, housing_allowance,
                    transport_allowance, other_allowances, emergency_contact_name,
                    emergency_contact_phone, emergency_contact_relation, status, notes
                ) VALUES (
                    :employee_code, :first_name, :last_name, :email, :phone, :mobile,
                    :date_of_birth, :gender, :marital_status, :nationality,
                    :address, :city, :state, :postal_code, :country,
                    :department_id, :designation_id, :employment_type,
                    :joining_date, :reporting_to, :bank_name, :bank_account_number,
                    :bank_routing_number, :tax_id, :basic_salary, :housing_allowance,
                    :transport_allowance, :other_allowances, :emergency_contact_name,
                    :emergency_contact_phone, :emergency_contact_relation, :status, :notes
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':employee_code' => $employee_code,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':mobile' => $data['mobile'] ?? null,
            ':date_of_birth' => $data['date_of_birth'] ?? null,
            ':gender' => $data['gender'] ?? null,
            ':marital_status' => $data['marital_status'] ?? null,
            ':nationality' => $data['nationality'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':state' => $data['state'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
            ':country' => $data['country'] ?? 'USA',
            ':department_id' => $data['department_id'] ?? null,
            ':designation_id' => $data['designation_id'] ?? null,
            ':employment_type' => $data['employment_type'] ?? 'full_time',
            ':joining_date' => $data['joining_date'] ?? null,
            ':reporting_to' => $data['reporting_to'] ?? null,
            ':bank_name' => $data['bank_name'] ?? null,
            ':bank_account_number' => $data['bank_account_number'] ?? null,
            ':bank_routing_number' => $data['bank_routing_number'] ?? null,
            ':tax_id' => $data['tax_id'] ?? null,
            ':basic_salary' => $data['basic_salary'] ?? 0,
            ':housing_allowance' => $data['housing_allowance'] ?? 0,
            ':transport_allowance' => $data['transport_allowance'] ?? 0,
            ':other_allowances' => $data['other_allowances'] ?? 0,
            ':emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            ':emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            ':emergency_contact_relation' => $data['emergency_contact_relation'] ?? null,
            ':status' => $data['status'] ?? 'active',
            ':notes' => $data['notes'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE employees SET
                    first_name = :first_name, last_name = :last_name, email = :email,
                    phone = :phone, mobile = :mobile, date_of_birth = :date_of_birth,
                    gender = :gender, marital_status = :marital_status,
                    address = :address, city = :city, state = :state,
                    postal_code = :postal_code, department_id = :department_id,
                    designation_id = :designation_id, employment_type = :employment_type,
                    reporting_to = :reporting_to, bank_name = :bank_name,
                    bank_account_number = :bank_account_number, tax_id = :tax_id,
                    basic_salary = :basic_salary, housing_allowance = :housing_allowance,
                    transport_allowance = :transport_allowance, other_allowances = :other_allowances,
                    emergency_contact_name = :emergency_contact_name,
                    emergency_contact_phone = :emergency_contact_phone,
                    emergency_contact_relation = :emergency_contact_relation,
                    status = :status, notes = :notes
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $data[':id'] = $id;
        return $stmt->execute($data);
    }
    
    private function generateEmployeeCode() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as count FROM employees WHERE YEAR(created_at) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year]);
        $result = $stmt->fetch();
        return 'EMP-' . $year . '-' . str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
    }
    
    public function getDepartments() {
        $sql = "SELECT * FROM departments WHERE is_active = 1 ORDER BY department_name";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getDesignations($department_id = null) {
        $sql = "SELECT * FROM designations WHERE is_active = 1";
        if ($department_id) {
            $sql .= " AND department_id = :department_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':department_id' => $department_id]);
        } else {
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }
    
    public function getManagers() {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as name 
                FROM employees 
                WHERE status = 'active' 
                ORDER BY first_name";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getLeaveRequests($filters = []) {
        $sql = "SELECT lr.*, e.employee_code, CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                lt.leave_type, CONCAT(a.first_name, ' ', a.last_name) as approved_by_name
                FROM leave_requests lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN employees a ON lr.approved_by = a.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND lr.employee_id = :employee_id";
            $params[':employee_id'] = $filters['employee_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND lr.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getLeaveTypes() {
        $sql = "SELECT * FROM leave_types WHERE is_active = 1 ORDER BY leave_type";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function createLeaveRequest($data) {
        $leave_number = 'LEAVE-' . date('Ymd') . '-' . rand(1000, 9999);
        
        // Calculate days
        $from = new DateTime($data['from_date']);
        $to = new DateTime($data['to_date']);
        $diff = $from->diff($to);
        $total_days = $diff->days + 1;
        
        $sql = "INSERT INTO leave_requests (
                    leave_number, employee_id, leave_type_id, from_date,
                    to_date, total_days, reason, status
                ) VALUES (
                    :leave_number, :employee_id, :leave_type_id, :from_date,
                    :to_date, :total_days, :reason, 'pending'
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':leave_number' => $leave_number,
            ':employee_id' => $data['employee_id'],
            ':leave_type_id' => $data['leave_type_id'],
            ':from_date' => $data['from_date'],
            ':to_date' => $data['to_date'],
            ':total_days' => $total_days,
            ':reason' => $data['reason'] ?? null
        ]);
    }
    
    public function approveLeave($id, $approved_by) {
        $sql = "UPDATE leave_requests SET status = 'approved', approved_by = :approved_by, approved_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':approved_by' => $approved_by]);
    }
    
    public function rejectLeave($id, $approved_by, $reason) {
        $sql = "UPDATE leave_requests SET status = 'rejected', approved_by = :approved_by, approved_at = NOW(), rejection_reason = :reason WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':approved_by' => $approved_by, ':reason' => $reason]);
    }
}
