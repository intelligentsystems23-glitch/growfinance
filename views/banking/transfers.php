<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Fund Transfers</h1>
        <p class="text-sm text-slate-500 mt-0.5">Track transfers between different bank accounts</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/banking/createTransfer" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
            <span class="material-symbols-outlined text-sm">sync_alt</span>
            New Transfer
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
        <div class="md:col-span-5">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($filters['date_from']) ?>">
        </div>
        <div class="md:col-span-5">
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

<!-- Transfers Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Transfer #</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">From Account</th>
                    <th class="px-4 py-2">To Account</th>
                    <th class="px-4 py-2 text-right">Amount</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($transfers as $transfer): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-1.5 font-semibold text-primary"><?= htmlspecialchars($transfer['transfer_number']) ?></td>
                    <td class="px-4 py-1.5 text-slate-600"><?= date('d/m/Y', strtotime($transfer['transfer_date'])) ?></td>
                    <td class="px-4 py-1.5 text-slate-700"><?= htmlspecialchars($transfer['from_account_name']) ?> <span class="text-[10px] text-slate-400 font-normal">(<?= htmlspecialchars($transfer['from_bank']) ?>)</span></td>
                    <td class="px-4 py-1.5 text-slate-700"><?= htmlspecialchars($transfer['to_account_name']) ?> <span class="text-[10px] text-slate-400 font-normal">(<?= htmlspecialchars($transfer['to_bank']) ?>)</span></td>
                    <td class="px-4 py-1.5 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($transfer['amount'], 2) ?></td>
                    <td class="px-4 py-1.5 text-slate-500 max-w-[200px] truncate" title="<?= htmlspecialchars($transfer['description']) ?>"><?= htmlspecialchars($transfer['description'] ?? '-') ?></td>
                    <td class="px-4 py-1.5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-green-50 text-green-600 border border-green-200">
                            <?= ucfirst($transfer['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($transfers)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center py-6 text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">sync_alt</span>
                            <p class="text-sm font-medium text-slate-500">No transfers found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>