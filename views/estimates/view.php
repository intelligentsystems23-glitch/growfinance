<?php if (!$estimate): ?><div class="alert alert-danger">Estimate not found</div><?php return; endif; ?>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if (in_array($estimate['status'], ['draft', 'sent', 'accepted'])): ?>
        <a href="estimates/convert/<?= $estimate['id'] ?>" class="btn btn-success mr-2" onclick="return confirm('Convert to invoice?')"><i class="fas fa-file-invoice"></i> Convert to Invoice</a>
        <?php endif; ?>
        <button onclick="window.print()" class="btn btn-info mr-2"><i class="fas fa-print"></i> Print</button>
        <a href="estimates" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3"><div class="card-header">Bill To</div><div class="card-body"><strong><?= htmlspecialchars($estimate['company_name']) ?></strong><br><?= htmlspecialchars($estimate['address']) ?><br><?= htmlspecialchars($estimate['email']) ?></div></div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3"><div class="card-header">Estimate Details</div><div class="card-body"><p><strong>Estimate #:</strong> <?= htmlspecialchars($estimate['estimate_number']) ?></p><p><strong>Date:</strong> <?= date('d/m/Y', strtotime($estimate['estimate_date'])) ?></p><p><strong>Expiry:</strong> <?= $estimate['expiry_date'] ? date('d/m/Y', strtotime($estimate['expiry_date'])) : 'N/A' ?></p><p><strong>Status:</strong> <span class="badge badge-info"><?= ucfirst($estimate['status']) ?></span></p></div></div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead><tr><th>Item</th><th>Description</th><th class="text-right">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr><td><?= htmlspecialchars($item['item_name'] ?? 'N/A') ?></td><td><?= htmlspecialchars($item['description']) ?></td><td class="text-right"><?= $item['quantity'] ?></td><td class="text-right"><?= htmlspecialchars($currency) ?> <?= number_format($item['unit_price'], 2) ?></td><td class="text-right"><?= htmlspecialchars($currency) ?> <?= number_format($item['total'], 2) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr><td colspan="4" class="text-right">Subtotal:</td><td class="text-right"><?= htmlspecialchars($currency) ?> <?= number_format($estimate['subtotal'], 2) ?></td></tr>
                <tr><td colspan="4" class="text-right">Tax:</td><td class="text-right"><?= htmlspecialchars($currency) ?> <?= number_format($estimate['tax_amount'], 2) ?></td></tr>
                <tr><td colspan="4" class="text-right"><strong>Total:</strong></td><td class="text-right"><strong><?= htmlspecialchars($currency) ?> <?= number_format($estimate['total_amount'], 2) ?></strong></td></tr>
            </tfoot>
        </table>
    </div>
</div>

<?php if ($estimate['notes']): ?><div class="card mt-3"><div class="card-header">Notes</div><div class="card-body"><?= nl2br(htmlspecialchars($estimate['notes'])) ?></div></div><?php endif; ?>
<?php if ($estimate['terms']): ?><div class="card mt-3"><div class="card-header">Terms</div><div class="card-body"><?= nl2br(htmlspecialchars($estimate['terms'])) ?></div></div><?php endif; ?>

<?php if ($estimate['status'] != 'invoiced'): ?>
<div class="card mt-3"><div class="card-header">Update Status</div><div class="card-body"><select id="statusSelect" class="form-control w-25 d-inline"><option value="draft" <?= $estimate['status']=='draft'?'selected':'' ?>>Draft</option><option value="sent" <?= $estimate['status']=='sent'?'selected':'' ?>>Sent</option><option value="accepted" <?= $estimate['status']=='accepted'?'selected':'' ?>>Accepted</option><option value="rejected" <?= $estimate['status']=='rejected'?'selected':'' ?>>Rejected</option></select> <button class="btn btn-primary" onclick="updateStatus(<?= $estimate['id'] ?>)">Update</button></div></div>
<script>function updateStatus(id){$.ajax({url:'estimates/updateStatus/'+id,type:'POST',data:{status:$('#statusSelect').val()},success:function(r){if(r.success)location.reload()}})}</script>
<?php endif; ?>