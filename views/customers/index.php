<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Customers</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your customer database and tracking</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/customers/create" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
        <span class="material-symbols-outlined text-sm">add</span>
        New Customer
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Customers</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= number_format($stats['total'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">group</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Active Customers</p>
                <h3 class="text-xl font-bold text-green-600"><?= number_format($stats['active'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-green-50 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined text-base">how_to_reg</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">New This Month</p>
                <h3 class="text-xl font-bold text-amber-500"><?= number_format($stats['new_this_month'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-amber-50 flex items-center justify-center text-amber-500">
                <span class="material-symbols-outlined text-base">person_add</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Outstanding Balance</p>
                <h3 class="text-xl font-bold text-red-500"><?= htmlspecialchars($currency) ?> <?= number_format($stats['outstanding'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-red-50 flex items-center justify-center text-red-500">
                <span class="material-symbols-outlined text-base">account_balance_wallet</span>
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
                <input type="text" name="search" class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Name, email, phone..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">City</label>
            <select name="city" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Cities</option>
                <?php foreach ($cities ?? [] as $city): ?>
                <option value="<?= htmlspecialchars($city) ?>" <?= ($_GET['city'] ?? '') == $city ? 'selected' : '' ?>><?= htmlspecialchars($city) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Country</label>
            <select name="country" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Countries</option>
                <?php foreach ($countries ?? [] as $country): ?>
                <option value="<?= htmlspecialchars($country) ?>" <?= ($_GET['country'] ?? '') == $country ? 'selected' : '' ?>><?= htmlspecialchars($country) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
            <a href="<?= BASE_URL ?? '' ?>/customers" class="flex items-center justify-center px-4 py-1.5 bg-slate-100 text-slate-600 rounded-md text-sm font-medium hover:bg-slate-200 transition-colors" title="Clear Filters">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2 whitespace-nowrap">Code</th>
                    <th class="px-4 py-2 whitespace-nowrap">Company / Contact</th>
                    <th class="px-4 py-2 whitespace-nowrap">Email / Phone</th>
                    <th class="px-4 py-2 whitespace-nowrap">Location</th>
                    <th class="px-4 py-2 text-center whitespace-nowrap">Invoices</th>
                    <th class="px-4 py-2 text-right whitespace-nowrap">Outstanding</th>
                    <th class="px-4 py-2 text-center whitespace-nowrap">Status</th>
                    <th class="px-4 py-2 text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="8">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">group</span>
                            <p class="text-base font-medium text-slate-500">No customers found</p>
                            <p class="text-sm mt-1">Adjust your filters or add a new customer.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <a href="<?= BASE_URL ?? '' ?>/customers/view/<?= $customer['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($customer['customer_code'] ?? 'CUST-' . $customer['id']) ?>
                        </a>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap max-w-[200px] truncate" title="<?= htmlspecialchars($customer['company_name'] ?? '') ?>">
                        <div class="font-medium text-slate-900"><?= htmlspecialchars($customer['company_name'] ?? '') ?></div>
                        <div class="text-xs text-slate-500"><?= htmlspecialchars($customer['contact_person'] ?? '') ?></div>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <div class="text-slate-900"><?= htmlspecialchars($customer['email'] ?? '') ?></div>
                        <div class="text-xs text-slate-500"><?= htmlspecialchars($customer['phone'] ?: 'No phone') ?></div>
                    </td>
                    <td class="px-4 py-1.5 text-slate-600 whitespace-nowrap">
                        <?= htmlspecialchars($customer['city'] ?: '-') ?>, <?= htmlspecialchars($customer['country'] ?: '-') ?>
                    </td>
                    <td class="px-4 py-1.5 text-center whitespace-nowrap">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-semibold">
                            <?= number_format($customer['total_invoices'] ?? 0) ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 text-right whitespace-nowrap">
                        <span class="font-medium <?= ($customer['outstanding_balance'] ?? 0) > 0 ? 'text-red-500' : 'text-green-600' ?>">
                            <?= htmlspecialchars($currency) ?> <?= number_format($customer['outstanding_balance'] ?? 0, 2) ?>
                        </span>
                    </td>
                    <td class="px-4 py-1.5 text-center whitespace-nowrap">
                        <?php if (($customer['status'] ?? 0) == 1): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-green-50 text-green-600 border-green-200">Active</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-slate-100 text-slate-600 border-slate-200">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-1.5 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= BASE_URL ?? '' ?>/customers/view/<?= $customer['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <a href="<?= BASE_URL ?? '' ?>/customers/edit/<?= $customer['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <a href="<?= BASE_URL ?? '' ?>/invoices/create?customer_id=<?= $customer['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-green-600 transition-colors shadow-sm" title="New Invoice">
                                <span class="material-symbols-outlined text-sm">receipt_long</span>
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