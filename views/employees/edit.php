<?php if (!$employee): ?>
    <div class="alert alert-danger">Employee not found</div>
    <?php return; ?>
<?php endif; ?>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="employees/view/<?= $employee['id'] ?>" class="btn btn-info mr-2"><i class="fas fa-eye"></i> View</a>
        <a href="employees" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form id="employeeForm">
    <input type="hidden" name="id" value="<?= $employee['id'] ?>">
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Personal Information</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?= $employee['date_of_birth'] ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($employee['mobile']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="male" <?= $employee['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $employee['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $employee['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="single" <?= $employee['marital_status'] == 'single' ? 'selected' : '' ?>>Single</option>
                                    <option value="married" <?= $employee['marital_status'] == 'married' ? 'selected' : '' ?>>Married</option>
                                    <option value="divorced" <?= $employee['marital_status'] == 'divorced' ? 'selected' : '' ?>>Divorced</option>
                                    <option value="widowed" <?= $employee['marital_status'] == 'widowed' ? 'selected' : '' ?>>Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($employee['address']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="<?= htmlspecialchars($employee['city']) ?>"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>State</label><input type="text" name="state" class="form-control" value="<?= htmlspecialchars($employee['state']) ?>"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Postal Code</label><input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($employee['postal_code']) ?>"></div></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Employment Information</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= $employee['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['department_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation</label>
                                <select name="designation_id" id="designation_id" class="form-control">
                                    <option value="">Select Designation</option>
                                    <?php foreach ($designations as $des): ?>
                                    <option value="<?= $des['id'] ?>" <?= $employee['designation_id'] == $des['id'] ? 'selected' : '' ?>><?= htmlspecialchars($des['designation_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employment Type</label>
                                <select name="employment_type" class="form-control">
                                    <option value="full_time" <?= $employee['employment_type'] == 'full_time' ? 'selected' : '' ?>>Full Time</option>
                                    <option value="part_time" <?= $employee['employment_type'] == 'part_time' ? 'selected' : '' ?>>Part Time</option>
                                    <option value="contract" <?= $employee['employment_type'] == 'contract' ? 'selected' : '' ?>>Contract</option>
                                    <option value="intern" <?= $employee['employment_type'] == 'intern' ? 'selected' : '' ?>>Intern</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Joining Date</label>
                                <input type="date" name="joining_date" class="form-control" value="<?= $employee['joining_date'] ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Reports To</label>
                        <select name="reporting_to" class="form-control">
                            <option value="">Select Manager</option>
                            <?php foreach ($managers as $mgr): ?>
                            <option value="<?= $mgr['id'] ?>" <?= $employee['reporting_to'] == $mgr['id'] ? 'selected' : '' ?>><?= htmlspecialchars($mgr['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active" <?= $employee['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $employee['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="on_leave" <?= $employee['status'] == 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Salary Information</h5></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Basic Salary ($)</label>
                        <input type="number" name="basic_salary" class="form-control" step="0.01" value="<?= $employee['basic_salary'] ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Housing</label><input type="number" name="housing_allowance" class="form-control" step="0.01" value="<?= $employee['housing_allowance'] ?>"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Transport</label><input type="number" name="transport_allowance" class="form-control" step="0.01" value="<?= $employee['transport_allowance'] ?>"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Other</label><input type="number" name="other_allowances" class="form-control" step="0.01" value="<?= $employee['other_allowances'] ?>"></div></div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Bank Information</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Bank Name</label><input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($employee['bank_name']) ?>"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Account Number</label><input type="text" name="bank_account_number" class="form-control" value="<?= htmlspecialchars($employee['bank_account_number']) ?>"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Routing Number</label><input type="text" name="bank_routing_number" class="form-control" value="<?= htmlspecialchars($employee['bank_routing_number']) ?>"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Tax ID</label><input type="text" name="tax_id" class="form-control" value="<?= htmlspecialchars($employee['tax_id']) ?>"></div></div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Emergency Contact</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Contact Name</label><input type="text" name="emergency_contact_name" class="form-control" value="<?= htmlspecialchars($employee['emergency_contact_name']) ?>"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Relationship</label><input type="text" name="emergency_contact_relation" class="form-control" value="<?= htmlspecialchars($employee['emergency_contact_relation']) ?>"></div></div>
                    </div>
                    <div class="form-group"><label>Contact Phone</label><input type="text" name="emergency_contact_phone" class="form-control" value="<?= htmlspecialchars($employee['emergency_contact_phone']) ?>"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($employee['notes']) ?></textarea>
    </div>
    
    <hr>
    
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Employee</button>
    <a href="employees/view/<?= $employee['id'] ?>" class="btn btn-secondary">Cancel</a>
</form>

<script>
$('#department_id').change(function() {
    const deptId = $(this).val();
    if (deptId) {
        $.get('employees/getDesignations/' + deptId, function(data) {
            $('#designation_id').html('<option value="">Select Designation</option>');
            data.forEach(d => $('#designation_id').append(`<option value="${d.id}">${d.designation_name}</option>`));
        });
    }
});

$('#employeeForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'employees/edit/<?= $employee['id'] ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) window.location.href = 'employees/view/<?= $employee['id'] ?>';
            else alert('Error: ' + response.message);
        }
    });
});
</script>