
<?php
// Get settings
$currency = get_setting('currency', 'USD');
$currencySymbol = get_setting('currency_symbol', '$');
$dueDays = (int)get_setting('invoice_due_days', 30);
$defaultTaxRate = (float)get_setting('tax_rate', 16);
$defaultTerms = get_setting('invoice_terms', '');
$defaultFooter = get_setting('invoice_footer', '');
?>

<div class="max-w-6xl mx-auto py-1">
    <!-- Title Section -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <span class="material-symbols-outlined text-xl">description</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Create New Invoice</h1>
                <p class="text-xs text-slate-500">Configure new billing transaction details and items.</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/invoices" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to List</span>
        </a>
    </div>

    <form id="invoiceForm" class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Left Column: Details & Items (Span 2) -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Invoice Info Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-2.5">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Invoice Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Customer Selection -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Customer *</label>
                        <div class="relative">
                            <select name="customer_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                                <option value="">Select Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>">
                                    <?= htmlspecialchars($customer['company_name']) ?> (<?= htmlspecialchars($customer['contact_person']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                    
                    <div></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Invoice Date -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Invoice Date *</label>
                        <div class="relative">
                            <input type="date" name="invoice_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <!-- Due Date -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Due Date *</label>
                        <div class="relative">
                            <input type="date" name="due_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d', strtotime("+{$dueDays} days")) ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Items Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Line Items</h2>
                    <button type="button" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition-all flex items-center gap-1" onclick="addItemRow()">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Add Item</span>
                    </button>
                </div>
                
                <div class="overflow-x-auto -mx-4">
                    <table class="w-full text-left text-sm border-collapse" id="itemsTable">
                        <thead>
                            <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                                <th class="px-6 py-1 pb-2 w-[30%]">Item</th>
                                <th class="px-4 py-1 pb-2 w-[22%]">Description</th>
                                <th class="px-4 py-1 pb-2 w-[12%] text-right">Quantity</th>
                                <th class="px-4 py-1 pb-2 w-[14%] text-right">Unit Price</th>
                                <th class="px-4 py-1 pb-2 w-[10%] text-right">Tax %</th>
                                <th class="px-4 py-1 pb-2 w-[10%] text-right">Total</th>
                                <th class="px-6 py-1 pb-2 w-[5%] text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-slate-100">
                            <!-- Items will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Notes Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-2">
                <label class="block text-xs font-semibold text-slate-600">Notes / Remarks</label>
                <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter invoice details, payment terms, or remarks..."></textarea>
            </div>
        </div>
        
        <!-- Right Column: Summary & Actions (Span 1) -->
        <div class="space-y-4">
            <!-- Invoice Settings/Status Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Invoice Status</h2>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                    </div>
                </div>
            </div>
            
            <!-- Summary Totals Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-4">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Billing Summary</h2>
                
                <div class="space-y-2.5 text-sm text-slate-600">
                    <!-- Subtotal -->
                    <div class="flex justify-between items-center">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-800"><?= $currency ?> <span id="subtotal">0.00</span></span>
                    </div>
                    
                    <!-- Discount Selection & Input -->
                    <div class="space-y-2 border-t border-slate-100 pt-2">
                        <div class="flex justify-between items-center">
                            <span>Discount</span>
                            <div class="flex gap-2 w-32">
                                <input type="number" name="discount_value" id="discount_value" 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0">
                                <div class="relative">
                                    <select name="discount_type" id="discount_type" class="bg-slate-50 border border-slate-200 rounded-lg py-1 pl-2 pr-6 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                                        <option value="percentage">%</option>
                                        <option value="fixed"><?= $currency ?></option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-1.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">expand_more</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tax Rate Selection -->
                    <div class="space-y-2 border-t border-slate-100 pt-2">
                        <div class="flex justify-between items-center gap-3">
                            <span>Tax</span>
                            <div class="relative w-44">
                                <select name="tax_id" id="tax_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-1 pl-2 pr-6 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                                    <?php foreach ($taxes as $tax): ?>
                                    <option value="<?= $tax['id'] ?>" data-rate="<?= $tax['tax_rate'] ?>">
                                        <?= htmlspecialchars($tax['tax_name']) ?> (<?= $tax['tax_rate'] ?>%)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="material-symbols-outlined absolute right-1.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">expand_more</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs opacity-75">
                            <span>Tax Amount</span>
                            <span><?= $currency ?> <span id="tax_amount">0.00</span></span>
                        </div>
                    </div>
                    
                    <!-- Total Amount -->
                    <div class="flex justify-between items-center border-t border-slate-100 pt-2 text-base font-bold text-slate-900">
                        <span>Total</span>
                        <span class="text-blue-600"><?= $currency ?> <span id="total_amount">0.00</span></span>
                    </div>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-2">
                <button type="submit" class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">save</span>
                    <span>Save Invoice</span>
                </button>
                <a href="<?= BASE_URL ?>/invoices" class="block w-full py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Hidden calculation inputs -->
        <input type="hidden" name="subtotal" id="input_subtotal">
        <input type="hidden" name="tax_amount" id="input_tax_amount">
        <input type="hidden" name="discount_amount" id="input_discount_amount">
        <input type="hidden" name="total_amount" id="input_total_amount">
        <input type="hidden" name="items" id="input_items">
    </form>
</div>

<!-- Item Template -->
<template id="itemTemplate">
    <tr class="align-middle group">
        <td class="px-4 py-2">
            <div class="relative">
                <select name="item_id[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none item-select" onchange="updateItemPrice(this)">
                    <option value="">Select Item</option>
                    <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>" 
                            data-price="<?= $product['sale_price'] ?>"
                            data-name="<?= htmlspecialchars($product['item_name']) ?>">
                        <?= htmlspecialchars($product['item_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm">expand_more</span>
            </div>
        </td>
        <td class="px-3 py-2">
            <input type="text" name="item_desc[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-description" placeholder="Description">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="item_qty[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-quantity" value="1" min="1" step="0.01" onchange="calculateTotals()">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="item_price[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-price" value="0" min="0" step="0.01" onchange="calculateTotals()">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="item_tax[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-tax" value="<?= $defaultTaxRate ?>" min="0" max="100" step="0.01" onchange="calculateTotals()">
        </td>
        <td class="px-3 py-2 text-right font-semibold text-slate-800 text-xs">
            <span class="item-total">0.00</span>
        </td>
        <td class="px-4 py-2 text-center">
            <button type="button" class="h-7 w-7 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors" onclick="removeItemRow(this)">
                <span class="material-symbols-outlined text-lg">delete</span>
            </button>
        </td>
    </tr>
</template>

<script>
let itemCount = 0;

function addItemRow() {
    const template = document.getElementById('itemTemplate');
    const clone = template.content.cloneNode(true);
    document.getElementById('itemsBody').appendChild(clone);
    itemCount++;
}

function removeItemRow(button) {
    button.closest('tr').remove();
    calculateTotals();
}

function updateItemPrice(select) {
    const option = select.options[select.selectedIndex];
    const price = option.dataset.price || 0;
    const name = option.dataset.name || '';
    
    const row = select.closest('tr');
    row.querySelector('.item-price').value = price;
    row.querySelector('.item-description').value = name;
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalTax = 0;
    
    const rows = document.querySelectorAll('#itemsBody tr');
    const items = [];
    
    rows.forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const taxRate = parseFloat(row.querySelector('.item-tax').value) || 0;
        
        const itemTotal = quantity * price;
        const itemTax = itemTotal * (taxRate / 100);
        
        subtotal += itemTotal;
        totalTax += itemTax;
        
        row.querySelector('.item-total').textContent = itemTotal.toFixed(2);
        
        // Collect item data
        const select = row.querySelector('.item-select');
        items.push({
            item_id: select.value || null,
            description: row.querySelector('.item-description').value,
            quantity: quantity,
            unit_price: price,
            tax_rate: taxRate,
            total: itemTotal
        });
    });
    
    // Calculate discount
    const discountType = document.getElementById('discount_type').value;
    const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
    let discountAmount = 0;
    
    if (discountType === 'percentage') {
        discountAmount = subtotal * (discountValue / 100);
    } else {
        discountAmount = discountValue;
    }
    
    const afterDiscount = subtotal - discountAmount;
    const taxSelect = document.getElementById('tax_id');
    const selectedOption = taxSelect ? taxSelect.selectedOptions[0] : null;
    const taxRate = selectedOption ? (parseFloat(selectedOption.dataset.rate) || 0) : 0;
    const taxAmount = afterDiscount * (taxRate / 100);
    const total = afterDiscount + taxAmount;
    
    // Update display
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax_amount').textContent = taxAmount.toFixed(2);
    document.getElementById('total_amount').textContent = total.toFixed(2);
    
    // Update hidden inputs
    document.getElementById('input_subtotal').value = subtotal;
    document.getElementById('input_tax_amount').value = taxAmount;
    document.getElementById('input_discount_amount').value = discountAmount;
    document.getElementById('input_total_amount').value = total;
    document.getElementById('input_items').value = JSON.stringify(items);
}

// Event listeners
document.getElementById('discount_value').addEventListener('input', calculateTotals);
document.getElementById('discount_type').addEventListener('change', calculateTotals);
document.getElementById('tax_id').addEventListener('change', calculateTotals);

// Form submission
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= BASE_URL ?>/invoices/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= BASE_URL ?>/invoices/show/' + data.id;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error saving invoice');
        console.error(error);
    });
});

// Add first row on load
addItemRow();
</script>