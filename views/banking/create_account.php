<!-- Title Section -->
<div class="max-w-4xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Account' ?></h1>
            <p class="text-xs text-slate-500">Configure new banking account details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/banking/accounts" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="accountForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Account Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Name *</label>
                <input type="text" name="account_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="e.g., Main Checking">
            </div>
            <!-- Account Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Number *</label>
                <input type="text" name="account_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="Account number">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Bank Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Bank Name *</label>
                <input type="text" name="bank_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="Bank name">
            </div>
            <!-- Branch Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Branch Name</label>
                <input type="text" name="branch_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Branch location">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Account Type -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Type *</label>
                <div class="relative">
                    <select name="account_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="checking">Checking</option>
                        <option value="savings">Savings</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="cash">Cash</option>
                        <option value="other">Other</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Currency -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Currency</label>
                <div class="relative">
                    <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                        <option value="CAD">CAD - Canadian Dollar</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Opening Balance -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Opening Balance</label>
                <input type="number" name="opening_balance" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" step="0.01">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- As Of Date -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">As Of Date</label>
                <input type="date" name="as_of_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>">
            </div>
            <!-- Account Holder -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Holder</label>
                <input type="text" name="account_holder" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Name on account">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Routing Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Routing Number</label>
                <input type="text" name="routing_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Routing/ABA number">
            </div>
            <!-- SWIFT Code -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">SWIFT Code</label>
                <input type="text" name="swift_code" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="SWIFT/BIC code">
            </div>
            <!-- IBAN -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">IBAN</label>
                <input type="text" name="iban" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="IBAN">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Notes -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Notes</label>
                <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2" placeholder="Enter notes..."></textarea>
            </div>
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Save Account</span>
            </button>
            <a href="<?= BASE_URL ?>/banking/accounts" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('accountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">refresh</span> Saving Account...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/banking/createAccount', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof window.showToast === 'function') {
                window.showToast('Bank account created successfully!', 'success');
            }
            setTimeout(() => {
                window.location.href = '<?= BASE_URL ?>/banking/accounts';
            }, 600);
        } else {
            if (typeof window.showToast === 'function') {
                window.showToast(data.message || 'Error creating bank account', 'error');
            } else {
                alert('Error: ' + (data.message || 'Failed to create bank account'));
            }
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(err => {
        if (typeof window.showToast === 'function') {
            window.showToast('An error occurred while saving the account.', 'error');
        } else {
            alert('An error occurred while saving the account.');
        }
        console.error(err);
        btn.innerHTML = orig;
        btn.disabled = false;
    });
});
</script>