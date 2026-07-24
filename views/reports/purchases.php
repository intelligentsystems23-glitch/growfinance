<?php 
$hide_title = true; 
$queryParams = $_GET;
unset($queryParams['url']);
$queryString = http_build_query($queryParams);
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Purchase Report</h1>
        <p class="text-sm text-slate-500 mt-1">Monitor purchase orders, vendor spending, and procurement</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/purchases/csv?<?= $queryString ?>" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">file_download</span> CSV</a>
        <a href="<?= BASE_URL ?? '' ?>/reports/export/purchases/pdf?<?= $queryString ?>" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> PDF</a>
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
            <label class="block text-xs font-medium text-slate-500 mb-1">Vendor</label>
            <select name="vendor_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Vendors</option>
                <?php foreach ($vendors as $vendor): ?>
                <option value="<?= $vendor['id'] ?>" <?= ($filters['vendor_id'] ?? '') == $vendor['id'] ? 'selected' : '' ?>><?= htmlspecialchars($vendor['company_name']) ?></option>
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
    <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total POs</p><h3 class="text-2xl font-bold"><?= number_format($summary['total_pos'] ?? 0) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Purchases</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_purchases'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Paid</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_paid'] ?? 0, 2) ?></h3></div>
    <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Outstanding</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_outstanding'] ?? 0, 2) ?></h3></div>
</div>

<!-- Purchases Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h5 class="font-bold text-slate-900">Purchase Details</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="purchasesTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">PO Number</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Vendor</th><th class="px-6 py-4 text-right">Total</th><th class="px-6 py-4 text-right">Paid</th><th class="px-6 py-4 text-right">Balance</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4 text-center">Payment</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($purchases)): ?>
                <tr><td colspan="8"><div class="flex flex-col items-center justify-center py-12 text-slate-400"><span class="material-symbols-outlined text-4xl mb-3 opacity-50">shopping_cart</span><p class="text-base font-medium text-slate-500">No purchase orders found</p></div></td></tr>
                <?php else: ?>
                <?php foreach ($purchases as $po): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4"><a href="purchase-orders/view/<?= $po['id'] ?>" class="font-medium text-primary hover:text-primary/80"><?= htmlspecialchars($po['po_number']) ?></a></td>
                    <td class="px-6 py-4 text-slate-600"><?= date('d/m/Y', strtotime($po['po_date'])) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($po['vendor_name']) ?></td>
                    <td class="px-6 py-4 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($po['total_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($po['total_paid'] ?? 0, 2) ?></td>
                    <td class="px-6 py-4 text-right <?= (($po['total_amount'] - ($po['total_paid'] ?? 0)) > 0) ? 'text-red-500' : 'text-green-600' ?>"><?= htmlspecialchars($currency) ?> <?= number_format($po['total_amount'] - ($po['total_paid'] ?? 0), 2) ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php $ps = $po['status'] == 'received' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-amber-50 text-amber-600 border-amber-200'; ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $ps ?>"><?= ucfirst($po['status']) ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $pct = ($po['total_paid'] ?? 0) >= $po['total_amount'] ? 'bg-green-50 text-green-600 border-green-200' : (($po['total_paid'] ?? 0) > 0 ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-red-50 text-red-600 border-red-200');
                        $pct_text = ($po['total_paid'] ?? 0) >= $po['total_amount'] ? 'Paid' : (($po['total_paid'] ?? 0) > 0 ? 'Partial' : 'Unpaid');
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $pct ?>"><?= $pct_text ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() { $('#purchasesTable').DataTable({ "pageLength": 25, "order": [[1, "desc"]] }); });
</script>