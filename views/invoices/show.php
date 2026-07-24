<?php
if (!$invoice) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200'>Invoice not found</div>";
    return;
}

// Load settings via global setting helper
$company_name = get_setting('company_name', 'My Business ERP');
$company_email = get_setting('company_email', 'info@mybusiness.com');
$company_phone = get_setting('company_phone', '+1 555-0199');
$company_address = get_setting('company_address', '123 Enterprise Way, Tech City');
$company_logo = get_company_logo();

$symbol = get_setting('currency_symbol', get_setting('currency', 'USD'));

// Calculate payment terms difference
$terms_days = 30;
if (!empty($invoice['invoice_date']) && !empty($invoice['due_date'])) {
    $date_diff = strtotime($invoice['due_date']) - strtotime($invoice['invoice_date']);
    $terms_days = round($date_diff / (60 * 60 * 24));
}
?>

<style>
@media print {
    /* Hide layout elements not part of the invoice form */
    aside, header, #mobileSidebarToggle, #sidebarOverlay, .print\:hidden {
        display: none !important;
    }
    
    /* Reset main layout margins and padding for full-page print */
    main {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
}
</style>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Actions (Hidden when printing) -->
    <div class="flex justify-between items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm print:hidden">
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/invoices" class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-900">Invoice Preview</h1>
                <p class="text-xs text-slate-500">Print or edit this invoice</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print Invoice</span>
            </button>
            <a href="<?= BASE_URL ?>/invoices/edit/<?= $invoice['id'] ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit Invoice</span>
            </a>
        </div>
    </div>

    <!-- Printable Invoice Page -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 md:p-12 space-y-10 print:border-none print:shadow-none print:p-0">
        
        <!-- Header Banner -->
        <div class="bg-teal-600 text-white px-6 py-4 flex justify-between items-center rounded-sm">
            <h2 class="text-xl font-bold tracking-wider uppercase">Invoice</h2>
            <div class="text-lg font-bold"># <?= htmlspecialchars($invoice['invoice_number']) ?></div>
        </div>

        <!-- From / Bill To Grid -->
        <div class="grid grid-cols-2 gap-12 text-sm">
            <!-- FROM column -->
            <div class="space-y-2">
                <?php if ($company_logo): ?>
                    <img src="<?= htmlspecialchars($company_logo) ?>" alt="Logo" class="h-12 object-contain mb-2">
                <?php endif; ?>
                <h3 class="text-xs font-bold text-teal-600 uppercase tracking-widest">From</h3>
                <div class="space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-base"><?= htmlspecialchars($company_name) ?></div>
                    <div><?= htmlspecialchars($company_address) ?></div>
                    <div class="text-slate-500"><?= htmlspecialchars($company_email) ?> | <?= htmlspecialchars($company_phone) ?></div>
                </div>
            </div>

            <!-- BILL TO column -->
            <div class="space-y-2">
                <h3 class="text-xs font-bold text-teal-600 uppercase tracking-widest">Bill To</h3>
                <div class="space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-base"><?= htmlspecialchars($invoice['company_name'] ?? 'Client Name / Company') ?></div>
                    <div><?= htmlspecialchars($invoice['contact_person'] ?? '') ?></div>
                    <div><?= htmlspecialchars($invoice['address'] ?? 'Client Street Address') ?></div>
                    <div class="text-slate-500"><?= htmlspecialchars($invoice['email'] ?? 'client@company.com') ?></div>
                </div>
            </div>
        </div>

        <!-- Dates Timeline Info -->
        <div class="flex justify-between items-center text-sm border-t border-b border-slate-100 py-3 text-slate-600">
            <div>
                <span>Invoice Date:</span>
                <span class="font-bold text-slate-900 ml-2"><?= date('m/d/Y', strtotime($invoice['invoice_date'])) ?></span>
            </div>
            <div>
                <span>Due Date:</span>
                <span class="font-bold text-slate-900 ml-2"><?= date('m/d/Y', strtotime($invoice['due_date'])) ?></span>
            </div>
            <div class="text-teal-600 font-bold tracking-wide">
                Net <?= $terms_days ?>
            </div>
        </div>

        <!-- Invoice Details Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-slate-900 font-bold border-t-2 border-b-2 border-teal-600 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-1.5 px-2">Item</th>
                        <th class="py-1.5 px-2">Description</th>
                        <th class="py-1.5 px-2 text-right w-24">Quantity</th>
                        <th class="py-1.5 px-2 text-right w-28">Unit Price</th>
                        <th class="py-1.5 px-2 text-right w-20">Tax</th>
                        <th class="py-1.5 px-2 text-right w-32">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="py-1.5 px-2 font-bold text-slate-950"><?= htmlspecialchars($item['item_name'] ?? 'Custom Item') ?></td>
                        <td class="py-1.5 px-2 text-slate-500"><?= htmlspecialchars($item['description'] ?? '') ?></td>
                        <td class="py-1.5 px-2 text-right text-slate-700 font-medium"><?= number_format($item['quantity'], 2) ?></td>
                        <td class="py-1.5 px-2 text-right text-slate-700 font-medium"><?= $symbol ?> <?= number_format($item['unit_price'], 2) ?></td>
                        <td class="py-1.5 px-2 text-right text-slate-500"><?= $item['tax_rate'] ?? 0 ?>%</td>
                        <td class="py-1.5 px-2 text-right text-slate-950 font-bold"><?= $symbol ?> <?= number_format($item['total'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="flex flex-col md:flex-row md:justify-between items-start md:items-start gap-8 pt-4">
            <!-- Left Side: Invoice Notes -->
            <div class="space-y-3 w-full md:w-1/2">
                <?php if (!empty($invoice['notes'])): ?>
                    <h3 class="text-xs font-bold text-teal-600 uppercase tracking-widest">Invoice Notes</h3>
                    <div class="p-4 bg-amber-50/50 border border-amber-100 rounded-xl text-xs text-amber-900/80 leading-relaxed">
                        <?= nl2br(htmlspecialchars($invoice['notes'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Side: Calculations -->
            <div class="space-y-4 w-full md:w-80 text-sm text-slate-600 ml-auto">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-bold text-slate-900"><?= $symbol ?> <?= number_format($invoice['subtotal'], 2) ?></span>
                    </div>
                    <?php if ($invoice['discount_amount'] > 0): ?>
                    <div class="flex justify-between text-red-600">
                        <span>Discount:</span>
                        <span class="font-bold">-<?= $symbol ?> <?= number_format($invoice['discount_amount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php 
                    $tax_rate = 0;
                    $taxable_amount = $invoice['subtotal'] - $invoice['discount_amount'];
                    if ($taxable_amount > 0 && $invoice['tax_amount'] > 0) {
                        $tax_rate = round(($invoice['tax_amount'] / $taxable_amount) * 100, 2);
                    }
                    $balance = $invoice['total_amount'] - $invoice['paid_amount'];
                    ?>
                    <div class="flex justify-between">
                        <span>Tax (<?= $tax_rate ?>%):</span>
                        <span class="font-bold text-slate-900"><?= $symbol ?> <?= number_format($invoice['tax_amount'], 2) ?></span>
                    </div>
                </div>

                <!-- Total Banner Block -->
                <div class="bg-teal-600 text-white px-4 py-2.5 flex justify-between items-center rounded-sm font-bold text-base shadow-sm">
                    <span>TOTAL</span>
                    <span><?= $symbol ?> <?= number_format($invoice['total_amount'], 2) ?></span>
                </div>

                <?php if ($invoice['paid_amount'] > 0): ?>
                <div class="flex justify-between text-xs text-green-600 font-semibold px-2">
                    <span>Amount Paid:</span>
                    <span><?= $symbol ?> <?= number_format($invoice['paid_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between text-xs text-amber-600 font-bold px-2 pt-1 border-t border-slate-100">
                    <span>Balance Due:</span>
                    <span><?= $symbol ?> <?= number_format($balance, 2) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payment Methods & Thank You Footer -->
        <div class="border-t border-slate-100 pt-6 space-y-6">
            <div class="space-y-2">
                <h3 class="text-xs font-bold text-teal-600 uppercase tracking-widest">Payment Methods</h3>
                <p class="text-xs text-slate-500 font-medium">Bank Transfer | PayPal: <span class="text-slate-800"><?= htmlspecialchars($company_email) ?></span> | Venmo: <span class="text-slate-800">@yourhandle</span></p>
            </div>
            
            <div class="text-teal-600 font-bold text-sm">
                Thank you for your business!
            </div>
        </div>

    </div>

    <!-- Payments history block (Only for application usage, hidden when printing) -->
    <?php if (!empty($payments)): ?>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4 print:hidden">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Recorded Payments History</h3>
            <p class="text-xs text-slate-500">Transaction logs associated with this invoice</p>
        </div>
        <div class="border border-slate-100 rounded-xl overflow-hidden">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3">Payment Date</th>
                        <th class="px-5 py-3 text-right">Amount</th>
                        <th class="px-5 py-3">Method</th>
                        <th class="px-5 py-3">Reference Number</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($payments as $payment): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 text-slate-600 font-medium"><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                        <td class="px-5 py-3 text-right text-green-600 font-bold"><?= $symbol ?> <?= number_format($payment['amount'], 2) ?></td>
                        <td class="px-5 py-3 text-slate-500"><?= htmlspecialchars($payment['method_name'] ?? $payment['payment_method'] ?? 'N/A') ?></td>
                        <td class="px-5 py-3 text-slate-500"><?= htmlspecialchars($payment['reference_number'] ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>