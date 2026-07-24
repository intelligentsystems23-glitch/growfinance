<?php if (!$vendor): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Vendor not found</div>
    <?php return; ?>
<?php endif; ?>

<?php
$currency_code = strtoupper($currency ?? 'USD');
$outstanding = ($vendor['total_purchases'] ?? 0) - ($vendor['total_paid'] ?? 0);
?>

<div class="space-y-4">
    <!-- Header Block -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-start gap-3">
            <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0 mt-1">
                <span class="material-symbols-outlined text-xl">storefront</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($vendor['company_name'] ?? '') ?></h1>
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2 py-0.5 rounded">
                        <?= htmlspecialchars($vendor['vendor_code'] ?? ('VEND-' . $vendor['id'])) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Contact: <span class="font-medium text-slate-700"><?= htmlspecialchars($vendor['contact_person'] ?? 'N/A') ?></span>
                    <?php if (!empty($vendor['email'])): ?>
                        | <a href="mailto:<?= htmlspecialchars($vendor['email']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($vendor['email']) ?></a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 self-stretch md:self-auto">
            <a href="<?= BASE_URL ?>/vendors" class="flex-1 md:flex-none px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back</span>
            </a>
            <a href="<?= BASE_URL ?>/vendors/edit/<?= $vendor['id'] ?>" class="flex-1 md:flex-none px-3 py-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit</span>
            </a>
            <a href="<?= BASE_URL ?>/purchase-orders/create?vendor_id=<?= $vendor['id'] ?>" class="flex-1 md:flex-none px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>New PO</span>
            </a>
        </div>
    </div>

    <!-- Vendor Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- POs -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">shopping_cart</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total POs</p>
                <h3 class="text-lg font-bold text-slate-900"><?= number_format($vendor['total_pos'] ?? 0) ?></h3>
            </div>
        </div>

        <!-- Purchases -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">inventory</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Purchases</p>
                <h3 class="text-lg font-bold text-green-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($vendor['total_purchases'] ?? 0, 2) ?></h3>
            </div>
        </div>

        <!-- Paid -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Paid</p>
                <h3 class="text-lg font-bold text-teal-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($vendor['total_paid'] ?? 0, 2) ?></h3>
            </div>
        </div>

        <!-- Outstanding -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">pending_actions</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Outstanding Payables</p>
                <h3 class="text-lg font-bold text-red-500"><?= htmlspecialchars($currency_code) ?> <?= number_format($outstanding, 2) ?></h3>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- Left: Vendor Profile & Banking Information -->
        <div class="lg:col-span-1 space-y-4">
            
            <!-- Details Card -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Vendor details</h5>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= ($vendor['status'] ?? 0) == 1 ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                        <?= ($vendor['status'] ?? 0) == 1 ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="p-4">
                    <table class="w-full text-xs divide-y divide-slate-100">
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Category:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['category_name'] ?? 'Uncategorized') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Phone:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['phone'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Mobile:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['mobile'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Website:</td>
                            <td class="text-right text-slate-900 font-semibold">
                                <?php if (!empty($vendor['website'])): ?>
                                    <a href="<?= htmlspecialchars($vendor['website']) ?>" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-0.5">
                                        <span>Visit Website</span>
                                        <span class="material-symbols-outlined text-[10px]">open_in_new</span>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Address:</td>
                            <td class="text-right text-slate-700">
                                <?php if (!empty($vendor['address']) || !empty($vendor['city'])): ?>
                                    <div class="font-medium text-slate-900"><?= htmlspecialchars($vendor['address'] ?? '') ?></div>
                                    <div class="text-slate-500"><?= htmlspecialchars($vendor['city'] ?? '') ?>, <?= htmlspecialchars($vendor['state'] ?? '') ?> <?= htmlspecialchars($vendor['postal_code'] ?? '') ?></div>
                                    <div class="text-slate-500"><?= htmlspecialchars($vendor['country'] ?? '') ?></div>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Tax ID:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['tax_id'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Payment Terms:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['payment_terms'] ?: 'Net 30') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Credit Limit:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($vendor['credit_limit'] ?? 0, 2) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Banking Block -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Banking Details</h5>
                </div>
                <div class="p-4">
                    <table class="w-full text-xs divide-y divide-slate-100">
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Bank Name:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['bank_name'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Account Number:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['bank_account_number'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Routing Number:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($vendor['bank_routing_number'] ?: 'N/A') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right: Tables (POs, Payments) -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Purchase Orders Card -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Purchase Orders</h5>
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-100">POs list</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2">PO Number</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2 text-right">Amount</th>
                                <th class="px-4 py-2 text-center">Status</th>
                                <th class="px-4 py-2 text-center">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($purchase_orders)): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">No purchase orders found for this vendor.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($purchase_orders as $po): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-2.5 font-semibold text-blue-600">
                                            <a href="<?= BASE_URL ?>/purchase-orders/show/<?= $po['id'] ?>" class="hover:underline">
                                                <?= htmlspecialchars($po['po_number'] ?? ('#' . $po['id'])) ?>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2.5 text-slate-600"><?= date('d/m/Y', strtotime($po['po_date'])) ?></td>
                                        <td class="px-4 py-2.5 text-right font-medium text-slate-900"><?= htmlspecialchars($currency_code) ?> <?= number_format($po['total_amount'], 2) ?></td>
                                        <td class="px-4 py-2.5 text-center">
                                            <?php
                                            $status_colors = [
                                                'draft' => 'bg-slate-50 text-slate-600 border-slate-200',
                                                'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'received' => 'bg-green-50 text-green-700 border-green-200',
                                                'cancelled' => 'bg-red-50 text-red-700 border-red-200'
                                            ];
                                            $color = $status_colors[$po['status']] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                            ?>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color ?>">
                                                <?= ucfirst($po['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
                                            <?php
                                            $pay_colors = [
                                                'unpaid' => 'bg-red-50 text-red-600 border-red-200',
                                                'partial' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'paid' => 'bg-green-50 text-green-600 border-green-200'
                                            ];
                                            $pay_color = $pay_colors[$po['payment_status']] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                            ?>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold border <?= $pay_color ?>">
                                                <?= ucfirst($po['payment_status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ledger Transactions Card -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Ledger Transactions</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Type</th>
                                <th class="px-4 py-2">Reference</th>
                                <th class="px-4 py-2 text-right">Debit</th>
                                <th class="px-4 py-2 text-right">Credit</th>
                                <th class="px-4 py-2 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">No transaction history found for this vendor.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $txn): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-2.5 text-slate-600"><?= date('d/m/Y', strtotime($txn['transaction_date'])) ?></td>
                                        <td class="px-4 py-2.5 font-medium <?= $txn['transaction_type'] === 'purchase' ? 'text-blue-700' : 'text-green-700' ?>">
                                            <?= ucfirst($txn['transaction_type']) ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-slate-600 font-medium"><?= htmlspecialchars($txn['reference_no'] ?: '-') ?></td>
                                        <td class="px-4 py-2.5 text-right text-slate-900 font-medium">
                                            <?= $txn['transaction_type'] === 'purchase' ? (htmlspecialchars($currency_code) . ' ' . number_format($txn['amount'], 2)) : '-' ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-right text-green-600 font-medium">
                                            <?= $txn['transaction_type'] === 'payment' ? (htmlspecialchars($currency_code) . ' ' . number_format($txn['amount'], 2)) : '-' ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-slate-950">
                                            <?= htmlspecialchars($currency_code) ?> <?= number_format($txn['balance'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
