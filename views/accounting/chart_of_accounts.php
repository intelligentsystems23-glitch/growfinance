<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Chart of Accounts</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your general ledger accounts</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>/accounting/createAccount" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            New Account
        </a>
        <a href="<?= BASE_URL ?>/accounting" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-6">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Search Accounts</label>
            <input type="text" name="search" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search by code or name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="md:col-span-4">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Account Type</label>
            <div class="relative">
                <select name="account_type" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">All Types</option>
                    <option value="asset" <?= ($_GET['account_type'] ?? '') == 'asset' ? 'selected' : '' ?>>Assets</option>
                    <option value="liability" <?= ($_GET['account_type'] ?? '') == 'liability' ? 'selected' : '' ?>>Liabilities</option>
                    <option value="equity" <?= ($_GET['account_type'] ?? '') == 'equity' ? 'selected' : '' ?>>Equity</option>
                    <option value="income" <?= ($_GET['account_type'] ?? '') == 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= ($_GET['account_type'] ?? '') == 'expense' ? 'selected' : '' ?>>Expenses</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
        </div>
    </form>
</div>

<!-- Accounts Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Account Name</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Parent Account</th>
                    <th class="px-4 py-2 text-right">Current Balance</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($accounts as $account): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-2 font-mono font-semibold text-slate-700"><?= htmlspecialchars($account['account_code']) ?></td>
                    <td class="px-4 py-2 text-slate-900 font-medium"><?= htmlspecialchars($account['account_name']) ?></td>
                    <td class="px-4 py-2">
                        <?php
                        $typeColors = [
                            'asset' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'liability' => 'bg-red-50 text-red-600 border-red-200',
                            'equity' => 'bg-purple-50 text-purple-600 border-purple-200',
                            'income' => 'bg-green-50 text-green-600 border-green-200',
                            'expense' => 'bg-amber-50 text-amber-600 border-amber-200'
                        ];
                        $color_class = $typeColors[$account['account_type']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($account['account_type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-slate-500"><?= htmlspecialchars($account['parent_name'] ?? '-') ?></td>
                    <td class="px-4 py-2 text-right font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($account['current_balance'], 2) ?></td>
                    <td class="px-4 py-2 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $account['is_active'] ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                            <?= $account['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="<?= BASE_URL ?>/accounting/editAccount/<?= $account['id'] ?>" class="inline-flex items-center justify-center p-1 bg-white border border-slate-200 text-slate-600 rounded-md hover:bg-slate-50 transition-colors shadow-sm" title="Edit">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($accounts)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center py-6">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">account_tree</span>
                            <p class="text-sm font-medium">No accounts found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>