<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Inventory Report</h1>
        <p class="text-sm text-slate-500 mt-1">Stock levels, valuation, and movement analysis</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/inventory/csv" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">file_download</span> CSV</a>
        <a href="<?= BASE_URL ?? '' ?>/reports/export/inventory/pdf" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> PDF</a>
        <a href="<?= BASE_URL ?? '' ?>/reports" class="flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors"><span class="material-symbols-outlined text-sm">arrow_back</span> Back</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Products</p><h3 class="text-2xl font-bold"><?= number_format($summary['total_products'] ?? 0) ?></h3></div>
    <div class="p-4 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Total Stock</p><h3 class="text-2xl font-bold"><?= number_format($summary['total_stock'] ?? 0) ?></h3></div>
    <div class="p-4 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Cost Value</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_cost_value'] ?? 0, 2) ?></h3></div>
    <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Sale Value</p><h3 class="text-2xl font-bold"><?= htmlspecialchars($currency) ?> <?= number_format($summary['total_sale_value'] ?? 0, 2) ?></h3></div>
    <div class="p-4 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Out of Stock</p><h3 class="text-2xl font-bold"><?= number_format($summary['out_of_stock'] ?? 0) ?></h3></div>
    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 text-white shadow-sm text-center"><p class="text-xs font-semibold uppercase tracking-wider opacity-80 mb-1">Low Stock</p><h3 class="text-2xl font-bold"><?= number_format($summary['low_stock'] ?? 0) ?></h3></div>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h5 class="font-bold text-slate-900">Stock Details</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="inventoryTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">Item Code</th><th class="px-6 py-4">Product Name</th><th class="px-6 py-4">Category</th><th class="px-6 py-4 text-right">Stock</th><th class="px-6 py-4 text-right">Cost</th><th class="px-6 py-4 text-right">Sale</th><th class="px-6 py-4 text-right">Stock Value</th><th class="px-6 py-4 text-center">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($inventory as $item): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($item['item_code']) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($item['item_name']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($item['category_name'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-right <?= $item['current_stock'] == 0 ? 'font-bold text-red-500' : ($item['current_stock'] <= $item['reorder_level'] ? 'font-medium text-amber-500' : 'text-slate-900') ?>"><?= number_format($item['current_stock']) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($item['purchase_price'], 2) ?></td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($item['sale_price'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($item['stock_value'] ?? 0, 2) ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($item['current_stock'] == 0): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-red-50 text-red-600 border-red-200">Out</span>
                        <?php elseif ($item['current_stock'] <= $item['reorder_level']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-50 text-amber-600 border-amber-200">Low</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-50 text-green-600 border-green-200">In Stock</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>$(document).ready(function() { $('#inventoryTable').DataTable({ "pageLength": 25 }); });</script>