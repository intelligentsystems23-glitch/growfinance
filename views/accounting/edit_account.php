<?php if (!$account): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Account not found</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">edit</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Edit Account' ?></h1>
            <p class="text-xs text-slate-500">Configure ledger account parameters.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/accounting/chartOfAccounts" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="accountForm" class="space-y-4">
        <input type="hidden" name="id" value="<?= $account['id'] ?>">
        
        <!-- Account Code -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Account Code</label>
            <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-500 outline-none transition-all" value="<?= htmlspecialchars($account['account_code'] ?? '') ?>" readonly>
            <span class="text-[10px] text-slate-400">Account code cannot be changed</span>
        </div>
        
        <!-- Account Name -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Account Name *</label>
            <input type="text" name="account_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required value="<?= htmlspecialchars($account['account_name'] ?? '') ?>">
        </div>
        
        <!-- Account Type -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Account Type *</label>
            <div class="relative">
                <select name="account_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                    <option value="asset" <?= $account['account_type'] == 'asset' ? 'selected' : '' ?>>Asset</option>
                    <option value="liability" <?= $account['account_type'] == 'liability' ? 'selected' : '' ?>>Liability</option>
                    <option value="equity" <?= $account['account_type'] == 'equity' ? 'selected' : '' ?>>Equity</option>
                    <option value="income" <?= $account['account_type'] == 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= $account['account_type'] == 'expense' ? 'selected' : '' ?>>Expense</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        
        <!-- Parent Account -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Parent Account</label>
            <div class="relative">
                <select name="parent_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                    <option value="">None (Top Level)</option>
                    <?php foreach ($parent_accounts as $acc): ?>
                    <?php if ($acc['id'] != $account['id']): ?>
                    <option value="<?= $acc['id'] ?>" <?= $account['parent_id'] == $acc['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($acc['account_code'] ?? '') ?> - <?= htmlspecialchars($acc['account_name'] ?? '') ?>
                    </option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        
        <!-- Description -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Description</label>
            <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2"><?= htmlspecialchars($account['description'] ?? '') ?></textarea>
        </div>
        
        <!-- Status -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
            <div class="relative">
                <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" <?= $account['is_system'] ? 'disabled' : '' ?>>
                    <option value="1" <?= $account['is_active'] ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= !$account['is_active'] ? 'selected' : '' ?>>Inactive</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
            <?php if ($account['is_system']): ?>
            <span class="text-[10px] text-slate-400">System accounts cannot be deactivated</span>
            <?php endif; ?>
        </div>
        
        <!-- Current Balance -->
        <div class="p-3 bg-blue-50 text-blue-800 rounded-md text-xs font-medium">
            <strong>Current Balance:</strong> <?= htmlspecialchars($currency) ?> <?= number_format($account['current_balance'], 2) ?><br>
            <span class="text-blue-600">Balance is updated through journal entries</span>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Update Account</span>
            </button>
            <a href="<?= BASE_URL ?>/accounting/chartOfAccounts" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
$('#accountForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '<?= BASE_URL ?>/accounting/editAccount/<?= $account['id'] ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) window.location.href = '<?= BASE_URL ?>/accounting/chartOfAccounts';
            else alert('Error: ' + response.message);
        }
    });
});
</script>