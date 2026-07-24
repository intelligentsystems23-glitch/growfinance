<?php
// Dashboard data is passed from DashboardController
// The layout (header/footer) is loaded by the controller
?>

<!-- Dashboard Header -->
<div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
    <div class="flex items-center gap-4">
        <div class="h-12 w-12 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-2xl">grid_view</span>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Enterprise Treasury Dashboard</h2>
            <p class="text-xs text-slate-500 mt-0.5">Real-time ledger balances, dynamic cash flow, and auditor maker-checker approvals.</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/expenses/create" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 bg-white shadow-sm">
            <span class="material-symbols-outlined text-lg">payments</span>
            <span>Log Expense</span>
        </a>
        <a href="<?= BASE_URL ?>/invoices/create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all flex items-center gap-1 shadow-sm">
            <span class="material-symbols-outlined text-lg">add</span>
            <span>Issue Invoice</span>
        </a>
    </div>
</div>

<!-- First-Time Onboarding & Getting Started Guide -->
<?php 
$onboard = $onboarding ?? ['completed' => 0, 'total' => 5, 'percentage' => 0, 'steps' => []]; 
$steps = $onboard['steps'] ?? [];
?>
<div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white rounded-2xl p-6 shadow-xl border border-slate-700/80 mb-6 relative overflow-hidden group">
    <!-- Decorative background glow -->
    <div class="absolute -top-24 -right-24 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
        <div class="space-y-2 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30 uppercase tracking-widest">
                <span class="material-symbols-outlined text-xs">rocket_launch</span>
                First-Time Setup Guide
            </div>
            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">Welcome! Let's set up your business ERP</h2>
            <p class="text-xs text-slate-300 leading-relaxed">
                Follow these 5 essential steps to configure your system preferences, add core accounts, register customers, and issue your first invoice.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 flex items-center gap-4 min-w-[240px]">
            <div class="relative w-14 h-14 flex items-center justify-center font-bold text-base text-blue-400">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-blue-400 transition-all duration-1000 ease-out" stroke-dasharray="<?= $onboard['percentage'] ?>, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <span class="absolute text-xs font-bold text-white"><?= $onboard['percentage'] ?>%</span>
            </div>
            <div>
                <span class="text-xs font-bold text-white"><?= $onboard['completed'] ?> of <?= $onboard['total'] ?> Steps Done</span>
                <p class="text-[11px] text-slate-300 mt-0.5"><?= $onboard['percentage'] === 100 ? 'All setup steps completed!' : 'Complete setup below' ?></p>
            </div>
        </div>
    </div>

    <!-- Onboarding Steps Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 mt-6 pt-6 border-t border-white/10 relative z-10">
        
        <!-- Step 1: Company Settings -->
        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-3.5 border border-white/10 transition-all flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Step 1</span>
                    <?php if (!empty($steps['company'])): ?>
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center" title="Completed">
                            <span class="material-symbols-outlined text-xs">check</span>
                        </span>
                    <?php else: ?>
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-400 flex items-center justify-center text-[10px] font-bold">1</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-xs font-bold text-white">Business Settings</h3>
                <p class="text-[11px] text-slate-300 leading-tight">Company details, logo & tax rate</p>
            </div>
            <a href="<?= BASE_URL ?>/settings" class="inline-flex items-center justify-center gap-1 w-full py-1.5 px-3 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                <span>Configure</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>

        <!-- Step 2: Banking & Float -->
        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-3.5 border border-white/10 transition-all flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Step 2</span>
                    <?php if (!empty($steps['banking'])): ?>
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center" title="Completed">
                            <span class="material-symbols-outlined text-xs">check</span>
                        </span>
                    <?php else: ?>
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-400 flex items-center justify-center text-[10px] font-bold">2</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-xs font-bold text-white">Bank Accounts</h3>
                <p class="text-[11px] text-slate-300 leading-tight">Bank accounts & cash float</p>
            </div>
            <a href="<?= BASE_URL ?>/banking" class="inline-flex items-center justify-center gap-1 w-full py-1.5 px-3 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                <span>Add Account</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>

        <!-- Step 3: Customers & Suppliers -->
        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-3.5 border border-white/10 transition-all flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Step 3</span>
                    <?php if (!empty($steps['contacts'])): ?>
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center" title="Completed">
                            <span class="material-symbols-outlined text-xs">check</span>
                        </span>
                    <?php else: ?>
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-400 flex items-center justify-center text-[10px] font-bold">3</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-xs font-bold text-white">Contacts DB</h3>
                <p class="text-[11px] text-slate-300 leading-tight">Register customers & suppliers</p>
            </div>
            <a href="<?= BASE_URL ?>/customers/create" class="inline-flex items-center justify-center gap-1 w-full py-1.5 px-3 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                <span>Add Customer</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>

        <!-- Step 4: Inventory & Products -->
        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-3.5 border border-white/10 transition-all flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Step 4</span>
                    <?php if (!empty($steps['products'])): ?>
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center" title="Completed">
                            <span class="material-symbols-outlined text-xs">check</span>
                        </span>
                    <?php else: ?>
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-400 flex items-center justify-center text-[10px] font-bold">4</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-xs font-bold text-white">Products Catalog</h3>
                <p class="text-[11px] text-slate-300 leading-tight">Catalog items & initial stock</p>
            </div>
            <a href="<?= BASE_URL ?>/products/create" class="inline-flex items-center justify-center gap-1 w-full py-1.5 px-3 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                <span>Add Product</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>

        <!-- Step 5: Billing & Transactions -->
        <div class="bg-white/5 hover:bg-white/10 rounded-xl p-3.5 border border-white/10 transition-all flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Step 5</span>
                    <?php if (!empty($steps['invoices'])): ?>
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center" title="Completed">
                            <span class="material-symbols-outlined text-xs">check</span>
                        </span>
                    <?php else: ?>
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-400 flex items-center justify-center text-[10px] font-bold">5</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-xs font-bold text-white">First Invoice</h3>
                <p class="text-[11px] text-slate-300 leading-tight">Issue invoice or log expense</p>
            </div>
            <a href="<?= BASE_URL ?>/invoices/create" class="inline-flex items-center justify-center gap-1 w-full py-1.5 px-3 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white transition-colors shadow-md shadow-blue-600/20">
                <span>Create Invoice</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>

    </div>
</div>

<!-- Treasury KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <!-- Total Cash/Float -->
    <a href="<?= BASE_URL ?>/banking" class="bg-[#0e7490] text-white p-3.5 rounded-lg flex items-center gap-4 shadow-sm hover:opacity-95 hover:scale-[1.02] active:scale-[0.99] transition-all cursor-pointer">
        <div class="h-12 w-12 rounded-md bg-white/10 flex items-center justify-center text-white flex-shrink-0">
            <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
        </div>
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-white/70">Total Cash/Float</p>
            <h3 class="text-lg md:text-xl font-bold mt-0.5"><?= htmlspecialchars($currency) ?> <?= number_format($financial['total_cash'] ?? 0, 2) ?></h3>
        </div>
    </a>
    
    <!-- Accounts Receivable -->
    <a href="<?= BASE_URL ?>/invoices" class="bg-[#15803d] text-white p-3.5 rounded-lg flex items-center gap-4 shadow-sm hover:opacity-95 hover:scale-[1.02] active:scale-[0.99] transition-all cursor-pointer">
        <div class="h-12 w-12 rounded-md bg-white/10 flex items-center justify-center text-white flex-shrink-0">
            <span class="material-symbols-outlined text-2xl">trending_up</span>
        </div>
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-white/70">Accounts Receivable</p>
            <h3 class="text-lg md:text-xl font-bold mt-0.5"><?= htmlspecialchars($currency) ?> <?= number_format($financial['outstanding'] ?? 0, 2) ?></h3>
        </div>
    </a>
    
    <!-- Pending Auditor Checks -->
    <a href="<?= BASE_URL ?>/expenses" class="bg-[#b45309] text-white p-3.5 rounded-lg flex items-center gap-4 shadow-sm hover:opacity-95 hover:scale-[1.02] active:scale-[0.99] transition-all cursor-pointer">
        <div class="h-12 w-12 rounded-md bg-white/10 flex items-center justify-center text-white flex-shrink-0">
            <span class="material-symbols-outlined text-2xl">how_to_reg</span>
        </div>
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-white/70">Pending Auditor Checks</p>
            <h3 class="text-lg md:text-xl font-bold mt-0.5"><?= number_format($financial['pending_auditor_checks'] ?? 0) ?> Action Tasks</h3>
        </div>
    </a>
    
    <!-- Low Stock Alerts -->
    <a href="<?= BASE_URL ?>/products" class="bg-[#b91c1c] text-white p-3.5 rounded-lg flex items-center gap-4 shadow-sm hover:opacity-95 hover:scale-[1.02] active:scale-[0.99] transition-all cursor-pointer">
        <div class="h-12 w-12 rounded-md bg-white/10 flex items-center justify-center text-white flex-shrink-0">
            <span class="material-symbols-outlined text-2xl">inventory_2</span>
        </div>
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-white/70">Low Stock Alerts</p>
            <h3 class="text-lg md:text-xl font-bold mt-0.5"><?= number_format($counts['low_stock'] ?? 0) ?> Products Low</h3>
        </div>
    </a>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <!-- Left Column: Sales & Quick Links -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Sales Summary -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-slate-900">Sales Summary</h3>
                    <p class="text-sm text-slate-500">Revenue across time periods</p>
                </div>
                <span class="text-xs font-semibold bg-green-50 text-green-600 px-3 py-1 rounded-full">Live</span>
            </div>
            <div class="p-3.5">
                <div class="grid grid-cols-4 gap-4 text-center mb-4">
                    <a href="<?= BASE_URL ?>/invoices" class="p-3 rounded-md bg-slate-50 block hover:bg-slate-100 hover:scale-[1.03] transition-all cursor-pointer">
                        <p class="text-xs text-slate-500 mb-1">Today</p>
                        <p class="font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($sales['today'] ?? 0, 2) ?></p>
                    </a>
                    <a href="<?= BASE_URL ?>/invoices" class="p-3 rounded-md bg-slate-50 block hover:bg-slate-100 hover:scale-[1.03] transition-all cursor-pointer">
                        <p class="text-xs text-slate-500 mb-1">This Week</p>
                        <p class="font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($sales['week'] ?? 0, 2) ?></p>
                    </a>
                    <a href="<?= BASE_URL ?>/invoices" class="p-3 rounded-md bg-slate-50 block hover:bg-slate-100 hover:scale-[1.03] transition-all cursor-pointer">
                        <p class="text-xs text-slate-500 mb-1">This Month</p>
                        <p class="font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($sales['month'] ?? 0, 2) ?></p>
                    </a>
                    <a href="<?= BASE_URL ?>/invoices" class="p-3 rounded-md bg-blue-50 block hover:bg-blue-100 hover:scale-[1.03] transition-all cursor-pointer">
                        <p class="text-xs text-slate-500 mb-1">This Year</p>
                        <p class="font-bold text-blue-700"><?= htmlspecialchars($currency) ?> <?= number_format($sales['year'] ?? 0, 2) ?></p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
            <div class="px-4 py-2.5 border-b border-slate-100">
                <h3 class="font-semibold text-slate-900">Quick Links</h3>
            </div>
            <div class="p-3.5 grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="<?= BASE_URL ?>/invoices" class="flex flex-col items-center p-4 bg-slate-50 rounded-md hover:bg-blue-50 hover:text-blue-700 transition-colors group">
                    <span class="material-symbols-outlined text-blue-500 text-3xl mb-2 group-hover:scale-110 transition-transform">description</span>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-blue-700">Invoices</span>
                </a>
                <a href="<?= BASE_URL ?>/customers" class="flex flex-col items-center p-4 bg-slate-50 rounded-md hover:bg-green-50 hover:text-green-700 transition-colors group">
                    <span class="material-symbols-outlined text-green-500 text-3xl mb-2 group-hover:scale-110 transition-transform">group</span>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-green-700">Customers</span>
                </a>
                <a href="<?= BASE_URL ?>/products" class="flex flex-col items-center p-4 bg-slate-50 rounded-md hover:bg-purple-50 hover:text-purple-700 transition-colors group">
                    <span class="material-symbols-outlined text-purple-500 text-3xl mb-2 group-hover:scale-110 transition-transform">inventory_2</span>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-purple-700">Products</span>
                </a>
                <a href="<?= BASE_URL ?>/reports" class="flex flex-col items-center p-4 bg-slate-50 rounded-md hover:bg-amber-50 hover:text-amber-700 transition-colors group">
                    <span class="material-symbols-outlined text-amber-500 text-3xl mb-2 group-hover:scale-110 transition-transform">assessment</span>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-amber-700">Reports</span>
                </a>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-semibold text-slate-900">Recent Invoices</h3>
                <a href="<?= BASE_URL ?>/invoices" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-2.5 font-semibold whitespace-nowrap">Invoice #</th>
                            <th class="text-left px-4 py-2.5 font-semibold whitespace-nowrap">Customer</th>
                            <th class="text-left px-4 py-2.5 font-semibold whitespace-nowrap">Date</th>
                            <th class="text-right px-4 py-2.5 font-semibold whitespace-nowrap">Amount</th>
                            <th class="text-center px-4 py-2.5 font-semibold whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_invoices)): ?>
                        <?php foreach ($recent_invoices as $inv): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2.5 whitespace-nowrap">
                                <a href="<?= BASE_URL ?>/invoices/show/<?= $inv['id'] ?>" class="font-medium text-blue-600 hover:text-blue-700">
                                    <?= htmlspecialchars($inv['invoice_number'] ?? ('#' . $inv['id'])) ?>
                                </a>
                            </td>
                            <td class="px-4 py-2.5 text-slate-700 whitespace-nowrap max-w-[150px] truncate" title="<?= htmlspecialchars($inv['company_name'] ?? 'N/A') ?>"><?= htmlspecialchars($inv['company_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2.5 text-slate-500 whitespace-nowrap"><?= date('d/m/Y', strtotime($inv['invoice_date'])) ?></td>
                            <td class="px-4 py-2.5 text-right font-medium whitespace-nowrap"><?= htmlspecialchars($currency) ?> <?= number_format($inv['total_amount'], 2) ?></td>
                            <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                <?php
                                $status_colors = ['draft'=>'bg-slate-100 text-slate-600','sent'=>'bg-blue-100 text-blue-700','paid'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700'];
                                $color = $status_colors[$inv['status']] ?? 'bg-slate-100 text-slate-600';
                                ?>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap <?= $color ?>">
                                    <?= ucfirst($inv['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">No recent invoices found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-4">


        <!-- Recent Customers -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
            <div class="px-4 py-2.5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-semibold text-slate-900">Recent Customers</h3>
                <a href="<?= BASE_URL ?>/customers" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
            </div>
            <div class="p-3.5 space-y-3">
                <?php if (!empty($recent_customers)): ?>
                <?php foreach ($recent_customers as $customer): ?>
                <a href="<?= BASE_URL ?>/customers/view/<?= $customer['id'] ?>" class="flex items-center gap-3 p-2 rounded-md hover:bg-slate-50 transition-colors">
                    <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <?= strtoupper(substr($customer['company_name'] ?? $customer['name'] ?? 'C', 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900"><?= htmlspecialchars($customer['company_name'] ?? $customer['name'] ?? 'Customer') ?></p>
                        <p class="text-xs text-slate-400">Joined <?= date('d/m/Y', strtotime($customer['created_at'] ?? date('Y-m-d'))) ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center py-6 text-slate-400">
                    <i class="fas fa-users text-2xl mb-2 opacity-50"></i>
                    <p class="text-sm">No recent customers yet</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
            <div class="px-4 py-2.5 border-b border-slate-100">
                <h3 class="font-semibold text-slate-900">Recent Payments</h3>
            </div>
            <div class="p-3.5 space-y-3">
                <?php if (!empty($recent_payments)): ?>
                <?php foreach ($recent_payments as $payment): ?>
                <a href="<?= BASE_URL ?>/banking/transactions" class="flex items-center justify-between p-2 rounded-md hover:bg-slate-50 transition-colors block">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($payment['amount'], 2) ?></p>
                            <p class="text-xs text-slate-400"><?= htmlspecialchars($payment['company_name'] ?? 'Customer') ?></p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400"><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></span>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center py-6 text-slate-400">
                    <i class="fas fa-credit-card text-2xl mb-2 opacity-50"></i>
                    <p class="text-sm">No recent payments</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (!empty($alerts)): ?>
        <div class="space-y-2">
            <?php foreach ($alerts as $alert): ?>
            <div class="p-4 rounded-md border <?= $alert['type'] === 'danger' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-amber-50 border-amber-200 text-amber-700' ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-<?= $alert['icon'] ?>"></i>
                    <p class="text-sm font-medium"><?= htmlspecialchars($alert['message']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>