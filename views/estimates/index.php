<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Estimates</h1>
        <p class="text-sm text-slate-500 mt-1">Create and manage customer estimates and proposals</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/estimates/create" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-sm">add</span>
        New Estimate
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search estimate # or customer..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= ($_GET['status'] ?? '') == 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="accepted" <?= ($_GET['status'] ?? '') == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                <option value="invoiced" <?= ($_GET['status'] ?? '') == 'invoiced' ? 'selected' : '' ?>>Invoiced</option>
                <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                <option value="expired" <?= ($_GET['status'] ?? '') == 'expired' ? 'selected' : '' ?>>Expired</option>
            </select>
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
            <a href="<?= BASE_URL ?? '' ?>/estimates" class="flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors" title="Clear Filters">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
        </div>
    </form>
</div>

<!-- Estimates Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Estimate #</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Expiry</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($estimates)): ?>
                <tr>
                    <td colspan="7">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">calculator</span>
                            <p class="text-base font-medium text-slate-500">No estimates found</p>
                            <p class="text-sm mt-1">Create your first estimate to get started.</p>
                            <a href="<?= BASE_URL ?? '' ?>/estimates/create" class="mt-3 text-sm font-medium text-primary hover:text-primary/80">+ Create Estimate</a>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($estimates as $est): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <a href="<?= BASE_URL ?? '' ?>/estimates/view/<?= $est['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($est['estimate_number']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= date('d/m/Y', strtotime($est['estimate_date'])) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($est['company_name'] ?? 'N/A') ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= $est['expiry_date'] ? date('d/m/Y', strtotime($est['expiry_date'])) : '-' ?></td>
                    <td class="px-6 py-4 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($est['total_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $status_styles = [
                            'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'sent' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'accepted' => 'bg-green-50 text-green-600 border-green-200',
                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                            'invoiced' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                            'expired' => 'bg-amber-50 text-amber-600 border-amber-200'
                        ];
                        $style = $status_styles[$est['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $style ?>">
                            <?= ucfirst($est['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= BASE_URL ?? '' ?>/estimates/view/<?= $est['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <?php if (in_array($est['status'], ['draft', 'sent', 'accepted'])): ?>
                            <a href="<?= BASE_URL ?? '' ?>/estimates/convert/<?= $est['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-green-600 transition-colors shadow-sm" title="Convert to Invoice" onclick="return confirm('Convert this estimate to an invoice?')">
                                <span class="material-symbols-outlined text-sm">receipt_long</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>