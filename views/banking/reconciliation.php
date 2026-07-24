<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Bank Reconciliation</h1>
        <p class="text-sm text-slate-500 mt-0.5">Match your ledger transactions with bank statement records</p>
    </div>
    <a href="<?= BASE_URL ?>/banking" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Back to Banking
    </a>
</div>

<!-- Account Selection Form -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" id="accountSelectForm" class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Select Bank Account to Reconcile</label>
            <div class="relative">
                <select name="account_id" onchange="document.getElementById('accountSelectForm').submit()" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= ($account_id == $acc['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($acc['account_name']) ?> (<?= htmlspecialchars($acc['bank_name']) ?>) - Balance: <?= htmlspecialchars($currency) ?> <?= number_format($acc['current_balance'], 2) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
    </form>
</div>

<?php if ($selected_account): ?>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    
    <!-- LEFT COLUMN: Transactions Ledger List (Col span 8) -->
    <div class="lg:col-span-8 space-y-4">
        
        <!-- Unreconciled Transactions -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="font-semibold text-slate-900">Unreconciled Transactions</h3>
                    <p class="text-xs text-slate-500">Select statement matches</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <?= count($unreconciled_transactions) ?> Pending
                </span>
            </div>
            
            <form id="reconcileForm">
                <input type="hidden" name="reconcile" value="1">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left" id="unreconciledTable">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5 text-center w-10">
                                    <input type="checkbox" id="selectAllUnreconciled" class="rounded border-slate-300 text-primary focus:ring-primary/20">
                                </th>
                                <th class="px-4 py-2.5">Date</th>
                                <th class="px-4 py-2.5">Transaction #</th>
                                <th class="px-4 py-2.5 text-center">Type</th>
                                <th class="px-4 py-2.5">Description</th>
                                <th class="px-4 py-2.5 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($unreconciled_transactions as $trx): ?>
                            <?php 
                            $is_deposit = in_array($trx['transaction_type'], ['deposit', 'receipt', 'interest']);
                            $signed_amount = $is_deposit ? $trx['amount'] : -$trx['amount'];
                            ?>
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="ids[]" value="<?= $trx['id'] ?>" data-amount="<?= $signed_amount ?>" class="trx-checkbox rounded border-slate-300 text-primary focus:ring-primary/20">
                                </td>
                                <td class="px-4 py-2 text-slate-500"><?= date('d/m/Y', strtotime($trx['transaction_date'])) ?></td>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-700"><?= htmlspecialchars($trx['transaction_number']) ?></td>
                                <td class="px-4 py-2 text-center">
                                    <?php
                                    $typeColors = [
                                        'deposit' => 'bg-green-50 text-green-600 border-green-200',
                                        'receipt' => 'bg-green-50 text-green-600 border-green-200',
                                        'interest' => 'bg-cyan-50 text-cyan-600 border-cyan-200',
                                        'withdrawal' => 'bg-red-50 text-red-600 border-red-200',
                                        'payment' => 'bg-red-50 text-red-600 border-red-200',
                                        'fee' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'transfer' => 'bg-indigo-50 text-indigo-600 border-indigo-200'
                                    ];
                                    $color_class = $typeColors[$trx['transaction_type']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                    ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold border <?= $color_class ?>">
                                        <?= ucfirst($trx['transaction_type']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-slate-500 max-w-[220px] truncate" title="<?= htmlspecialchars($trx['description']) ?>">
                                    <?= htmlspecialchars($trx['description'] ?? '-') ?>
                                </td>
                                <td class="px-4 py-2 text-right font-semibold <?= $is_deposit ? 'text-green-600' : 'text-slate-800' ?>">
                                    <?= $is_deposit ? '+' : '-' ?><?= htmlspecialchars($currency) ?> <?= number_format($trx['amount'], 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($unreconciled_transactions)): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center py-4 text-slate-400">
                                        <span class="material-symbols-outlined text-3xl mb-2 opacity-50 text-green-500">task_alt</span>
                                        <p class="text-sm font-semibold text-slate-900">All caught up!</p>
                                        <p class="text-xs text-slate-400 mt-0.5">All matching transactions have been reconciled.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($unreconciled_transactions)): ?>
                <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium select-count-label">0 items selected</span>
                    <button type="submit" class="flex items-center gap-1.5 bg-primary text-white px-4 py-1.5 rounded-lg text-xs font-semibold shadow-sm hover:bg-primary/95 hover:shadow transition-all">
                        <span class="material-symbols-outlined text-xs">done_all</span>
                        <span>Reconcile Selected</span>
                    </button>
                </div>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Reconciled History -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="font-semibold text-slate-900">Recently Reconciled</h3>
                    <p class="text-xs text-slate-500">History of matched ledger entries</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200">
                    <?= count($reconciled_transactions) ?> Cleared
                </span>
            </div>
            
            <form id="unreconcileForm">
                <input type="hidden" name="reconcile" value="0">
                <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200 sticky top-0">
                            <tr>
                                <th class="px-4 py-2.5 text-center w-10">
                                    <input type="checkbox" id="selectAllReconciled" class="rounded border-slate-300 text-slate-500 focus:ring-slate-200">
                                </th>
                                <th class="px-4 py-2.5">Date</th>
                                <th class="px-4 py-2.5">Transaction #</th>
                                <th class="px-4 py-2.5 text-center">Type</th>
                                <th class="px-4 py-2.5">Description</th>
                                <th class="px-4 py-2.5 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($reconciled_transactions as $trx): ?>
                            <?php $is_deposit = in_array($trx['transaction_type'], ['deposit', 'receipt', 'interest']); ?>
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="ids[]" value="<?= $trx['id'] ?>" class="reconciled-checkbox rounded border-slate-300 text-slate-500 focus:ring-slate-200">
                                </td>
                                <td class="px-4 py-2 text-slate-500"><?= date('d/m/Y', strtotime($trx['transaction_date'])) ?></td>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-500"><?= htmlspecialchars($trx['transaction_number']) ?></td>
                                <td class="px-4 py-2 text-center">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold border bg-slate-50 text-slate-500 border-slate-200">
                                        <?= ucfirst($trx['transaction_type']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-slate-400 max-w-[220px] truncate" title="<?= htmlspecialchars($trx['description']) ?>">
                                    <?= htmlspecialchars($trx['description'] ?? '-') ?>
                                </td>
                                <td class="px-4 py-2 text-right font-semibold text-slate-500">
                                    <?= $is_deposit ? '+' : '-' ?><?= htmlspecialchars($currency) ?> <?= number_format($trx['amount'], 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($reconciled_transactions)): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">No reconciled history found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($reconciled_transactions)): ?>
                <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium reconciled-count-label">0 items selected</span>
                    <button type="submit" class="flex items-center gap-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        <span class="material-symbols-outlined text-xs">undo</span>
                        <span>Mark as Unreconciled</span>
                    </button>
                </div>
                <?php endif; ?>
            </form>
        </div>
        
    </div>
    
    <!-- RIGHT COLUMN: Reconciliation Workstation (Col span 4) -->
    <div class="lg:col-span-4 space-y-4">
        
        <!-- Workstation Panel -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-4">
            <h3 class="font-bold text-slate-900 text-sm mb-3">Reconciliation Workstation</h3>
            
            <div class="space-y-3.5">
                <!-- Ledger Balance -->
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Ledger balance</span>
                    <div class="text-xl font-bold text-slate-800">
                        <?= htmlspecialchars($currency) ?> <span id="ledgerBalance"><?= number_format($selected_account['current_balance'], 2) ?></span>
                    </div>
                </div>
                
                <!-- Statement Ending Balance (User Input) -->
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Bank Statement Balance</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs"><?= htmlspecialchars($currency) ?></span>
                        <input type="number" id="statementBalance" class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-800" step="0.01" placeholder="Enter ending balance...">
                    </div>
                </div>
                
                <div class="border-t border-slate-100 my-3"></div>
                
                <!-- Computations -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Cleared in Selection:</span>
                        <span class="font-semibold text-slate-800" id="clearedSelected"><?= htmlspecialchars($currency) ?> 0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Expected Balance:</span>
                        <span class="font-semibold text-slate-800" id="expectedBalance"><?= htmlspecialchars($currency) ?> <?= number_format($selected_account['current_balance'], 2) ?></span>
                    </div>
                    
                    <div class="border-t border-dashed border-slate-100 pt-2 flex justify-between items-center font-bold text-sm">
                        <span>Difference:</span>
                        <span class="text-red-500" id="reconcileDiff"><?= htmlspecialchars($currency) ?> 0.00</span>
                    </div>
                </div>
                
                <!-- Status Indicators -->
                <div id="diffIndicator" class="p-3 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 text-xs flex items-start gap-2">
                    <span class="material-symbols-outlined text-slate-400 text-sm mt-0.5">info</span>
                    <div>
                        <p class="font-bold">Unbalanced</p>
                        <p class="text-slate-500 mt-0.5">Adjust Statement Balance or check items to clear the differences.</p>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

<script>
// Select All Unreconciled
$('#selectAllUnreconciled').change(function() {
    $('.trx-checkbox').prop('checked', this.checked).trigger('change');
});

// Select All Reconciled
$('#selectAllReconciled').change(function() {
    $('.reconciled-checkbox').prop('checked', this.checked).trigger('change');
});

// Update labels & worksheet
$('.trx-checkbox').change(function() {
    const checkedBoxes = $('.trx-checkbox:checked');
    $('.select-count-label').text(`${checkedBoxes.length} items selected`);
    calculateWorksheet();
});

$('.reconciled-checkbox').change(function() {
    const checkedBoxes = $('.reconciled-checkbox:checked');
    $('.reconciled-count-label').text(`${checkedBoxes.length} items selected`);
});

// Handle user changing bank statement ending balance
$('#statementBalance').on('input', function() {
    calculateWorksheet();
});

// Compute worksheet math
function calculateWorksheet() {
    const ledger = parseFloat(<?= json_encode($selected_account['current_balance']) ?>);
    let cleared = 0;
    
    $('.trx-checkbox:checked').each(function() {
        cleared += parseFloat($(this).data('amount')) || 0;
    });
    
    // Expected balance = ledger + selected cleared
    const expected = ledger + cleared;
    
    // Ending Bank Balance (statement balance input)
    const statementVal = $('#statementBalance').val();
    const statement = statementVal ? parseFloat(statementVal) : null;
    
    // Render cleared selected
    $('#clearedSelected').text(SYSTEM_CURRENCY + ' ' + (cleared >= 0 ? '+' : '-') + Math.abs(cleared).toFixed(2));
    $('#expectedBalance').text(SYSTEM_CURRENCY + ' ' + expected.toFixed(2));
    
    const diffIndicator = $('#diffIndicator');
    const diffLabel = $('#reconcileDiff');
    
    if (statement === null) {
        diffLabel.text(SYSTEM_CURRENCY + ' --');
        diffLabel.removeClass('text-green-600 text-red-500').addClass('text-slate-500');
        diffIndicator.html(`
            <span class="material-symbols-outlined text-slate-400 text-sm mt-0.5">info</span>
            <div>
                <p class="font-bold">Missing Statement Balance</p>
                <p class="text-slate-500 mt-0.5">Please fill in ending statement balance from your bank report.</p>
            </div>
        `).removeClass('bg-green-50 text-green-700 border-green-200').addClass('bg-slate-50 text-slate-600 border-slate-200');
        return;
    }
    
    const difference = statement - expected;
    diffLabel.text(SYSTEM_CURRENCY + ' ' + difference.toFixed(2));
    
    if (Math.abs(difference) < 0.005) {
        diffLabel.removeClass('text-red-500 text-slate-500').addClass('text-green-600');
        diffIndicator.html(`
            <span class="material-symbols-outlined text-green-500 text-sm mt-0.5">check_circle</span>
            <div>
                <p class="font-bold text-green-800">Balanced!</p>
                <p class="text-green-600 mt-0.5">The difference is zero. You can safely reconcile the selected ledger matches.</p>
            </div>
        `).removeClass('bg-slate-50 text-slate-600 border-slate-200 bg-red-50 border-red-200 text-red-700').addClass('bg-green-50 text-green-700 border-green-200');
    } else {
        diffLabel.removeClass('text-green-600 text-slate-500').addClass('text-red-500');
        diffIndicator.html(`
            <span class="material-symbols-outlined text-red-400 text-sm mt-0.5">warning</span>
            <div>
                <p class="font-bold text-red-800">Unbalanced (${difference > 0 ? 'Surplus' : 'Deficit'})</p>
                <p class="text-red-600 mt-0.5">Adjust selection or ending balance. Difference is ${difference.toFixed(2)}.</p>
            </div>
        `).removeClass('bg-slate-50 text-slate-600 border-slate-200 bg-green-50 text-green-700 border-green-200').addClass('bg-red-50 border-red-200 text-red-700');
    }
}

// Reconcile selected submissions
$('#reconcileForm').submit(function(e) {
    e.preventDefault();
    const checked = $('.trx-checkbox:checked');
    if (checked.length === 0) {
        alert('Please check at least one transaction to reconcile.');
        return;
    }
    
    $.ajax({
        url: '<?= BASE_URL ?>/banking/reconciliation',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});

// Unreconcile selected submissions
$('#unreconcileForm').submit(function(e) {
    e.preventDefault();
    const checked = $('.reconciled-checkbox:checked');
    if (checked.length === 0) {
        alert('Please check at least one transaction to unreconcile.');
        return;
    }
    
    $.ajax({
        url: '<?= BASE_URL ?>/banking/reconciliation',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});
</script>
<?php endif; ?>
