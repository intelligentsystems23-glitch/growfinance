<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
        <p class="text-sm text-slate-500 mt-1">Manage purchase orders, vendor orders, and stock receipts</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/purchase-orders/create" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-sm">add</span>
        New Purchase Order
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Total POs</p>
                <h3 class="text-2xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= number_format($stats['total_pos'] ?? 0) ?></h3>
            </div>
            <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <span class="material-symbols-outlined">shopping_cart</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Draft / Sent</p>
                <h3 class="text-2xl font-bold text-amber-500"><?= number_format(($stats['draft'] ?? 0) + ($stats['sent'] ?? 0)) ?></h3>
            </div>
            <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Received</p>
                <h3 class="text-2xl font-bold text-green-600"><?= number_format($stats['received'] ?? 0) ?></h3>
            </div>
            <div class="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined">inventory</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Outstanding Amount</p>
                <h3 class="text-2xl font-bold text-red-500"><?= htmlspecialchars($currency) ?> <?= number_format(($stats['total_amount'] ?? 0) - ($stats['paid_amount'] ?? 0), 2) ?></h3>
            </div>
            <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                <span class="material-symbols-outlined">account_balance_wallet</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search PO # or vendor..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-500 mb-1">Vendor</label>
            <select name="vendor_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Vendors</option>
                <?php foreach ($vendors ?? [] as $vendor): ?>
                <option value="<?= $vendor['id'] ?>" <?= ($_GET['vendor_id'] ?? '') == $vendor['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($vendor['company_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= ($_GET['status'] ?? '') == 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="received" <?= ($_GET['status'] ?? '') == 'received' ? 'selected' : '' ?>>Received</option>
                <option value="cancelled" <?= ($_GET['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= $_GET['date_from'] ?? '' ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= $_GET['date_to'] ?? '' ?>">
        </div>
        <div class="md:col-span-1 flex gap-1">
            <button type="submit" class="flex-1 flex items-center justify-center bg-slate-900 text-white px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors h-[38px]" title="Filter">
                <span class="material-symbols-outlined text-sm">filter_list</span>
            </button>
            <a href="<?= BASE_URL ?? '' ?>/purchase-orders" class="flex items-center justify-center px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm hover:bg-slate-200 transition-colors h-[38px]" title="Clear">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
        </div>
    </form>
</div>

<!-- POs Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">PO Number</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Vendor</th>
                    <th class="px-6 py-4">Expected</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-right">Paid</th>
                    <th class="px-6 py-4 text-right">Balance</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Payment</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($purchase_orders ?? [])): ?>
                <tr>
                    <td colspan="10">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">shopping_cart</span>
                            <p class="text-base font-medium text-slate-500">No purchase orders found</p>
                            <p class="text-sm mt-1">Create your first purchase order to get started.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($purchase_orders as $po): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <a href="<?= BASE_URL ?? '' ?>/purchase-orders/view/<?= $po['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($po['po_number']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= date('d/m/Y', strtotime($po['po_date'])) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($po['vendor_name'] ?? '') ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= $po['expected_date'] ? date('d/m/Y', strtotime($po['expected_date'])) : '-' ?></td>
                    <td class="px-6 py-4 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($po['total_amount'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($po['paid_amount'] ?? 0, 2) ?></td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-medium <?= ($po['total_amount'] - ($po['paid_amount'] ?? 0)) > 0 ? 'text-red-500' : 'text-green-600' ?>">
                            <?= htmlspecialchars($currency) ?> <?= number_format($po['total_amount'] - ($po['paid_amount'] ?? 0), 2) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $po_status_styles = [
                            'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'sent' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'received' => 'bg-green-50 text-green-600 border-green-200',
                            'cancelled' => 'bg-red-50 text-red-600 border-red-200'
                        ];
                        $po_style = $po_status_styles[$po['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $po_style ?>">
                            <?= ucfirst($po['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $payment_styles = [
                            'unpaid' => 'bg-red-50 text-red-600 border-red-200',
                            'partial' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'paid' => 'bg-green-50 text-green-600 border-green-200'
                        ];
                        $p_style = $payment_styles[$po['payment_status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $p_style ?>">
                            <?= ucfirst($po['payment_status'] ?? 'unpaid') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= BASE_URL ?? '' ?>/purchase-orders/view/<?= $po['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <?php if ($po['status'] == 'draft'): ?>
                            <a href="<?= BASE_URL ?? '' ?>/purchase-orders/edit/<?= $po['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <button onclick="approvePO(<?= $po['id'] ?>)" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-green-600 transition-colors shadow-sm" title="Approve">
                                <span class="material-symbols-outlined text-sm">check</span>
                            </button>
                            <?php endif; ?>
                            <?php if ($po['status'] == 'sent'): ?>
                            <a href="<?= BASE_URL ?? '' ?>/purchase-orders/receive/<?= $po['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-amber-500 transition-colors shadow-sm" title="Receive Stock">
                                <span class="material-symbols-outlined text-sm">inventory_2</span>
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

<script>
$(document).ready(function() {
    <?php if (isset($purchase_orders) && count($purchase_orders) > 0): ?>
    // Simple table sorting can be added here if needed
    <?php endif; ?>
});

function approvePO(id) {
    if (confirm('Approve this purchase order?')) {
        $.ajax({
            url: '<?= BASE_URL ?? '' ?>/purchase-orders/approve/' + id,
            type: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    }
}
</script>