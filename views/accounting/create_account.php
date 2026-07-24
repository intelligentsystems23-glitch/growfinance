<!-- Title Section -->
<div class="max-w-2xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Account' ?></h1>
            <p class="text-xs text-slate-500">Configure new ledger account details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/accounting/chartOfAccounts" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="accountForm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Account Code -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Account Code *</label>
                <input type="text" name="account_code" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="e.g. 1010" required>
            </div>
            
            <!-- Account Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Account Name *</label>
                <input type="text" name="account_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="e.g. Petty Cash" required>
            </div>
            
            <!-- Account Type -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Account Type *</label>
                <div class="relative">
                    <select name="account_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="asset">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            
            <!-- Parent Account -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Parent Account</label>
                <div class="relative">
                    <select name="parent_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="">None (Top Level)</option>
                        <?php foreach ($parent_accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>">
                            <?= htmlspecialchars($acc['account_code']) ?> - <?= htmlspecialchars($acc['account_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            
            <!-- Opening Balance -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Opening Balance</label>
                <input type="number" name="opening_balance" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0.00">
            </div>
            
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            
            <!-- Description (Full Width) -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600">Description</label>
                <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter account description..."></textarea>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Save Account</span>
            </button>
            <a href="<?= BASE_URL ?>/accounting/chartOfAccounts" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
$('#accountForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '<?= BASE_URL ?>/accounting/createAccount',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) window.location.href = '<?= BASE_URL ?>/accounting/chartOfAccounts';
            else alert('Error: ' + response.message);
        }
    });
});
</script>