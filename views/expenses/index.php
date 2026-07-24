<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Expenses</h1>
        <p class="text-sm text-slate-500 mt-0.5">Track and manage your company expenditures</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/expenses/create" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
        <span class="material-symbols-outlined text-sm">add</span>
        Record Expense
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <!-- Total Expenses -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Expenses</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= htmlspecialchars($currency) ?> <?= number_format($stats['total'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">payments</span>
            </div>
        </div>
    </div>
    <!-- This Month -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">This Month</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($currency) ?> <?= number_format($stats['this_month'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-blue-50 flex items-center justify-center text-blue-500">
                <span class="material-symbols-outlined text-base">calendar_today</span>
            </div>
        </div>
    </div>
    <!-- Paid Expenses -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Paid Expenses</p>
                <h3 class="text-xl font-bold text-green-600"><?= htmlspecialchars($currency) ?> <?= number_format($stats['paid_amount'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-green-50 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined text-base">check_circle</span>
            </div>
        </div>
    </div>
    <!-- Pending Approval -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Pending Approval</p>
                <h3 class="text-xl font-bold text-amber-500"><?= htmlspecialchars($currency) ?> <?= number_format($stats['pending_amount'] ?? 0, 2) ?></h3>
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
                <input type="text" name="search" class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search expenses..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Category</label>
            <select name="category_id" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Categories</option>
                <?php foreach ($categories ?? [] as $category): ?>
                <option value="<?= $category['id'] ?>" <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Status</label>
            <select name="payment_status" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="pending" <?= ($_GET['payment_status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= ($_GET['payment_status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
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
        <div class="md:col-span-1 flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors" title="Filter">
                <span class="material-symbols-outlined text-sm">filter_list</span>
            </button>
        </div>
    </form>
</div>

<!-- Expenses Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Expense #</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Vendor</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2 text-right">Amount</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($expenses)): ?>
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center py-6 text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">receipt_long</span>
                            <p class="text-sm font-medium text-slate-500">No expenses found</p>
                            <p class="text-xs mt-1">Adjust your filters or record a new expense.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($expenses as $expense): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-1.5 font-semibold text-primary">
                        <?= htmlspecialchars($expense['expense_number']) ?>
                    </td>
                    <td class="px-4 py-1.5 text-slate-600"><?= date('d/m/Y', strtotime($expense['expense_date'])) ?></td>
                    <td class="px-4 py-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">
                            <?= htmlspecialchars($expense['category_name'] ?? 'Uncategorized') ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 font-medium text-slate-900"><?= htmlspecialchars($expense['vendor_name'] ?? '-') ?></td>
                    <td class="px-4 py-1.5 text-slate-500 truncate max-w-[200px]" title="<?= htmlspecialchars($expense['description']) ?>">
                        <?= htmlspecialchars(strlen($expense['description']) > 40 ? substr($expense['description'], 0, 40) . '...' : $expense['description']) ?>
                    </td>
                    <td class="px-4 py-1.5 text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($expense['amount'], 2) ?></td>
                    <td class="px-4 py-1.5 text-center">
                        <?php
                        $status_colors = [
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'paid' => 'bg-green-50 text-green-600 border-green-200',
                            'cancelled' => 'bg-red-50 text-red-600 border-red-200'
                        ];
                        $color_class = $status_colors[$expense['payment_status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($expense['payment_status']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= BASE_URL ?? '' ?>/expenses/view/<?= $expense['id'] ?>" class="h-7 w-7 rounded border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 flex items-center justify-center hover:text-blue-600 transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                            </a>
                            <?php if ($expense['payment_status'] !== 'paid'): ?>
                            <a href="<?= BASE_URL ?? '' ?>/expenses/edit/<?= $expense['id'] ?>" class="h-7 w-7 rounded border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 flex items-center justify-center hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-[15px]">edit</span>
                            </a>
                            <?php endif; ?>
                            <?php if (!$expense['approved_by']): ?>
                            <button onclick="approveExpense(<?= $expense['id'] ?>)" class="h-7 w-7 rounded border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 flex items-center justify-center hover:text-green-600 transition-colors shadow-sm" title="Approve">
                                <span class="material-symbols-outlined text-[15px]">check</span>
                            </button>
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
function approveExpense(id) {
    if (confirm('Approve this expense?')) {
        $.ajax({
            url: '<?= BASE_URL ?? '' ?>/expenses/approve/' + id,
            type: 'POST',
            success: function(response) {
                location.reload();
            },
            error: function() {
                location.reload();
            }
        });
    }
}
</script>