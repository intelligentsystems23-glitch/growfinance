<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Customer Report</h1>
        <p class="text-sm text-slate-500 mt-1">Customer purchase history and outstanding balances</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?? '' ?>/reports/export/customers/csv" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">file_download</span> CSV</a>
        <a href="<?= BASE_URL ?? '' ?>/reports/export/customers/pdf" target="_blank" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-600 transition-colors shadow-sm"><span class="material-symbols-outlined text-sm">picture_as_pdf</span> PDF</a>
        <a href="<?= BASE_URL ?? '' ?>/reports" class="flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors"><span class="material-symbols-outlined text-sm">arrow_back</span> Back</a>
    </div>
</div>

<!-- Customer Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h5 class="font-bold text-slate-900">Customer Purchase Summary</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="customerReportTable">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Contact</th><th class="px-6 py-4">Email</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4 text-right">Invoices</th><th class="px-6 py-4 text-right">Purchases</th><th class="px-6 py-4 text-right">Paid</th><th class="px-6 py-4 text-right">Outstanding</th><th class="px-6 py-4">Last Purchase</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($customers as $customer): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4"><a href="customers/view/<?= $customer['id'] ?>" class="font-medium text-primary hover:text-primary/80"><?= htmlspecialchars($customer['company_name']) ?></a></td>
                    <td class="px-6 py-4 text-slate-700"><?= htmlspecialchars($customer['contact_person']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($customer['email']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($customer['phone']) ?></td>
                    <td class="px-6 py-4 text-right"><span class="inline-flex items-center justify-center px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold"><?= number_format($customer['total_invoices']) ?></span></td>
                    <td class="px-6 py-4 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($customer['total_purchases'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($customer['total_paid'], 2) ?></td>
                    <td class="px-6 py-4 text-right"><span class="font-medium <?= $customer['outstanding'] > 0 ? 'text-red-500' : 'text-green-600' ?>"><?= htmlspecialchars($currency) ?> <?= number_format($customer['outstanding'], 2) ?></span></td>
                    <td class="px-6 py-4 text-slate-500"><?= $customer['last_purchase'] ? date('d/m/Y', strtotime($customer['last_purchase'])) : 'Never' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>$(document).ready(function() { $('#customerReportTable').DataTable({ "pageLength": 25, "order": [[5, "desc"]] }); });</script>