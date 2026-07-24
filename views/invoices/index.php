<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Invoices</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage and track your customer invoices</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/invoices/create" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
        <span class="material-symbols-outlined text-sm">add</span>
        New Invoice
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <!-- Total Invoices -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Invoices</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= number_format($stats['total_count'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">receipt_long</span>
            </div>
        </div>
    </div>
    <!-- Total Amount -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Amount</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($currency) ?> <?= number_format($stats['total_amount'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-blue-50 flex items-center justify-center text-blue-500">
                <span class="material-symbols-outlined text-base">account_balance_wallet</span>
            </div>
        </div>
    </div>
    <!-- Paid Amount -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Paid Amount</p>
                <h3 class="text-xl font-bold text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($stats['paid_amount'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-green-50 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined text-base">check_circle</span>
            </div>
        </div>
    </div>
    <!-- Outstanding -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Outstanding</p>
                <h3 class="text-xl font-bold text-amber-500"><?= htmlspecialchars($currency) ?> <?= number_format($stats['outstanding_amount'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-amber-50 flex items-center justify-center text-amber-500">
                <span class="material-symbols-outlined text-base">pending_actions</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-3">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Invoice # or customer..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= ($_GET['status'] ?? '') == 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="paid" <?= ($_GET['status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="overdue" <?= ($_GET['status'] ?? '') == 'overdue' ? 'selected' : '' ?>>Overdue</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= $_GET['date_from'] ?? '' ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= $_GET['date_to'] ?? '' ?>">
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
            <a href="<?= BASE_URL ?? '' ?>/invoices" class="flex items-center justify-center px-4 py-1.5 bg-slate-100 text-slate-600 rounded-md text-sm font-medium hover:bg-slate-200 transition-colors" title="Clear Filters">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
        </div>
    </form>
</div>

<!-- Invoices Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2 whitespace-nowrap">Invoice #</th>
                    <th class="px-4 py-2 whitespace-nowrap">Date</th>
                    <th class="px-4 py-2 whitespace-nowrap">Customer</th>
                    <th class="px-4 py-2 whitespace-nowrap">Due Date</th>
                    <th class="px-4 py-2 text-right whitespace-nowrap">Amount</th>
                    <th class="px-4 py-2 text-right whitespace-nowrap">Paid</th>
                    <th class="px-4 py-2 text-right whitespace-nowrap">Balance</th>
                    <th class="px-4 py-2 text-center whitespace-nowrap">Status</th>
                    <th class="px-4 py-2 text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="9">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">receipt_long</span>
                            <p class="text-base font-medium text-slate-500">No invoices found</p>
                            <p class="text-sm mt-1">Adjust your filters or create a new invoice.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($invoices as $inv): ?>
                <?php $balance = $inv['total_amount'] - $inv['paid_amount']; ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <a href="<?= BASE_URL ?? '' ?>/invoices/show/<?= $inv['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($inv['invoice_number'] ?? ('#' . $inv['id'])) ?>
                        </a>
                    </td>
                    <td class="px-4 py-1.5 text-slate-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($inv['invoice_date'])) ?></td>
                    <td class="px-4 py-1.5 whitespace-nowrap max-w-[200px] truncate" title="<?= htmlspecialchars($inv['company_name'] ?? 'N/A') ?>">
                        <span class="font-medium text-slate-900"><?= htmlspecialchars($inv['company_name'] ?? 'N/A') ?></span>
                    </td>
                    <td class="px-4 py-1.5 text-slate-600 whitespace-nowrap"><?= date('d/m/Y', strtotime($inv['due_date'])) ?></td>
                    <td class="px-4 py-1.5 text-right font-medium text-slate-900 whitespace-nowrap"><?= htmlspecialchars($currency) ?> <?= number_format($inv['total_amount'], 2) ?></td>
                    <td class="px-4 py-1.5 text-right font-medium text-green-600 whitespace-nowrap"><?= htmlspecialchars($currency) ?> <?= number_format($inv['paid_amount'], 2) ?></td>
                    <td class="px-4 py-1.5 text-right font-medium <?= $balance > 0 ? 'text-amber-500' : 'text-slate-500' ?> whitespace-nowrap">
                        <?= htmlspecialchars($currency) ?> <?= number_format($balance, 2) ?>
                    </td>
                    <td class="px-4 py-1.5 text-center whitespace-nowrap">
                        <?php
                        $status_colors = [
                            'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'sent' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'paid' => 'bg-green-50 text-green-600 border-green-200',
                            'overdue' => 'bg-red-50 text-red-600 border-red-200'
                        ];
                        $color_class = $status_colors[$inv['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($inv['status']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= BASE_URL ?? '' ?>/invoices/show/<?= $inv['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <a href="<?= BASE_URL ?? '' ?>/invoices/edit/<?= $inv['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>