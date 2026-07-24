<?php if (!$customer): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Customer not found</div>
    <?php return; ?>
<?php endif; ?>

<?php
// Load settings for defaults
$settings = [];
try {
    $db = getConnection();
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (Exception $e) {
    // fallback
}

$currency_code = strtoupper($currency ?? 'USD');
?>

<div class="space-y-4">
    <!-- Header Block -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-start gap-3">
            <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0 mt-1">
                <span class="material-symbols-outlined text-xl">person</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($customer['company_name'] ?? '') ?></h1>
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2 py-0.5 rounded">
                        <?= htmlspecialchars($customer['customer_code'] ?? ('CUST-' . $customer['id'])) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Contact: <span class="font-medium text-slate-700"><?= htmlspecialchars($customer['contact_person'] ?? 'N/A') ?></span>
                    <?php if (!empty($customer['email'])): ?>
                        | <a href="mailto:<?= htmlspecialchars($customer['email']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($customer['email']) ?></a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 self-stretch md:self-auto">
            <a href="<?= BASE_URL ?>/customers" class="flex-1 md:flex-none px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back</span>
            </a>
            <a href="<?= BASE_URL ?>/customers/edit/<?= $customer['id'] ?>" class="flex-1 md:flex-none px-3 py-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit</span>
            </a>
            <a href="<?= BASE_URL ?>/invoices/create?customer_id=<?= $customer['id'] ?>" class="flex-1 md:flex-none px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-semibold text-center transition-all inline-flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>New Invoice</span>
            </a>
        </div>
    </div>

    <!-- Customer Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Invoices -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">receipt</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Invoices</p>
                <h3 class="text-lg font-bold text-slate-900"><?= number_format($customer['total_invoices'] ?? 0) ?></h3>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">payments</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Revenue</p>
                <h3 class="text-lg font-bold text-green-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($customer['total_revenue'] ?? 0, 2) ?></h3>
            </div>
        </div>

        <!-- Paid -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Paid</p>
                <h3 class="text-lg font-bold text-teal-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($customer['total_paid'] ?? 0, 2) ?></h3>
            </div>
        </div>

        <!-- Outstanding -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-9 w-9 rounded-md bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">pending_actions</span>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Outstanding Balance</p>
                <h3 class="text-lg font-bold text-red-500"><?= htmlspecialchars($currency_code) ?> <?= number_format($customer['outstanding_balance'] ?? 0, 2) ?></h3>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- Left: Customer Information & Notes -->
        <div class="lg:col-span-1 space-y-4">
            
            <!-- Information Block -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Customer Details</h5>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= ($customer['status'] ?? 0) == 1 ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                        <?= ($customer['status'] ?? 0) == 1 ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="p-4">
                    <table class="w-full text-xs divide-y divide-slate-100">
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Contact Person:</td>
                            <td class="text-right text-slate-900 font-medium"><?= htmlspecialchars($customer['contact_person'] ?? 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Phone:</td>
                            <td class="text-right text-slate-900 font-medium"><?= htmlspecialchars($customer['phone'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Mobile:</td>
                            <td class="text-right text-slate-900 font-medium"><?= htmlspecialchars($customer['mobile'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Website:</td>
                            <td class="text-right text-slate-900 font-medium">
                                <?php if (!empty($customer['website'])): ?>
                                    <a href="<?= htmlspecialchars($customer['website']) ?>" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-0.5">
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
                                <?php if (!empty($customer['address']) || !empty($customer['city'])): ?>
                                    <div class="font-medium text-slate-900"><?= htmlspecialchars($customer['address'] ?? '') ?></div>
                                    <div><?= htmlspecialchars($customer['city'] ?? '') ?>, <?= htmlspecialchars($customer['state'] ?? '') ?> <?= htmlspecialchars($customer['postal_code'] ?? '') ?></div>
                                    <div class="text-[10px] text-slate-400 font-semibold uppercase"><?= htmlspecialchars($customer['country'] ?? '') ?></div>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Tax/VAT ID:</td>
                            <td class="text-right text-slate-900 font-medium"><?= htmlspecialchars($customer['tax_number'] ?: 'N/A') ?></td>
                        </tr>
                        <tr class="py-2 flex justify-between items-start gap-4">
                            <td class="font-medium text-slate-500 w-1/3">Customer Since:</td>
                            <td class="text-right text-slate-900 font-medium"><?= !empty($customer['customer_since']) ? date('d/m/Y', strtotime($customer['customer_since'])) : '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Customer Notes</h5>
                </div>
                <div class="p-4 space-y-4">
                    <form id="noteForm" class="space-y-2">
                        <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                        <textarea name="note" class="w-full bg-slate-50 border border-slate-200 rounded-md p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none" rows="2" placeholder="Add a log entry or note..." required></textarea>
                        <button type="submit" class="w-full py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-md text-xs font-semibold transition-all">Add Note</button>
                    </form>
                    
                    <div class="divide-y divide-slate-100 max-h-60 overflow-y-auto pr-1 space-y-2 custom-scrollbar">
                        <?php foreach ($notes as $note): ?>
                        <div class="pt-2 pb-1 text-xs">
                            <div class="flex justify-between items-center text-[10px] text-slate-400 mb-1">
                                <span class="font-semibold text-slate-500"><?= htmlspecialchars($note['username']) ?></span>
                                <span><?= date('d/m/Y H:i', strtotime($note['created_at'])) ?></span>
                            </div>
                            <p class="text-slate-700 leading-relaxed"><?= nl2br(htmlspecialchars($note['note'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($notes)): ?>
                            <p class="text-center text-slate-400 py-4 text-xs">No notes logged yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Tabbed Documentation and Tables -->
        <div class="lg:col-span-2 space-y-4">
            
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-200 bg-slate-50/50">
                    <button class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-primary text-primary transition-all focus:outline-none" data-tab="invoices">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg">receipt_long</span>
                            <span>Invoices</span>
                        </span>
                    </button>
                    <button class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-all focus:outline-none" data-tab="payments">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
                            <span>Payments</span>
                        </span>
                    </button>
                    <button class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-all focus:outline-none" data-tab="activities">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg">history</span>
                            <span>Activities</span>
                        </span>
                    </button>
                </div>

                <!-- Tab Panes -->
                <div class="p-0">
                    
                    <!-- Invoices Pane -->
                    <div id="invoices" class="tab-pane">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2">Invoice #</th>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Due Date</th>
                                        <th class="px-4 py-2 text-right">Amount</th>
                                        <th class="px-4 py-2 text-right">Paid</th>
                                        <th class="px-4 py-2 text-right">Balance</th>
                                        <th class="px-4 py-2 text-center">Status</th>
                                        <th class="px-4 py-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach ($invoices as $inv): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-2">
                                            <a href="<?= BASE_URL ?>/invoices/show/<?= $inv['id'] ?>" class="font-semibold text-primary hover:underline">
                                                <?= htmlspecialchars($inv['invoice_number']) ?>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-slate-600"><?= date('d/m/Y', strtotime($inv['invoice_date'])) ?></td>
                                        <td class="px-4 py-2 text-slate-600"><?= date('d/m/Y', strtotime($inv['due_date'])) ?></td>
                                        <td class="px-4 py-2 text-right font-medium text-slate-900"><?= htmlspecialchars($currency_code) ?> <?= number_format($inv['total_amount'], 2) ?></td>
                                        <td class="px-4 py-2 text-right font-medium text-green-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($inv['paid_amount'], 2) ?></td>
                                        <td class="px-4 py-2 text-right font-medium <?= $inv['balance'] > 0 ? 'text-red-500' : 'text-slate-500' ?>">
                                            <?= htmlspecialchars($currency_code) ?> <?= number_format($inv['balance'], 2) ?>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <?php
                                            $status_colors = [
                                                'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                'sent' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                'paid' => 'bg-green-50 text-green-600 border-green-200',
                                                'overdue' => 'bg-red-50 text-red-600 border-red-200'
                                            ];
                                            $color_class = $status_colors[$inv['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                            ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                                                <?= ucfirst($inv['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="<?= BASE_URL ?>/invoices/show/<?= $inv['id'] ?>" class="h-6 w-6 rounded border border-slate-200 text-slate-500 inline-flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View Invoice">
                                                <span class="material-symbols-outlined text-xs">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($invoices)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-slate-400 py-6">No invoices created yet.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payments Pane -->
                    <div id="payments" class="tab-pane hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Invoice #</th>
                                        <th class="px-4 py-2 text-right">Amount</th>
                                        <th class="px-4 py-2">Method</th>
                                        <th class="px-4 py-2">Reference</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach ($payments as $payment): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-2 text-slate-600"><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                                        <td class="px-4 py-2">
                                            <a href="<?= BASE_URL ?>/invoices/show/<?= $payment['invoice_id'] ?>" class="font-semibold text-primary hover:underline">
                                                <?= htmlspecialchars($payment['invoice_number']) ?>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-right font-medium text-slate-900"><?= htmlspecialchars($currency_code) ?> <?= number_format($payment['amount'], 2) ?></td>
                                        <td class="px-4 py-2 text-slate-700"><?= htmlspecialchars($payment['method_name'] ?? 'N/A') ?></td>
                                        <td class="px-4 py-2 text-slate-500 font-mono"><?= htmlspecialchars($payment['reference_number'] ?: '-') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($payments)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-400 py-6">No payments received yet.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Activities Pane -->
                    <div id="activities" class="tab-pane hidden">
                        <div class="p-6">
                            <div class="relative border-l border-slate-200 pl-6 space-y-6">
                                <?php foreach ($activities as $activity): ?>
                                <div class="relative">
                                    <!-- Bullet node -->
                                    <span class="absolute -left-[30px] top-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-blue-500 ring-4 ring-blue-50 flex items-center justify-center"></span>
                                    
                                    <div class="text-xs">
                                        <div class="flex items-center gap-2 text-slate-400 mb-1">
                                            <span class="font-bold text-slate-800"><?= htmlspecialchars($activity['username']) ?></span>
                                            <span>•</span>
                                            <span><?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?></span>
                                        </div>
                                        <p class="text-slate-600 leading-relaxed"><?= htmlspecialchars($activity['description']) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($activities)): ?>
                                    <p class="text-center text-slate-400 py-4 text-xs">No logged activities found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tab switching logic
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            
            // Remove active classes, add inactive classes to all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700');
            });
            
            // Add active classes, remove inactive classes from clicked button
            this.classList.add('border-primary', 'text-primary');
            this.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700');
            
            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
            });
            
            // Show the target tab pane
            const targetPane = document.getElementById(target);
            if (targetPane) {
                targetPane.classList.remove('hidden');
            }
        });
    });

    // Notes form submission
    const noteForm = document.getElementById('noteForm');
    if (noteForm) {
        noteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= BASE_URL ?>/customers/addNote/<?= $customer['id'] ?>', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error adding note:', error);
                alert('An error occurred while adding the note.');
            });
        });
    }
});
</script>