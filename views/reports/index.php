<?php $hide_title = true; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Reports</h1>
    <p class="text-sm text-slate-500 mt-1">View and analyze business performance across all modules</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <a href="<?= BASE_URL ?? '' ?>/reports/sales" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">bar_chart</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Sales Reports</h3>
            <p class="text-sm text-slate-500 mt-1">Analyze sales performance, customer purchases, and revenue trends</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full group-hover:bg-blue-100 transition-colors">
                View Reports
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/expenses" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-red-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">payments</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-red-600 transition-colors">Expense Reports</h3>
            <p class="text-sm text-slate-500 mt-1">Track expenses by category, vendor, and time period</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-red-600 bg-red-50 px-4 py-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                View Reports
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/purchases" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-green-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">shopping_cart</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-green-600 transition-colors">Purchase Reports</h3>
            <p class="text-sm text-slate-500 mt-1">Monitor purchase orders, vendor spending, and procurement</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-green-600 bg-green-50 px-4 py-1.5 rounded-full group-hover:bg-green-100 transition-colors">
                View Reports
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/profitLoss" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">account_balance</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Profit & Loss</h3>
            <p class="text-sm text-slate-500 mt-1">View income, expenses, and net profit over time</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full group-hover:bg-indigo-100 transition-colors">
                View P&L Statement
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/tax" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">receipt_long</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Tax Report</h3>
            <p class="text-sm text-slate-500 mt-1">Summarize tax collected on sales transactions</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-amber-600 bg-amber-50 px-4 py-1.5 rounded-full group-hover:bg-amber-100 transition-colors">
                View Tax Report
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/inventory" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-purple-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500 group-hover:bg-purple-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">inventory_2</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600 transition-colors">Inventory Report</h3>
            <p class="text-sm text-slate-500 mt-1">Stock levels, valuation, and movement analysis</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-purple-600 bg-purple-50 px-4 py-1.5 rounded-full group-hover:bg-purple-100 transition-colors">
                View Inventory Report
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/reports/customers" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-teal-400/50 transition-all p-6 group">
        <div class="flex flex-col items-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-500 group-hover:bg-teal-100 transition-colors mb-4">
                <span class="material-symbols-outlined text-3xl">group</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors">Customer Report</h3>
            <p class="text-sm text-slate-500 mt-1">Customer purchase history and outstanding balances</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-teal-600 bg-teal-50 px-4 py-1.5 rounded-full group-hover:bg-teal-100 transition-colors">
                View Customer Report
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </div>
    </a>
</div>