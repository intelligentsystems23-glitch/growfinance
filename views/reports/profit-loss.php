<?php 
$hide_title = true; 
$queryParams = $_GET;
unset($queryParams['url']);
$queryString = http_build_query($queryParams);
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Profit & Loss Statement</h1>
        <p class="text-sm text-slate-500 mt-1"><?= date('d M Y', strtotime($filters['date_from'])) ?> - <?= date('d M Y', strtotime($filters['date_to'])) ?></p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/profit-loss/pdf?<?= $queryString ?>" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> Export PDF</a>
        <button onclick="window.print()" class="flex items-center gap-2 bg-cyan-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-cyan-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">print</span> Print</button>
        <a href="<?= BASE_URL ?? '' ?>/reports" class="flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors"><span class="material-symbols-outlined text-sm">arrow_back</span> Back</a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" value="<?= htmlspecialchars($filters['date_from']) ?>">
        </div>
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" value="<?= htmlspecialchars($filters['date_to']) ?>">
        </div>
        <div class="md:col-span-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">Generate</button>
        </div>
    </form>
</div>

<!-- P&L Statement -->
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 text-center bg-slate-50/50">
            <h3 class="font-bold text-xl text-slate-900">Profit & Loss Statement</h3>
            <p class="text-sm text-slate-500 mt-1"><?= date('d M Y', strtotime($filters['date_from'])) ?> - <?= date('d M Y', strtotime($filters['date_to'])) ?></p>
        </div>
        <div class="p-6">
            <!-- Income Section -->
            <h5 class="font-bold text-green-600 flex items-center gap-2 mb-3"><span class="material-symbols-outlined">arrow_upward</span> Income</h5>
            <table class="w-full text-sm mb-4">
                <tr class="border-b border-slate-100"><td class="py-2 text-slate-700">Sales Revenue</td><td class="py-2 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($pl_data['total_income'], 2) ?></td></tr>
                <tr class="bg-slate-50 font-bold"><td class="py-2 px-3 text-slate-900 rounded-l-lg">Total Income</td><td class="py-2 px-3 text-right text-green-600 rounded-r-lg"><?= htmlspecialchars($currency) ?> <?= number_format($pl_data['total_income'], 2) ?></td></tr>
            </table>

            <!-- Expenses Section -->
            <h5 class="font-bold text-red-500 flex items-center gap-2 mb-3 mt-6"><span class="material-symbols-outlined">arrow_downward</span> Expenses</h5>
            <table class="w-full text-sm mb-4">
                <?php foreach ($pl_data['expenses'] as $expense): ?>
                <?php if ($expense['amount'] > 0): ?>
                <tr class="border-b border-slate-100"><td class="py-2 text-slate-700"><?= htmlspecialchars($expense['category_name']) ?></td><td class="py-2 text-right text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($expense['amount'], 2) ?></td></tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <tr class="bg-slate-50 font-bold"><td class="py-2 px-3 text-slate-900">Total Expenses</td><td class="py-2 px-3 text-right text-red-500"><?= htmlspecialchars($currency) ?> <?= number_format($pl_data['total_expenses'], 2) ?></td></tr>
            </table>

            <hr class="my-4 border-slate-200">

            <!-- Net Profit -->
            <table class="w-full text-sm border border-slate-200 rounded-xl overflow-hidden">
                <tr class="<?= $pl_data['net_profit'] >= 0 ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600' ?> text-white font-bold">
                    <td class="py-3 px-4">NET PROFIT (LOSS)</td>
                    <td class="py-3 px-4 text-right"><?= htmlspecialchars($currency) ?> <?= number_format($pl_data['net_profit'], 2) ?></td>
                </tr>
                <tr class="border-t border-slate-100"><td class="py-2 px-4 text-slate-600">Profit Margin</td><td class="py-2 px-4 text-right font-medium text-slate-900"><?= number_format($pl_data['profit_margin'], 2) ?>%</td></tr>
            </table>
        </div>
    </div>
</div>