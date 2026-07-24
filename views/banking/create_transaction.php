<!-- Title Section -->
<div class="max-w-3xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Record New Transaction' ?></h1>
            <p class="text-xs text-slate-500">Configure new transaction details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/banking/transactions" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-3xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="transactionForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Bank Account -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Bank Account *</label>
                <div class="relative">
                    <select name="bank_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="">Select Account</option>
                        <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>">
                            <?= htmlspecialchars($acc['account_name']) ?> (<?= htmlspecialchars($acc['bank_name']) ?>) - Balance: <?= htmlspecialchars($currency) ?> <?= number_format($acc['current_balance'], 2) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Transaction Date -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Transaction Date *</label>
                <input type="date" name="transaction_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Transaction Type -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Transaction Type *</label>
                <div class="relative">
                    <select name="transaction_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" id="transactionType" required>
                        <option value="">Select Type</option>
                        <option value="deposit">Deposit</option>
                        <option value="withdrawal">Withdrawal</option>
                        <option value="payment">Payment</option>
                        <option value="receipt">Receipt</option>
                        <option value="fee">Bank Fee</option>
                        <option value="interest">Interest Earned</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Amount -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Amount *</label>
                <input type="number" name="amount" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" step="0.01" min="0.01" required>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Payee/Payer -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Payee/Payer</label>
                <input type="text" name="payee_payer" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Who is this transaction with?">
            </div>
            <!-- Check Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Check Number</label>
                <input type="text" name="check_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="If paid by check">
            </div>
        </div>
        
        <!-- Description (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Description *</label>
            <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" required placeholder="Transaction description"></textarea>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Record Transaction</span>
            </button>
            <a href="<?= BASE_URL ?>/banking/transactions" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
$('#transactionForm').submit(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= BASE_URL ?>/banking/createTransaction',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                window.location.href = '<?= BASE_URL ?>/banking/transactions';
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});
</script>