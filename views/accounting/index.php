<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Accounting</h1>
        <p class="text-sm text-slate-500 mt-1">Manage chart of accounts, journal entries, and financial reports</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/accounting/createJournal" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-sm">add</span>
        New Journal Entry
    </a>
</div>

<!-- Accounting Modules Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="<?= BASE_URL ?? '' ?>/accounting/chartOfAccounts" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-primary/30 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors">
                <span class="material-symbols-outlined">account_tree</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-primary transition-colors">Chart of Accounts</h3>
                <p class="text-sm text-slate-500 mt-0.5">Manage your general ledger accounts</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-slate-400"><?= $accountCounts['chart_of_accounts'] ?? 'View' ?> accounts</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-primary transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/accounting/journalEntries" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-green-500/30 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-100 transition-colors">
                <span class="material-symbols-outlined">book</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-green-600 transition-colors">Journal Entries</h3>
                <p class="text-sm text-slate-500 mt-0.5">Record and manage entries</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-slate-400"><?= $accountCounts['journal_entries'] ?? 'View' ?> entries</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-green-500 transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/accounting/trialBalance" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-purple-500/30 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500 group-hover:bg-purple-100 transition-colors">
                <span class="material-symbols-outlined">balance</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-purple-600 transition-colors">Trial Balance</h3>
                <p class="text-sm text-slate-500 mt-0.5">View account balances</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-slate-400">Check balances</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-purple-500 transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/accounting/balanceSheet" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-500/30 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-100 transition-colors">
                <span class="material-symbols-outlined">account_balance</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Balance Sheet</h3>
                <p class="text-sm text-slate-500 mt-0.5">Assets, liabilities & equity</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-slate-400">View report</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-indigo-500 transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/accounting/profitLoss" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-500/30 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors">
                <span class="material-symbols-outlined">bar_chart</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Profit & Loss</h3>
                <p class="text-sm text-slate-500 mt-0.5">Revenue and expense summary</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-slate-400">View report</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-amber-500 transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>

    <a href="<?= BASE_URL ?? '' ?>/accounting/createJournal" class="bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm hover:shadow-md hover:border-primary/40 transition-all p-6 group">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined">add_circle</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-primary transition-colors">New Journal Entry</h3>
                <p class="text-sm text-slate-500 mt-0.5">Create a new journal entry</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-primary font-medium">Create now</span>
            <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-primary transition-all group-hover:translate-x-1">arrow_forward</span>
        </div>
    </a>
</div>