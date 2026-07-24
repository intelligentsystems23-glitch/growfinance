<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Banking</h1>
        <p class="text-sm text-slate-500 mt-1">Manage accounts, transactions, and transfers</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?? '' ?>/banking/accounts" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2.5 rounded-xl font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors">
            <span class="material-symbols-outlined text-sm">account_balance</span>
            Manage Accounts
        </a>
        <a href="<?= BASE_URL ?? '' ?>/banking/createTransfer" class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2.5 rounded-xl font-medium border border-indigo-200 hover:bg-indigo-100 transition-colors">
            <span class="material-symbols-outlined text-sm">sync_alt</span>
            Transfer Funds
        </a>
        <a href="<?= BASE_URL ?? '' ?>/banking/createTransaction" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            Record Transaction
        </a>
    </div>
</div>

<!-- Accounts Summary Grid (Total Balance & Accounts) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Bank Balance -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-5 shadow-lg shadow-green-500/20 text-white flex flex-col justify-between min-h-[140px]">
        <div>
            <p class="text-green-100 text-[10px] uppercase font-bold tracking-wider mb-1">Total Bank Balance</p>
            <h3 class="text-2xl font-bold tracking-tight"><?= htmlspecialchars($currency) ?> <?= number_format($total_balance ?? 0, 2) ?></h3>
        </div>
        <div class="flex justify-between items-center mt-4">
            <span class="text-xs text-green-100 font-medium">Consolidated Float</span>
            <span class="material-symbols-outlined text-base opacity-80">payments</span>
        </div>
    </div>

    <!-- Individual Accounts -->
    <?php foreach ($accounts ?? [] as $account): ?>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group flex flex-col justify-between min-h-[140px]">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="font-bold text-slate-900 line-clamp-1"><?= htmlspecialchars($account['account_name']) ?></h6>
                <p class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($account['bank_name']) ?></p>
            </div>
            <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-sm">account_balance</span>
            </div>
        </div>
        <div class="mt-4">
            <h4 class="text-xl font-bold <?= $account['current_balance'] >= 0 ? 'text-green-600' : 'text-red-500' ?>">
                <?= htmlspecialchars($currency) ?> <?= number_format($account['current_balance'], 2) ?>
            </h4>
            <p class="text-[10px] text-slate-400 mt-0.5 font-mono"><?= htmlspecialchars($account['account_number']) ?></p>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($accounts)): ?>
    <div class="col-span-3 bg-white p-5 rounded-2xl border border-dashed border-slate-300 text-center text-slate-500 flex flex-col items-center justify-center min-h-[140px]">
        <span class="material-symbols-outlined text-3xl mb-1 opacity-50">account_balance</span>
        <p class="text-sm font-medium">No bank accounts created yet</p>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="<?= BASE_URL ?? '' ?>/banking/transactions" class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex flex-col items-center justify-center text-slate-600 hover:bg-white hover:border-slate-200 hover:shadow-sm hover:text-primary transition-all group">
        <span class="material-symbols-outlined text-3xl mb-2 group-hover:scale-110 transition-transform">receipt_long</span>
        <h6 class="font-semibold text-sm">All Transactions</h6>
    </a>
    <a href="<?= BASE_URL ?? '' ?>/banking/transfers" class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex flex-col items-center justify-center text-slate-600 hover:bg-white hover:border-slate-200 hover:shadow-sm hover:text-indigo-600 transition-all group">
        <span class="material-symbols-outlined text-3xl mb-2 group-hover:scale-110 transition-transform">sync_alt</span>
        <h6 class="font-semibold text-sm">Fund Transfers</h6>
    </a>
    <a href="<?= BASE_URL ?? '' ?>/banking/reconciliation" class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex flex-col items-center justify-center text-slate-600 hover:bg-white hover:border-slate-200 hover:shadow-sm hover:text-amber-600 transition-all group">
        <span class="material-symbols-outlined text-3xl mb-2 group-hover:scale-110 transition-transform">balance</span>
        <h6 class="font-semibold text-sm">Reconciliation</h6>
    </a>
    <a href="<?= BASE_URL ?? '' ?>/reports" class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex flex-col items-center justify-center text-slate-600 hover:bg-white hover:border-slate-200 hover:shadow-sm hover:text-blue-600 transition-all group">
        <span class="material-symbols-outlined text-3xl mb-2 group-hover:scale-110 transition-transform">bar_chart</span>
        <h6 class="font-semibold text-sm">Reports</h6>
    </a>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="border-b border-slate-100 px-6 py-4 flex justify-between items-center bg-slate-50/50">
        <h5 class="font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-400">history</span>
            Recent Transactions
        </h5>
        <a href="<?= BASE_URL ?? '' ?>/banking/transactions" class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Account</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Payee/Payer</th>
                    <th class="px-6 py-3 text-right">Amount</th>
                    <th class="px-6 py-3 text-right">Balance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="7">
                        <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">receipt_long</span>
                            <p class="text-sm font-medium">No recent transactions</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($transactions as $trx): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-3 text-slate-600"><?= date('d/m/Y', strtotime($trx['transaction_date'])) ?></td>
                    <td class="px-6 py-3 font-medium text-slate-900"><?= htmlspecialchars($trx['account_name']) ?></td>
                    <td class="px-6 py-3">
                        <?php if (in_array($trx['transaction_type'], ['deposit', 'receipt'])): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200"><?= ucfirst($trx['transaction_type']) ?></span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200"><?= ucfirst($trx['transaction_type']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3 text-slate-500 truncate max-w-[200px]" title="<?= htmlspecialchars($trx['description']) ?>">
                        <?= htmlspecialchars(strlen($trx['description']) > 30 ? substr($trx['description'], 0, 30) . '...' : $trx['description']) ?>
                    </td>
                    <td class="px-6 py-3 text-slate-700"><?= htmlspecialchars($trx['payee_payer'] ?? '-') ?></td>
                    <td class="px-6 py-3 text-right font-medium <?= in_array($trx['transaction_type'], ['deposit', 'receipt']) ? 'text-green-600' : 'text-slate-900' ?>">
                        <?= in_array($trx['transaction_type'], ['deposit', 'receipt']) ? '+' : '-' ?><?= htmlspecialchars($currency) ?> <?= number_format($trx['amount'], 2) ?>
                    </td>
                    <td class="px-6 py-3 text-right font-medium text-slate-500"><?= htmlspecialchars($currency) ?> <?= number_format($trx['running_balance'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>