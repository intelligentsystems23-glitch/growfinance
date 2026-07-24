<?php 
$hide_title = true; 
$queryParams = $_GET;
unset($queryParams['url']);
$queryString = http_build_query($queryParams);
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Expense Report</h1>
        <p class="text-sm text-slate-500 mt-1">Track expenses by category, vendor, and time period</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/expenses/csv?<?= $queryString ?>" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">file_download</span> CSV</a>
        <a href="<?= BASE_URL ?? '' ?>/reports/export/expenses/pdf?<?= $queryString ?>" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> PDF</a>
        <a href="<?= BASE_URL ?? '' ?>/reports" class="flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors"><span class="material-symbols-outlined text-sm">arrow_back</span> Back</a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" value="<?= htmlspecialchars($filters['date_from']) ?>">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" value="<?= htmlspecialchars($filters['date_to']) ?>">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Category</label>
            <select name="category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">Filter</button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Expenses</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_amount'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Count</p><h3 class="text-2xl font-bold"><?= number_format($summary['total_expenses'] ?? 0) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Average Expense</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['average_amount'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Largest Expense</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['max_amount'] ?? 0, 2) ?></h3></div>
</div>

<!-- Chart -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <h5 class="font-bold text-slate-900 mb-4">Expenses by Category</h5>
    <canvas id="categoryChart" style="height: 300px;"></canvas>
</div>

<!-- Expenses Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h5 class="font-bold text-slate-900">Expense Details</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="expensesTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">Expense #</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Category</th><th class="px-6 py-4">Vendor</th><th class="px-6 py-4">Description</th><th class="px-6 py-4 text-right">Amount</th><th class="px-6 py-4">Payment Method</th><th class="px-6 py-4 text-center">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($expenses)): ?>
                <tr><td colspan="8"><div class="flex flex-col items-center justify-center py-12 text-slate-400"><span class="material-symbols-outlined text-4xl mb-3 opacity-50">receipt_long</span><p class="text-base font-medium text-slate-500">No expenses found</p></div></td></tr>
                <?php else: ?>
                <?php foreach ($expenses as $expense): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($expense['expense_number']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= date('d/m/Y', strtotime($expense['expense_date'])) ?></td>
                    <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600"><?= htmlspecialchars($expense['category_name'] ?? 'Uncategorized') ?></span></td>
                    <td class="px-6 py-4 text-slate-700"><?= htmlspecialchars($expense['vendor_name'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-slate-500 truncate max-w-[200px]" title="<?= htmlspecialchars($expense['description']) ?>"><?= htmlspecialchars(substr($expense['description'], 0, 40)) ?>...</td>
                    <td class="px-6 py-4 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($expense['amount'], 2) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($expense['payment_method'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php $ps = $expense['payment_status'] == 'paid' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-amber-50 text-amber-600 border-amber-200'; ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $ps ?>"><?= ucfirst($expense['payment_status']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() { $('#expensesTable').DataTable({ "pageLength": 25, "order": [[1, "desc"]] }); });

const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = <?= json_encode($by_category) ?>.filter(c => c.total_amount > 0);
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryData.map(c => c.category_name),
        datasets: [{ data: categoryData.map(c => c.total_amount), backgroundColor: ['#ef4444','#3b82f6','#10b981','#f59e0b','#8b5cf6','#6b7280','#6366f1','#14b8a6','#ec4899','#f97316'] }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
});
</script>