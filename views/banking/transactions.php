<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Transactions</h1>
        <p class="text-sm text-slate-500 mt-0.5">Transactions ledger for bank accounts</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/banking/createTransaction" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Record Transaction
        </a>
        <a href="<?= BASE_URL ?? '' ?>/banking" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back to Banking
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-4">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Bank Account</label>
            <div class="relative">
                <select name="account_id" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">All Accounts</option>
                    <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= ($filters['account_id'] ?? '') == $acc['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($acc['account_name']) ?> (<?= htmlspecialchars($acc['bank_name']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($filters['date_from']) ?>">
        </div>
        <div class="md:col-span-3">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($filters['date_to']) ?>">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left" id="transactionsTable">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Transaction #</th>
                    <th class="px-4 py-2">Account</th>
                    <th class="px-4 py-2 text-center">Type</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Payee/Payer</th>
                    <th class="px-4 py-2">Check #</th>
                    <th class="px-4 py-2 text-right">Amount</th>
                    <th class="px-4 py-2 text-right">Balance</th>
                    <th class="px-4 py-2 text-center">Reconciled</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($transactions as $trx): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-1.5 text-slate-600"><?= date('d/m/Y', strtotime($trx['transaction_date'])) ?></td>
                    <td class="px-4 py-1.5 font-semibold text-primary"><?= htmlspecialchars($trx['transaction_number']) ?></td>
                    <td class="px-4 py-1.5 text-slate-700"><?= htmlspecialchars($trx['account_name']) ?></td>
                    <td class="px-4 py-1.5 text-center">
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($trx['transaction_type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 text-slate-500 truncate max-w-[200px]" title="<?= htmlspecialchars($trx['description']) ?>">
                        <?= htmlspecialchars(strlen($trx['description']) > 30 ? substr($trx['description'], 0, 30) . '...' : $trx['description']) ?>
                    </td>
                    <td class="px-4 py-1.5 text-slate-700"><?= htmlspecialchars($trx['payee_payer'] ?? '-') ?></td>
                    <td class="px-4 py-1.5 text-slate-500 font-mono"><?= htmlspecialchars($trx['check_number'] ?? '-') ?></td>
                    <td class="px-4 py-1.5 text-right font-semibold <?= in_array($trx['transaction_type'], ['deposit', 'receipt', 'interest']) ? 'text-green-600' : 'text-red-500' ?>">
                        <?= in_array($trx['transaction_type'], ['deposit', 'receipt', 'interest']) ? '+' : '-' ?><?= htmlspecialchars($currency) ?> <?= number_format($trx['amount'], 2) ?>
                    </td>
                    <td class="px-4 py-1.5 text-right font-medium text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($trx['running_balance'], 2) ?></td>
                    <td class="px-4 py-1.5 text-center">
                        <?php if ($trx['reconciled']): ?>
                            <span class="material-symbols-outlined text-green-600 text-base" title="Reconciled">check_circle</span>
                        <?php else: ?>
                            <span class="material-symbols-outlined text-slate-300 text-base" title="Not reconciled">radio_button_unchecked</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center py-6 text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">receipt_long</span>
                            <p class="text-sm font-medium text-slate-500">No transactions found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>