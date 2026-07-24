<?php
$total_assets = 0;
$total_liabilities = 0;
$total_equity = 0;

// Calculate balances
if (!function_exists('getAccountBalance')) {
    function getAccountBalance($account, $tb_indexed) {
        if (isset($tb_indexed[$account['account_code']])) {
            $tb = $tb_indexed[$account['account_code']];
            $balance = $tb['opening_balance'] + $tb['total_debit'] - $tb['total_credit'];
            
            if (in_array($account['account_type'], ['asset', 'expense'])) {
                return $balance;
            } else {
                return -$balance;
            }
        }
        return 0;
    }
}
?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Balance Sheet</h1>
        <p class="text-sm text-slate-500 mt-0.5">Assets, liabilities, and equity financial statement</p>
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

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-6">
    <div class="text-center border-b border-slate-100 pb-4 mb-6">
        <h2 class="text-xl font-bold text-slate-900">Balance Sheet</h2>
        <p class="text-xs text-slate-500 mt-1">As of <?= date('d M Y', strtotime($as_of_date)) ?></p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Left Side: Assets -->
        <div>
            <h3 class="font-bold text-blue-600 text-sm uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex justify-between">
                <span>Assets</span>
                <span class="material-symbols-outlined text-base">account_balance_wallet</span>
            </h3>
            
            <div class="space-y-1 text-xs">
                <?php foreach ($data['assets'] as $asset): 
                    $balance = getAccountBalance($asset, $data['trial_balance']);
                    if ($balance == 0) continue;
                    $total_assets += $balance;
                ?>
                <div class="flex justify-between py-1.5 border-b border-slate-50 text-slate-700 hover:bg-slate-50 px-1 rounded transition-colors">
                    <span><?= htmlspecialchars($asset['account_name']) ?></span>
                    <span class="font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($balance, 2) ?></span>
                </div>
                <?php endforeach; ?>
                
                <?php if ($total_assets == 0): ?>
                <div class="text-center text-slate-400 py-4">No assets reported.</div>
                <?php endif; ?>
            </div>
            
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex justify-between items-center text-xs font-bold text-blue-800">
                <span>Total Assets</span>
                <span><?= htmlspecialchars($currency) ?> <?= number_format($total_assets, 2) ?></span>
            </div>
        </div>
        
        <!-- Right Side: Liabilities & Equity -->
        <div class="space-y-6">
            <!-- Liabilities -->
            <div>
                <h3 class="font-bold text-red-600 text-sm uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex justify-between">
                    <span>Liabilities</span>
                    <span class="material-symbols-outlined text-base">trending_down</span>
                </h3>
                
                <div class="space-y-1 text-xs">
                    <?php foreach ($data['liabilities'] as $liability): 
                        $balance = getAccountBalance($liability, $data['trial_balance']);
                        if ($balance == 0) continue;
                        $total_liabilities += abs($balance);
                    ?>
                    <div class="flex justify-between py-1.5 border-b border-slate-50 text-slate-700 hover:bg-slate-50 px-1 rounded transition-colors">
                        <span><?= htmlspecialchars($liability['account_name']) ?></span>
                        <span class="font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format(abs($balance), 2) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if ($total_liabilities == 0): ?>
                    <div class="text-center text-slate-400 py-4">No liabilities reported.</div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg flex justify-between items-center text-xs font-bold text-red-800">
                    <span>Total Liabilities</span>
                    <span><?= htmlspecialchars($currency) ?> <?= number_format($total_liabilities, 2) ?></span>
                </div>
            </div>
            
            <!-- Equity -->
            <div>
                <h3 class="font-bold text-green-600 text-sm uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex justify-between">
                    <span>Equity</span>
                    <span class="material-symbols-outlined text-base">account_balance</span>
                </h3>
                
                <div class="space-y-1 text-xs">
                    <?php foreach ($data['equity'] as $equity): 
                        $balance = getAccountBalance($equity, $data['trial_balance']);
                        if ($balance == 0) continue;
                        $total_equity += abs($balance);
                    ?>
                    <div class="flex justify-between py-1.5 border-b border-slate-50 text-slate-700 hover:bg-slate-50 px-1 rounded transition-colors">
                        <span><?= htmlspecialchars($equity['account_name']) ?></span>
                        <span class="font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format(abs($balance), 2) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Net Income (from P&L) -->
                    <?php
                    $net_income = 0;
                    $trial_balance = $data['trial_balance'];
                    foreach ($trial_balance as $tb) {
                        if (in_array($tb['account_type'], ['income', 'expense'])) {
                            $balance = $tb['opening_balance'] + $tb['total_debit'] - $tb['total_credit'];
                            if ($tb['account_type'] == 'income') {
                                $net_income += $balance;
                            } else {
                                $net_income -= $balance;
                            }
                        }
                    }
                    if ($net_income != 0):
                        $total_equity += $net_income;
                    ?>
                    <div class="flex justify-between py-1.5 border-b border-slate-50 text-slate-700 font-semibold px-1 rounded">
                        <span>Retained Earnings (Net Income)</span>
                        <span class="text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($net_income, 2) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center text-xs font-bold text-green-800">
                    <span>Total Equity</span>
                    <span><?= htmlspecialchars($currency) ?> <?= number_format($total_equity, 2) ?></span>
                </div>
            </div>
            
            <div class="border-t border-slate-200 pt-4 mt-6">
                <div class="p-3.5 bg-slate-900 rounded-xl flex justify-between items-center text-xs font-bold text-white shadow-sm">
                    <span>Total Liabilities & Equity</span>
                    <span><?= htmlspecialchars($currency) ?> <?= number_format($total_liabilities + $total_equity, 2) ?></span>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Balance Check -->
    <?php
    $is_balanced = abs($total_assets - ($total_liabilities + $total_equity)) < 0.01;
    $diff = $total_assets - ($total_liabilities + $total_equity);
    ?>
    <div class="mt-6 p-4 rounded-xl border <?= $is_balanced ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' ?> flex items-start gap-2.5 text-xs">
        <span class="material-symbols-outlined text-sm mt-0.5"><?= $is_balanced ? 'check_circle' : 'warning' ?></span>
        <div>
            <p class="font-bold">Balance Check: <?= $is_balanced ? 'Balanced' : 'Unbalanced' ?></p>
            <p class="mt-0.5 opacity-90">
                Assets (<?= htmlspecialchars($currency) ?> <?= number_format($total_assets, 2) ?>) 
                <?= $is_balanced ? '=' : '≠' ?> 
                Liabilities + Equity (<?= htmlspecialchars($currency) ?> <?= number_format($total_liabilities + $total_equity, 2) ?>)
                <?php if (!$is_balanced): ?>
                <span class="block mt-1 font-bold text-red-700">Discrepancy Difference: <?= htmlspecialchars($currency) ?> <?= number_format($diff, 2) ?></span>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>