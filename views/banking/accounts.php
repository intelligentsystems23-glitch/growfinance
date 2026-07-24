<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Bank Accounts</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your organization's bank accounts and cash float</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/banking/createAccount" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Add New Account
        </a>
        <a href="<?= BASE_URL ?? '' ?>/banking" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back to Banking
        </a>
    </div>
</div>

<!-- Total Balance -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <!-- Total Balance Card -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Balance</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= htmlspecialchars($currency) ?> <?= number_format($total_balance ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">payments</span>
            </div>
        </div>
    </div>
</div>

<!-- Accounts List -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php foreach ($accounts as $account): ?>
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all group relative flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h5 class="font-bold text-slate-900 text-base"><?= htmlspecialchars($account['account_name']) ?></h5>
                    <p class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($account['bank_name']) ?></p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $account['is_active'] ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                    <?= $account['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            
            <div class="my-4">
                <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-0.5">Current Balance</span>
                <h3 class="text-2xl font-bold <?= $account['current_balance'] >= 0 ? 'text-green-600' : 'text-red-500' ?>">
                    <?= htmlspecialchars($currency) ?> <?= number_format($account['current_balance'], 2) ?>
                </h3>
            </div>
            
            <div class="space-y-1 text-xs text-slate-600 border-t border-slate-100 pt-3">
                <p class="flex justify-between"><span class="text-slate-400">Account #:</span> <span class="font-mono font-medium text-slate-800"><?= htmlspecialchars($account['account_number']) ?></span></p>
                <p class="flex justify-between"><span class="text-slate-400">Type:</span> <span class="font-medium text-slate-800"><?= ucfirst($account['account_type']) ?></span></p>
                <p class="flex justify-between"><span class="text-slate-400">Currency:</span> <span class="font-medium text-slate-800"><?= $account['currency'] ?></span></p>
                <?php if ($account['account_holder']): ?>
                <p class="flex justify-between"><span class="text-slate-400">Holder:</span> <span class="font-medium text-slate-800"><?= htmlspecialchars($account['account_holder']) ?></span></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="border-t border-slate-100 mt-4 pt-3 flex items-center justify-end gap-2">
            <a href="<?= BASE_URL ?>/banking/editAccount/<?= $account['id'] ?>" class="flex items-center gap-1 bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors shadow-sm" title="Edit">
                <span class="material-symbols-outlined text-xs">edit</span>
                <span>Edit</span>
            </a>
            <a href="<?= BASE_URL ?>/banking/transactions?account_id=<?= $account['id'] ?>" class="flex items-center gap-1 bg-blue-50 border border-blue-200 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-100/50 transition-colors" title="Transactions">
                <span class="material-symbols-outlined text-xs">list_alt</span>
                <span>Transactions</span>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($accounts)): ?>
    <div class="col-span-full">
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
            No bank accounts found. <a href="<?= BASE_URL ?>/banking/createAccount" class="font-bold underline">Add your first account</a>.
        </div>
    </div>
    <?php endif; ?>
</div>