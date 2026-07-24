<?php
$total_income = 0;
$total_expenses = 0;
$income_accounts = [];
$expense_accounts = [];

foreach ($data as $account) {
    if ($account['income_amount'] > 0) {
        $income_accounts[] = $account;
        $total_income += $account['income_amount'];
    }
    if ($account['expense_amount'] > 0) {
        $expense_accounts[] = $account;
        $total_expenses += $account['expense_amount'];
    }
}

$net_profit = $total_income - $total_expenses;
?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Profit & Loss Statement</h1>
        <p class="text-sm text-slate-500 mt-0.5">Revenue and expense summary statement</p>
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
        <div class="md:col-span-5">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($_GET['date_from'] ?? date('Y-01-01')) ?>">
        </div>
        <div class="md:col-span-5">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($_GET['date_to'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">check_circle</span> Generate
            </button>
        </div>
    </form>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-6">
    <div class="text-center border-b border-slate-100 pb-4 mb-6">
        <h2 class="text-xl font-bold text-slate-900">Profit & Loss Statement</h2>
        <p class="text-xs text-slate-500 mt-1"><?= date('d M Y', strtotime($date_from)) ?> - <?= date('d M Y', strtotime($date_to)) ?></p>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="border border-slate-100 rounded-xl p-4 text-center bg-slate-50/50">
            <h6 class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Total Income</h6>
            <h4 class="text-xl font-bold text-green-600 mt-1"><?= htmlspecialchars($currency) ?> <?= number_format($total_income, 2) ?></h4>
        </div>
        <div class="border border-slate-100 rounded-xl p-4 text-center bg-slate-50/50">
            <h6 class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Total Expenses</h6>
            <h4 class="text-xl font-bold text-red-500 mt-1"><?= htmlspecialchars($currency) ?> <?= number_format($total_expenses, 2) ?></h4>
        </div>
        <div class="border border-slate-100 rounded-xl p-4 text-center bg-slate-50/50">
            <h6 class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Net <?= $net_profit >= 0 ? 'Profit' : 'Loss' ?></h6>
            <h4 class="text-xl font-bold <?= $net_profit >= 0 ? 'text-green-600' : 'text-red-500' ?> mt-1"><?= htmlspecialchars($currency) ?> <?= number_format(abs($net_profit), 2) ?></h4>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Details Table -->
        <div>
            <!-- Income Section -->
            <div class="mb-6">
                <h3 class="font-bold text-green-600 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 mb-2 flex justify-between">
                    <span>Income</span>
                    <span class="material-symbols-outlined text-base">trending_up</span>
                </h3>
                <table class="w-full text-xs">
                    <tbody>
                        <?php if (empty($income_accounts)): ?>
                        <tr><td colspan="2" class="text-slate-400 py-2">No income recorded for this period</td></tr>
                        <?php else: ?>
                        <?php foreach ($income_accounts as $income): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-2 text-slate-700"><?= htmlspecialchars($income['account_name']) ?></td>
                            <td class="py-2 text-right font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($income['income_amount'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="font-bold text-slate-800 border-t border-slate-200">
                        <tr>
                            <td class="py-2">Total Income</td>
                            <td class="py-2 text-right text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($total_income, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Expenses Section -->
            <div>
                <h3 class="font-bold text-red-600 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 mb-2 flex justify-between">
                    <span>Expenses</span>
                    <span class="material-symbols-outlined text-base">trending_down</span>
                </h3>
                <table class="w-full text-xs">
                    <tbody>
                        <?php if (empty($expense_accounts)): ?>
                        <tr><td colspan="2" class="text-slate-400 py-2">No expenses recorded for this period</td></tr>
                        <?php else: ?>
                        <?php foreach ($expense_accounts as $expense): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-2 text-slate-700"><?= htmlspecialchars($expense['account_name']) ?></td>
                            <td class="py-2 text-right font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($expense['expense_amount'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="font-bold text-slate-800 border-t border-slate-200">
                        <tr>
                            <td class="py-2">Total Expenses</td>
                            <td class="py-2 text-right text-red-500"><?= htmlspecialchars($currency) ?> <?= number_format($total_expenses, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="mt-6 border-t border-slate-200 pt-4">
                <div class="p-3.5 rounded-xl flex justify-between items-center text-xs font-bold text-white <?= $net_profit >= 0 ? 'bg-green-600' : 'bg-red-500' ?>">
                    <span>NET <?= $net_profit >= 0 ? 'PROFIT' : 'LOSS' ?></span>
                    <span><?= htmlspecialchars($currency) ?> <?= number_format(abs($net_profit), 2) ?></span>
                </div>
                <?php if ($total_income > 0): ?>
                <div class="flex justify-between text-[11px] text-slate-500 font-semibold mt-2 px-1">
                    <span>Profit Margin:</span>
                    <span><?= number_format(($net_profit / $total_income) * 100, 2) ?>%</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Expense Chart breakdown -->
        <div>
            <?php if (!empty($expense_accounts)): ?>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 h-full flex flex-col justify-between">
                <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider mb-4">Expense Breakdown</h4>
                <div class="relative h-[250px] w-full flex items-center justify-center">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
            <?php else: ?>
            <div class="border border-dashed border-slate-200 rounded-xl p-8 text-center text-slate-400 h-full flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-3xl mb-2 opacity-50">pie_chart</span>
                <p class="text-sm font-medium">No expenses to display chart</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($expense_accounts)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('expenseChart').getContext('2d');
const expenseData = <?= json_encode($expense_accounts) ?>;

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: expenseData.map(e => e.account_name),
        datasets: [{
            data: expenseData.map(e => e.expense_amount),
            backgroundColor: ['#e74a3b', '#4e73df', '#1cc88a', '#f6c23e', '#858796', '#5a5c69', '#2e59d9', '#17a673', '#e83e8c', '#fd7e14']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percent = ((value / total) * 100).toFixed(1);
                        return context.label + ': ' + SYSTEM_CURRENCY + ' ' + value.toFixed(2) + ' (' + percent + '%)';
                    }
                }
            }
        }
    }
});
</script>
<?php endif; ?>