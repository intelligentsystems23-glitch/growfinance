<?php if (!$expense): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Expense not found</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-3xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">receipt_long</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($expense['expense_number']) ?></h1>
            <p class="text-xs text-slate-500">Recorded on <?= date('d/m/Y', strtotime($expense['expense_date'])) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>/expenses" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to List</span>
        </a>
        <a href="<?= BASE_URL ?>/expenses/pdf/<?= $expense['id'] ?>" target="_blank" class="px-3.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
            <span>Export PDF</span>
        </a>
        <?php if ($expense['payment_status'] !== 'paid'): ?>
        <a href="<?= BASE_URL ?>/expenses/edit/<?= $expense['id'] ?>" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">edit</span>
            <span>Edit</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-3xl mx-auto space-y-6">
    <!-- Primary Details Card -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Amount -->
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Amount</span>
                <div class="text-2xl font-bold text-slate-950">
                    <?= htmlspecialchars($currency) ?> <?= number_format($expense['amount'], 2) ?>
                </div>
            </div>
            
            <!-- Payment Status -->
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Payment Status</span>
                <div>
                    <?php
                    $status_colors = [
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'paid' => 'bg-green-50 text-green-600 border-green-200',
                        'cancelled' => 'bg-red-50 text-red-600 border-red-200'
                    ];
                    $color_class = $status_colors[$expense['payment_status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    ?>
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold border <?= $color_class ?>">
                        <?= ucfirst($expense['payment_status']) ?>
                    </span>
                </div>
            </div>

            <!-- Category -->
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Category</span>
                <div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                        <?= htmlspecialchars($expense['category_name'] ?? 'Uncategorized') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 my-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Vendor -->
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500">Vendor/Supplier</span>
                <p class="text-sm font-medium text-slate-900"><?= htmlspecialchars($expense['vendor_name'] ?? '-') ?></p>
            </div>

            <!-- Date -->
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500">Expense Date</span>
                <p class="text-sm font-medium text-slate-900"><?= date('F j, Y', strtotime($expense['expense_date'])) ?></p>
            </div>

            <!-- Payment Method -->
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500">Payment Method</span>
                <p class="text-sm font-medium text-slate-900"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $expense['payment_method'] ?? '-'))) ?></p>
            </div>

            <!-- Reference Number -->
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500">Reference Number</span>
                <p class="text-sm font-medium text-slate-900"><?= htmlspecialchars($expense['reference_number'] ?? '-') ?></p>
            </div>
        </div>

        <div class="border-t border-slate-100 my-4"></div>

        <!-- Description -->
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500">Description</span>
            <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-md border border-slate-100 leading-relaxed">
                <?= nl2br(htmlspecialchars($expense['description'])) ?>
            </p>
        </div>

        <!-- Notes -->
        <?php if (!empty($expense['notes'])): ?>
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500">Additional Notes</span>
            <p class="text-sm text-slate-600 bg-amber-50/30 p-3 rounded-md border border-amber-100/50 italic leading-relaxed">
                <?= nl2br(htmlspecialchars($expense['notes'])) ?>
            </p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Approval Status Card -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full <?= $expense['approved_by'] ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' ?> flex items-center justify-center">
                <span class="material-symbols-outlined text-lg">
                    <?= $expense['approved_by'] ? 'verified' : 'pending_actions' ?>
                </span>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-slate-900">
                    <?= $expense['approved_by'] ? 'Approved Expense' : 'Awaiting Approval' ?>
                </h4>
                <p class="text-xs text-slate-500">
                    <?php if ($expense['approved_by']): ?>
                        Approved by <?= htmlspecialchars($expense['approved_by_name']) ?> on <?= date('d/m/Y H:i', strtotime($expense['approved_at'])) ?>
                    <?php else: ?>
                        This expense has not been approved yet.
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <?php if (!$expense['approved_by']): ?>
        <button onclick="approveExpense(<?= $expense['id'] ?>)" class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs font-semibold transition-all shadow-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">check</span>
            <span>Approve Now</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<script>
function approveExpense(id) {
    if (confirm('Approve this expense?')) {
        $.ajax({
            url: '<?= BASE_URL ?? '' ?>/expenses/approve/' + id,
            type: 'POST',
            success: function(response) {
                location.reload();
            },
            error: function() {
                location.reload();
            }
        });
    }
}
</script>
