<?php 
$hide_title = true; 
$queryParams = $_GET;
unset($queryParams['url']);
$queryString = http_build_query($queryParams);
?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Sales Report</h1>
        <p class="text-sm text-slate-500 mt-1">Analyze sales performance, customer purchases, and revenue trends</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/sales/csv?<?= $queryString ?>" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">file_download</span> CSV</a>
        <a href="<?= BASE_URL ?? '' ?>/reports/export/sales/pdf?<?= $queryString ?>" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> PDF</a>
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
            <label class="block text-xs font-medium text-slate-500 mb-1">Customer</label>
            <select name="customer_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Customers</option>
                <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['id'] ?>" <?= ($filters['customer_id'] ?? '') == $customer['id'] ? 'selected' : '' ?>><?= htmlspecialchars($customer['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All</option>
                <option value="paid" <?= ($filters['status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="sent" <?= ($filters['status'] ?? '') == 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="overdue" <?= ($filters['status'] ?? '') == 'overdue' ? 'selected' : '' ?>>Overdue</option>
            </select>
        </div>
        <div class="md:col-span-1">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">Filter</button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Invoices</p><h3 class="text-2xl font-bold"><?= number_format($summary['total_invoices'] ?? 0) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Sales</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_sales'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Paid</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_paid'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Outstanding</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_outstanding'] ?? 0, 2) ?></h3></div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <h5 class="font-bold text-slate-900 mb-4">Sales by Customer (Top 10)</h5>
        <canvas id="customerChart" style="height: 250px;"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <h5 class="font-bold text-slate-900 mb-4">Monthly Sales Trend</h5>
        <canvas id="trendChart" style="height: 250px;"></canvas>
    </div>
</div>

<!-- Sales Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <h5 class="font-bold text-slate-900">Sales Details</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="salesTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">Invoice #</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4 text-right">Subtotal</th><th class="px-6 py-4 text-right">Tax</th><th class="px-6 py-4 text-right">Discount</th><th class="px-6 py-4 text-right">Total</th><th class="px-6 py-4 text-right">Paid</th><th class="px-6 py-4 text-right">Balance</th><th class="px-6 py-4 text-center">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($sales)): ?>
                <tr><td colspan="10"><div class="flex flex-col items-center justify-center py-12 text-slate-400"><span class="material-symbols-outlined text-4xl mb-3 opacity-50">bar_chart</span><p class="text-base font-medium text-slate-500">No sales data found</p></div></td></tr>
                <?php else: ?>
                <?php foreach ($sales as $sale): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4"><a href="invoices/show/<?= $sale['id'] ?>" class="font-medium text-primary hover:text-primary/80"><?= htmlspecialchars($sale['invoice_number']) ?></a></td>
                    <td class="px-6 py-4 text-slate-600"><?= date('d/m/Y', strtotime($sale['invoice_date'])) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($sale['customer_name']) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($sale['subtotal'], 2) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($sale['tax_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($sale['discount_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($sale['total_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($sale['paid_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right <?= ($sale['balance'] ?? 0) > 0 ? 'text-red-500' : 'text-green-600' ?>"><?= htmlspecialchars($currency) ?> <?= number_format($sale['balance'] ?? 0, 2) ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php $status_colors = ['draft'=>'bg-slate-100 text-slate-600','sent'=>'bg-amber-50 text-amber-600','paid'=>'bg-green-50 text-green-600','overdue'=>'bg-red-50 text-red-600']; $sc = $status_colors[$sale['status']] ?? 'bg-slate-100 text-slate-600'; ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $sc ?>"><?= ucfirst($sale['status']) ?></span>
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
$(document).ready(function() { $('#salesTable').DataTable({ "pageLength": 25, "order": [[1, "desc"]] }); });

const customerCtx = document.getElementById('customerChart').getContext('2d');
const customerData = <?= json_encode($by_customer) ?>;
new Chart(customerCtx, {
    type: 'bar',
    data: {
        labels: customerData.slice(0, 10).map(c => (c.company_name || '').substring(0, 15)),
        datasets: [{ label: 'Total Sales ($)', data: customerData.slice(0, 10).map(c => c.total_sales), backgroundColor: '#2563eb' }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});

const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendData = <?= json_encode($monthly_trend) ?>;
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: trendData.map(t => t.month_name),
        datasets: [{ label: 'Monthly Sales ($)', data: trendData.map(t => t.total_sales), borderColor: '#059669', tension: 0.1, fill: true, backgroundColor: 'rgba(5, 150, 105, 0.1)' }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>