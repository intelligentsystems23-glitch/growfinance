<?php 
$hide_title = true; 
$queryParams = $_GET;
unset($queryParams['url']);
$queryString = http_build_query($queryParams);

$total_tax = array_sum(array_column($tax_data, 'total_tax'));
$total_sales = array_sum(array_column($tax_data, 'total_sales'));
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Tax Report</h1>
        <p class="text-sm text-slate-500 mt-1">Summarize tax collected on sales transactions</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/tax/pdf?<?= $queryString ?>" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> Export PDF</a>
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
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">Filter</button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="p-5 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Tax Collected</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($total_tax, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Sales (Taxable)</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($total_sales, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Average Tax Rate</p><h3 class="text-2xl font-bold"><?= $total_sales > 0 ? number_format(($total_tax / $total_sales) * 100, 2) : 0 ?>%</h3></div>
</div>

<!-- Monthly Tax Breakdown -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h5 class="font-bold text-slate-900">Monthly Tax Summary</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="taxTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">Month</th><th class="px-6 py-4 text-right">Invoices</th><th class="px-6 py-4 text-right">Sales (Excl. Tax)</th><th class="px-6 py-4 text-right">Tax Amount</th><th class="px-6 py-4 text-right">Total (Incl. Tax)</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($tax_data as $tax): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($tax['month_name']) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= number_format($tax['invoice_count']) ?></td>
                    <td class="px-6 py-4 text-right text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($tax['total_sales'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-amber-600"><?= htmlspecialchars($currency) ?> <?= number_format($tax['total_tax'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($tax['total_with_tax'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-slate-50 font-bold text-slate-900 border-t-2 border-slate-200">
                <tr>
                    <td class="px-6 py-4">Total</td>
                    <td class="px-6 py-4 text-right"><?= number_format(array_sum(array_column($tax_data, 'invoice_count'))) ?></td>
                    <td class="px-6 py-4 text-right"><?= htmlspecialchars($currency) ?> <?= number_format($total_sales, 2) ?></td>
                    <td class="px-6 py-4 text-right text-amber-600"><?= htmlspecialchars($currency) ?> <?= number_format($total_tax, 2) ?></td>
                    <td class="px-6 py-4 text-right"><?= htmlspecialchars($currency) ?> <?= number_format(array_sum(array_column($tax_data, 'total_with_tax')), 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>$(document).ready(function() { $('#taxTable').DataTable({ "pageLength": 25, "order": [[0, "desc"]], "info": false, "paging": false, "searching": false }); });</script>