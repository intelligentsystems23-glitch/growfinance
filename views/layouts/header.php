<?php
$currency = get_setting('currency', 'USD');
$currencySymbol = get_setting('currency_symbol', '$');
$siteTitle = get_setting('site_title', get_company_name());
$companyLogoUrl = get_company_logo();
$companyName = get_company_name();
?>
<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle) ?> - <?= htmlspecialchars($title ?? 'Dashboard') ?></title>

    <script>
        const SYSTEM_CURRENCY = <?= json_encode($currency) ?>;
        const SYSTEM_CURRENCY_SYMBOL = <?= json_encode($currencySymbol) ?>;
    </script>

    <!-- Tailwind CSS Console Warning Suppressor -->
    <script>
        (function() {
            const originalWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com')) {
                    return;
                }
                originalWarn.apply(console, args);
            };
        })();
    </script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block"
        rel="stylesheet">

    <!-- Font Awesome for fallback icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak], [v-cloak] {
            display: none !important;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F1F5F9;
            visibility: hidden;
            opacity: 0;
        }

        .card-elevation {
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Hide Font Awesome icons on desktop if using Material Icons */
        .show-fa {
            display: none;
        }

        /* Sidebar toggling animations & positioning */
        aside, main, header {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        @media (min-width: 768px) {
            body.sidebar-collapsed aside {
                transform: translateX(-260px) !important;
            }
            body.sidebar-collapsed main {
                margin-left: 0 !important;
            }
            body.sidebar-collapsed header {
                padding-left: 1.5rem !important;
            }
        }
    </style>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary-fixed-variant": "#444749",
                        "surface-container-low": "#eff4ff",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#dae2fd",
                        "outline-variant": "#c4c5d7",
                        "tertiary-fixed": "#e0e3e5",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#cbdbf5",
                        "surface-container-highest": "#d3e4fe",
                        "primary-container": "#1d4ed8",
                        "on-primary-container": "#cad3ff",
                        "inverse-on-surface": "#eaf1ff",
                        "inverse-surface": "#213145",
                        "on-surface": "#0b1c30",
                        "tertiary-container": "#595c5e",
                        "primary-fixed-dim": "#b7c4ff",
                        "primary": "#0037b0",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed": "#191c1e",
                        "on-primary-fixed": "#001551",
                        "surface-tint": "#2151da",
                        "inverse-primary": "#b7c4ff",
                        "surface-bright": "#f8f9ff",
                        "tertiary-fixed-dim": "#c4c7c9",
                        "secondary-container": "#dae2fd",
                        "error-container": "#ffdad6",
                        "surface-variant": "#d3e4fe",
                        "on-surface-variant": "#434655",
                        "on-primary": "#ffffff",
                        "surface": "#f8f9ff",
                        "surface-container": "#e5eeff",
                        "tertiary": "#414546",
                        "primary-fixed": "#dce1ff",
                        "surface-container-high": "#dce9ff",
                        "on-error-container": "#93000a",
                        "on-secondary-fixed": "#131b2e",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#0039b5",
                        "secondary": "#565e74",
                        "error": "#ba1a1a",
                        "background": "#f8f9ff",
                        "on-secondary-container": "#5c647a",
                        "on-tertiary-container": "#d2d4d6",
                        "secondary-fixed-dim": "#bec6e0",
                        "on-secondary-fixed-variant": "#3f465c",
                        "on-background": "#0b1c30",
                        "outline": "#747686"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "sidebar-width": "260px",
                        "container-max": "1440px",
                        xs: "4px",
                        md: "16px",
                        lg: "24px",
                        xl: "32px",
                        sm: "8px",
                        base: "4px"
                    },
                    fontFamily: {
                        "headline-md": ["Inter"],
                        "data-mono": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "display-lg": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    fontSize: {
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "data-mono": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "display-lg": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "label-md": ["12px", { "lineHeight": "16px", "fontWeight": "600" }]
                    }
                },
            },
        }
    </script>
</head>

<body class="bg-background text-on-surface">

    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 bottom-0 w-[260px] bg-slate-900 text-slate-300 flex flex-col z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 border-r border-slate-800">
        <!-- Logo Section -->
        <div class="h-14 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center gap-3">
                <?php if ($companyLogoUrl): ?>
                    <img src="<?= htmlspecialchars($companyLogoUrl) ?>" alt="Logo" class="h-8 max-w-[40px] object-contain rounded-lg bg-white p-1">
                <?php else: ?>
                    <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-md shadow-blue-500/20">
                        <?= htmlspecialchars(substr($companyName, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-white tracking-wide truncate max-w-[140px]"><?= htmlspecialchars($companyName) ?></span>
                    <span class="text-[9px] text-slate-500 font-semibold tracking-wider uppercase">Enterprise ERP</span>
                </div>
            </div>
            <button onclick="toggleDesktopSidebar()" class="hidden md:flex items-center justify-center p-1 rounded-md text-slate-400 hover:bg-slate-800 hover:text-white transition-colors" title="Collapse Sidebar">
                <span class="material-symbols-outlined text-lg">menu_open</span>
            </button>
        </div>

        <!-- Navigation Scrollable Area -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1.5 scrollbar-thin scrollbar-thumb-slate-800">
            <?php
            $current_url = $_GET['url'] ?? '';
            $active_module = explode('/', trim($current_url, '/'))[0] ?? 'dashboard';
            if ($active_module === '') {
                $active_module = 'dashboard';
            }
            ?>

            <!-- Dashboard Link -->
            <?php if (has_permission('dashboard.view')): ?>
            <a href="<?= BASE_URL ?>/dashboard" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= ($active_module === 'dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                <span class="material-symbols-outlined text-base">dashboard</span>
                <span>Dashboard Overview</span>
            </a>
            <?php endif; ?>

            <!-- Invoices Dropdown -->
            <?php if (has_permission('invoices.view')): ?>
            <?php $is_invoices = ($active_module === 'invoices'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuInvoices', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_invoices ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">receipt_long</span>
                        <span>Invoices & Billing</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_invoices ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuInvoices" class="<?= $is_invoices ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/invoices" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'invoices') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Invoices List</a>
                    <?php if (has_permission('invoices.create')): ?>
                    <a href="<?= BASE_URL ?>/invoices/create" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'invoices/create') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Create Invoice</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Customers Dropdown -->
            <?php if (has_permission('customers.manage') || has_permission('customers.view')): ?>
            <?php $is_customers = ($active_module === 'customers'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuCustomers', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_customers ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">group</span>
                        <span>Customers DB</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_customers ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuCustomers" class="<?= $is_customers ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/customers" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'customers') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Customers List</a>
                    <a href="<?= BASE_URL ?>/customers/create" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'customers/create') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Create Customer</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Vendors Dropdown -->
            <?php if (has_permission('vendors.manage') || has_permission('vendors.view')): ?>
            <?php $is_vendors = ($active_module === 'vendors'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuVendors', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_vendors ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">storefront</span>
                        <span>Vendors & Suppliers</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_vendors ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuVendors" class="<?= $is_vendors ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/vendors" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'vendors') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Vendors List</a>
                    <a href="<?= BASE_URL ?>/vendors/create" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'vendors/create') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Create Vendor</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Products Dropdown -->
            <?php if (has_permission('products.manage') || has_permission('products.view')): ?>
            <?php $is_products = ($active_module === 'products'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuProducts', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_products ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">inventory_2</span>
                        <span>Products & Inventory</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_products ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuProducts" class="<?= $is_products ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/products" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'products') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Products List</a>
                    <a href="<?= BASE_URL ?>/products/create" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'products/create') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Create Product</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Expenses Dropdown -->
            <?php if (has_permission('expenses.manage') || has_permission('expenses.view')): ?>
            <?php $is_expenses = ($active_module === 'expenses'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuExpenses', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_expenses ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">payments</span>
                        <span>Expenses Ledger</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_expenses ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuExpenses" class="<?= $is_expenses ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/expenses" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'expenses') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Expenses List</a>
                    <a href="<?= BASE_URL ?>/expenses/create" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'expenses/create') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Log Expense</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Banking Dropdown -->
            <?php if (has_permission('banking.manage') || has_permission('banking.view')): ?>
            <?php $is_banking = ($active_module === 'banking'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuBanking', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_banking ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">account_balance</span>
                        <span>Banking & Float</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_banking ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuBanking" class="<?= $is_banking ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/banking" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'banking') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Dashboard</a>
                    <a href="<?= BASE_URL ?>/banking/accounts" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'banking/accounts') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Bank Accounts</a>
                    <a href="<?= BASE_URL ?>/banking/transactions" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'banking/transactions') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Transactions Ledger</a>
                    <a href="<?= BASE_URL ?>/banking/transfers" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'banking/transfers') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Fund Transfers</a>
                    <a href="<?= BASE_URL ?>/banking/reconciliation" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'banking/reconciliation') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Reconciliation</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Reports Dropdown -->
            <?php if (has_permission('reports.view')): ?>
            <?php $is_reports = ($active_module === 'reports'); ?>
            <div class="space-y-1">
                <button onclick="toggleDropdown('menuReports', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_reports ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">assessment</span>
                        <span>Reports Dashboard</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_reports ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuReports" class="<?= $is_reports ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <a href="<?= BASE_URL ?>/reports" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Reports Home</a>
                    <a href="<?= BASE_URL ?>/reports/sales" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/sales') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Sales Report</a>
                    <a href="<?= BASE_URL ?>/reports/expenses" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/expenses') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Expense Report</a>
                    <a href="<?= BASE_URL ?>/reports/purchases" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/purchases') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Purchase Report</a>
                    <a href="<?= BASE_URL ?>/reports/profit-loss" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/profit-loss') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Profit & Loss</a>
                    <a href="<?= BASE_URL ?>/reports/tax" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/tax') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Tax Report</a>
                    <a href="<?= BASE_URL ?>/reports/inventory" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/inventory') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Inventory Report</a>
                    <a href="<?= BASE_URL ?>/reports/customers" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'reports/customers') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Customer Report</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Administration & Settings Dropdown -->
            <?php if (has_permission('settings.manage') || has_permission('users.manage') || has_permission('roles.manage')): ?>
            <?php $is_settings = ($active_module === 'settings' || $active_module === 'users' || $active_module === 'roles'); ?>
            <div class="space-y-1 border-t border-slate-800/80 pt-2 mt-2">
                <button onclick="toggleDropdown('menuSettings', this)" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all <?= $is_settings ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-base text-amber-400">admin_panel_settings</span>
                        <span>Administration</span>
                    </div>
                    <span class="material-symbols-outlined text-[10px] transition-transform duration-200 chevron-icon <?= $is_settings ? 'rotate-180' : '' ?>">expand_more</span>
                </button>
                <div id="menuSettings" class="<?= $is_settings ? '' : 'hidden' ?> pl-9 space-y-1.5 py-1">
                    <?php if (has_permission('users.manage') || has_permission('users.view')): ?>
                    <a href="<?= BASE_URL ?>/users" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'users') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">User Accounts</a>
                    <?php endif; ?>
                    
                    <?php if (has_permission('roles.manage')): ?>
                    <a href="<?= BASE_URL ?>/users/roles" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'users/roles' || $current_url === 'roles') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">Roles & Permissions</a>
                    <?php endif; ?>

                    <?php if (has_permission('settings.manage')): ?>
                    <a href="<?= BASE_URL ?>/settings" class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors <?= ($current_url === 'settings') ? 'text-blue-400 font-bold bg-blue-950/20' : 'text-slate-400 hover:text-slate-200' ?>">System Settings</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </nav>

        <!-- Sidebar Footer Sign out -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/20">
            <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs font-semibold text-red-400 hover:bg-red-950/20 hover:text-red-300 transition-colors">
                <span class="material-symbols-outlined text-base">logout</span>
                <span>Sign Out Account</span>
            </a>
        </div>
    </aside>

    <!-- Top Header -->
    <header class="fixed top-0 left-0 right-0 md:pl-[260px] h-14 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-6 z-40">
        <div class="flex items-center gap-3">
            <button id="mobileSidebarToggleBtn" onclick="toggleMobileSidebar()" class="md:hidden flex items-center justify-center p-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <button onclick="toggleDesktopSidebar()" class="hidden md:flex items-center justify-center p-1.5 rounded-lg text-slate-500 hover:bg-slate-100" title="Toggle Sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                <span>Enterprise Suite</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-semibold"><?= htmlspecialchars($title ?? 'Dashboard') ?> Overview</span>
            </div>
        </div>
        
        <!-- Right User Context -->
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/users/profile" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200" title="View Profile">
                <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                    <?= htmlspecialchars(strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'U', 0, 1))) ?>
                </div>
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-slate-800 leading-tight"><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User') ?></span>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider"><?= htmlspecialchars($_SESSION['role'] ?? 'employee') ?></span>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/logout" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Sign Out">
                <span class="material-symbols-outlined text-lg">logout</span>
            </a>
        </div>
    </header>

    <!-- Collapsible Dropdown Controller Javascript -->
    <script>
        function toggleDropdown(menuId, buttonEl) {
            const submenu = document.getElementById(menuId);
            const chevron = buttonEl.querySelector('.chevron-icon');
            if (submenu) {
                submenu.classList.toggle('hidden');
                if (chevron) {
                    chevron.classList.toggle('rotate-180');
                }
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.querySelector('aside');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        }

        function toggleDesktopSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
    </script>

    <!-- Main Content wrapper -->
    <main class="md:ml-[260px] p-6 max-w-[1440px] transition-all pt-20">