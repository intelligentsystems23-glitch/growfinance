<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-primary" data-toggle="modal" data-target="#leaveModal"><i class="fas fa-plus"></i> Request Leave</button>
        <a href="employees" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Back to Employees</a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-4">
                <select name="employee_id" class="form-control">
                    <option value="">All Employees</option>
                    <?php foreach ($employees as $emp): ?>
                    <option value="<?= $emp['id'] ?>" <?= ($_GET['employee_id'] ?? '') == $emp['id'] ? 'selected' : '' ?>><?= htmlspecialchars($emp['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Leave Requests Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Leave #</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['leave_number']) ?></td>
                        <td><?= htmlspecialchars($leave['employee_name']) ?></td>
                        <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                        <td><?= date('d/m/Y', strtotime($leave['from_date'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($leave['to_date'])) ?></td>
                        <td><?= $leave['total_days'] ?></td>
                        <td><?= htmlspecialchars(substr($leave['reason'], 0, 30)) ?>...</td>
                        <td>
                            <?php
                            $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                            $color = $statusColors[$leave['status']] ?? 'secondary';
                            ?>
                            <span class="badge badge-<?= $color ?>"><?= ucfirst($leave['status']) ?></span>
                        </td>
                        <td>
                            <?php if ($leave['status'] == 'pending'): ?>
                            <button class="btn btn-sm btn-success" onclick="approveLeave(<?= $leave['id'] ?>)"><i class="fas fa-check"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="rejectLeave(<?= $leave['id'] ?>)"><i class="fas fa-times"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Leave Request Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Request Leave</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form id="leaveForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Employee *</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Leave Type *</label>
                        <select name="leave_type_id" class="form-control" required>
                            <?php foreach ($leave_types as $lt): ?>
                            <option value="<?= $lt['id'] ?>"><?= htmlspecialchars($lt['leave_type']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>From Date *</label><input type="date" name="from_date" class="form-control" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label>To Date *</label><input type="date" name="to_date" class="form-control" required></div></div>
                    </div>
                    <div class="form-group"><label>Reason</label><textarea name="reason" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Reject Leave Request</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form id="rejectForm">
                <input type="hidden" name="leave_id" id="reject_leave_id">
                <div class="modal-body">
                    <div class="form-group"><label>Reason for Rejection *</label><textarea name="reason" class="form-control" rows="3" required></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#leaveForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'employees/createLeave',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) location.reload();
            else alert('Error: ' + response.message);
        }
    });
});

function approveLeave(id) {
    if (confirm('Approve this leave request?')) {
        $.ajax({ url: 'employees/approveLeave/' + id, type: 'POST', success: function(r) { if (r.success) location.reload(); } });
    }
}

function rejectLeave(id) {
    $('#reject_leave_id').val(id);
    $('#rejectModal').modal('show');
}

$('#rejectForm').submit(function(e) {
    e.preventDefault();
    const id = $('#reject_leave_id').val();
    $.ajax({
        url: 'employees/rejectLeave/' + id,
        type: 'POST',
        data: $(this).serialize(),
        success: function(r) { if (r.success) location.reload(); }
    });
});
</script>
