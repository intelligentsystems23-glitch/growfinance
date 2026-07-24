<?php if (!$employee): ?>
    <div class="alert alert-danger">Employee not found</div>
    <?php return; ?>
<?php endif; ?>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="employees/edit/<?= $employee['id'] ?>" class="btn btn-primary mr-2"><i class="fas fa-edit"></i> Edit</a>
        <a href="employees" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                    <i class="fas fa-user fa-4x text-secondary"></i>
                </div>
                <h4><?= htmlspecialchars($employee['full_name']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($employee['designation_name'] ?? 'No Designation') ?></p>
                <span class="badge badge-<?= $employee['status'] == 'active' ? 'success' : ($employee['status'] == 'on_leave' ? 'warning' : 'secondary') ?>"><?= ucfirst(str_replace('_', ' ', $employee['status'])) ?></span>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Contact Information</h5></div>
            <div class="card-body">
                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($employee['email']) ?></p>
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($employee['phone'] ?: 'N/A') ?></p>
                <p><i class="fas fa-mobile"></i> <?= htmlspecialchars($employee['mobile'] ?: 'N/A') ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($employee['address'] ?: 'N/A') ?></p>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Emergency Contact</h5></div>
            <div class="card-body">
                <p><strong><?= htmlspecialchars($employee['emergency_contact_name'] ?: 'N/A') ?></strong></p>
                <p><?= htmlspecialchars($employee['emergency_contact_relation'] ?: '') ?></p>
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($employee['emergency_contact_phone'] ?: 'N/A') ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Employment Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Department:</strong> <?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></p>
                        <p><strong>Designation:</strong> <?= htmlspecialchars($employee['designation_name'] ?? 'N/A') ?></p>
                        <p><strong>Employment Type:</strong> <?= ucfirst(str_replace('_', ' ', $employee['employment_type'] ?? 'N/A')) ?></p>
                        <p><strong>Reports To:</strong> <?= htmlspecialchars($employee['manager_name'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Joining Date:</strong> <?= $employee['joining_date'] ? date('d/m/Y', strtotime($employee['joining_date'])) : 'N/A' ?></p>
                        <p><strong>Employee Code:</strong> <?= htmlspecialchars($employee['employee_code']) ?></p>
                        <p><strong>Date of Birth:</strong> <?= $employee['date_of_birth'] ? date('d/m/Y', strtotime($employee['date_of_birth'])) : 'N/A' ?></p>
                        <p><strong>Gender:</strong> <?= ucfirst($employee['gender'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Salary Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><p><strong>Basic Salary:</strong><br><?= htmlspecialchars($currency) ?> <?= number_format($employee['basic_salary'], 2) ?></p></div>
                    <div class="col-md-3"><p><strong>Housing:</strong><br><?= htmlspecialchars($currency) ?> <?= number_format($employee['housing_allowance'], 2) ?></p></div>
                    <div class="col-md-3"><p><strong>Transport:</strong><br><?= htmlspecialchars($currency) ?> <?= number_format($employee['transport_allowance'], 2) ?></p></div>
                    <div class="col-md-3"><p><strong>Other:</strong><br><?= htmlspecialchars($currency) ?> <?= number_format($employee['other_allowances'], 2) ?></p></div>
                </div>
                <hr>
                <p><strong>Total Monthly:</strong> <span class="h4 text-success"><?= htmlspecialchars($currency) ?> <?= number_format($employee['basic_salary'] + $employee['housing_allowance'] + $employee['transport_allowance'] + $employee['other_allowances'], 2) ?></span></p>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Bank Information</h5></div>
            <div class="card-body">
                <p><strong>Bank:</strong> <?= htmlspecialchars($employee['bank_name'] ?: 'N/A') ?></p>
                <p><strong>Account Number:</strong> <?= htmlspecialchars($employee['bank_account_number'] ?: 'N/A') ?></p>
                <p><strong>Tax ID:</strong> <?= htmlspecialchars($employee['tax_id'] ?: 'N/A') ?></p>
            </div>
        </div>
        
        <?php if ($employee['notes']): ?>
        <div class="card"><div class="card-header"><h5 class="mb-0">Notes</h5></div><div class="card-body"><p><?= nl2br(htmlspecialchars($employee['notes'])) ?></p></div></div>
        <?php endif; ?>
    </div>
</div>