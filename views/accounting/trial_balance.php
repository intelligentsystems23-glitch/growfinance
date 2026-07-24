<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Trial Balance</h1>
        <p class="text-sm text-slate-500 mt-0.5">Account ledger balance checking statement</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-primary/95 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">print</span>
            <span>Print Report</span>
        </button>
        <a href="<?= BASE_URL ?>/accounting" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-10">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">As Of Date</label>
            <input type="date" name="as_of_date" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($_GET['as_of_date'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">check_circle</span> Generate
            </button>
        </div>
    </form>
</div>

<!-- Trial Balance Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
        <h3 class="font-semibold text-slate-900 text-sm">Statement as of <?= date('d M Y', strtotime($as_of_date)) ?></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Account Name</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2 text-right">Debit</th>
                    <th class="px-4 py-2 text-right">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $total_debit = 0;
                $total_credit = 0;
                foreach ($trial_balance as $tb): 
                    $balance = $tb['opening_balance'] + $tb['total_debit'] - $tb['total_credit'];
                    if ($balance == 0) continue;
                    
                    if (in_array($tb['account_type'], ['asset', 'expense'])) {
                        $debit = $balance > 0 ? $balance : 0;
                        $credit = $balance < 0 ? abs($balance) : 0;
                    } else {
                        $debit = $balance < 0 ? abs($balance) : 0;
                        $credit = $balance > 0 ? $balance : 0;
                    }
                    $total_debit += $debit;
                    $total_credit += $credit;
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-2 font-mono text-slate-700"><?= htmlspecialchars($tb['account_code']) ?></td>
                    <td class="px-4 py-2 text-slate-900 font-medium"><?= htmlspecialchars($tb['account_name']) ?></td>
                    <td class="px-4 py-2">
                        <?php
                        $typeColors = [
                            'asset' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'liability' => 'bg-red-50 text-red-600 border-red-200',
                            'equity' => 'bg-purple-50 text-purple-600 border-purple-200',
                            'income' => 'bg-green-50 text-green-600 border-green-200',
                            'expense' => 'bg-amber-50 text-amber-600 border-amber-200'
                        ];
                        $color_class = $typeColors[$tb['account_type']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($tb['account_type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-right font-semibold text-slate-700"><?= $debit > 0 ? htmlspecialchars($currency) . ' ' . number_format($debit, 2) : '-' ?></td>
                    <td class="px-4 py-2 text-right font-semibold text-slate-700"><?= $credit > 0 ? htmlspecialchars($currency) . ' ' . number_format($credit, 2) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-slate-50/50 font-bold border-t border-slate-200 text-slate-900">
                <tr>
                    <td colspan="3" class="px-4 py-2.5 text-right uppercase tracking-wider">Total:</td>
                    <td class="px-4 py-2.5 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($total_debit, 2) ?></td>
                    <td class="px-4 py-2.5 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($total_credit, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>