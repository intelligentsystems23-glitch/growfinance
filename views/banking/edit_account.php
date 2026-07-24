<?php if (!$account): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Account not found</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-4xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">edit</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Edit Bank Account' ?></h1>
            <p class="text-xs text-slate-500">Modify your banking account details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/banking/accounts" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="accountForm" class="space-y-4">
        <input type="hidden" name="id" value="<?= $account['id'] ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Account Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Name *</label>
                <input type="text" name="account_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required value="<?= htmlspecialchars($account['account_name'] ?? '') ?>">
            </div>
            <!-- Account Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Number</label>
                <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-500 outline-none transition-all" value="<?= htmlspecialchars($account['account_number'] ?? '') ?>" readonly>
                <span class="text-[10px] text-slate-400">Account number cannot be changed</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Bank Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Bank Name *</label>
                <input type="text" name="bank_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required value="<?= htmlspecialchars($account['bank_name'] ?? '') ?>">
            </div>
            <!-- Branch Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Branch Name</label>
                <input type="text" name="branch_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($account['branch_name'] ?? '') ?>">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Account Type -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Type *</label>
                <div class="relative">
                    <select name="account_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="checking" <?= $account['account_type'] == 'checking' ? 'selected' : '' ?>>Checking</option>
                        <option value="savings" <?= $account['account_type'] == 'savings' ? 'selected' : '' ?>>Savings</option>
                        <option value="credit_card" <?= $account['account_type'] == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                        <option value="cash" <?= $account['account_type'] == 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="other" <?= $account['account_type'] == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Account Holder -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Holder</label>
                <input type="text" name="account_holder" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($account['account_holder'] ?? '') ?>">
            </div>
            <!-- Routing Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Routing Number</label>
                <input type="text" name="routing_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($account['routing_number'] ?? '') ?>">
            </div>
        </div>
        
        <!-- Notes -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Notes</label>
            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2"><?= htmlspecialchars($account['notes'] ?? '') ?></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1" <?= $account['is_active'] ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= !$account['is_active'] ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Current Balance -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Current Balance</label>
                <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-500 outline-none transition-all" value="<?= htmlspecialchars($currency) ?> <?= number_format($account['current_balance'], 2) ?>" readonly>
                <span class="text-[10px] text-slate-400">Balance is updated through transactions</span>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Update Account</span>
            </button>
            <a href="<?= BASE_URL ?>/banking/accounts" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
$('#accountForm').submit(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= BASE_URL ?>/banking/editAccount/<?= $account['id'] ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                window.location.href = '<?= BASE_URL ?>/banking/accounts';
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});
</script>