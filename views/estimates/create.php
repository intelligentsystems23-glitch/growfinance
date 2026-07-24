<?php $hide_title = true; ?>

<div class="max-w-5xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Create New Estimate</h1>
            <p class="text-xs text-slate-500">Draft a new proposal or quote for a customer.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/estimates" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-5xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="estimateForm" class="space-y-4">
        <!-- Top fields -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Customer -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Customer *</label>
                <div class="relative">
                    <select name="customer_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- Estimate Date -->
            <div class="space-y-1.5 md:col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Estimate Date *</label>
                <input type="date" name="estimate_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>" required>
            </div>
            <!-- Expiry Date -->
            <div class="space-y-1.5 md:col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
            </div>
        </div>
        
        <h6 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 pt-2">Items</h6>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse" id="itemsTable">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-2">Item</th>
                        <th class="pb-2">Description</th>
                        <th class="pb-2 text-right" style="width: 100px;">Qty</th>
                        <th class="pb-2 text-right" style="width: 120px;">Price</th>
                        <th class="pb-2 text-right" style="width: 120px;">Total</th>
                        <th class="pb-2 text-center" style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody" class="divide-y divide-slate-100"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="pt-3">
                            <button type="button" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition-all inline-flex items-center gap-1" onclick="addItemRow()">
                                <span class="material-symbols-outlined text-sm">add</span>
                                <span>Add Item</span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Bottom sections: Notes & Terms vs Subtotal table -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4">
            <!-- Left: Notes & Terms -->
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Notes</label>
                    <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2" placeholder="Enter notes..."></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Terms</label>
                    <textarea name="terms" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2">Valid for 30 days</textarea>
                </div>
            </div>
            
            <!-- Right: Subtotal Table -->
            <div class="flex flex-col justify-end">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-100">
                        <tr class="text-slate-650">
                            <th class="py-2 text-left font-semibold">Subtotal</th>
                            <td class="py-2 text-right">$<span id="subtotal">0.00</span></td>
                        </tr>
                        <tr class="text-slate-650">
                            <th class="py-2 text-left font-semibold">Tax (10%)</th>
                            <td class="py-2 text-right">$<span id="tax_amount">0.00</span></td>
                        </tr>
                        <tr class="text-slate-900 font-bold text-base">
                            <th class="py-2 text-left">Total</th>
                            <td class="py-2 text-right text-blue-600">$<span id="total_amount">0.00</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <input type="hidden" name="subtotal" id="input_subtotal">
        <input type="hidden" name="tax_amount" id="input_tax_amount">
        <input type="hidden" name="total_amount" id="input_total_amount">
        <input type="hidden" name="items" id="input_items">
        <input type="hidden" name="status" value="draft">
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Save Estimate</span>
            </button>
            <a href="<?= BASE_URL ?>/estimates" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<template id="itemTemplate">
    <tr class="align-middle">
        <td class="py-2 pr-2">
            <div class="relative">
                <select name="item_id[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none item-select" onchange="updateItemPrice(this)">
                    <option value="">Select Item</option>
                    <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id'] ?>" data-price="<?= $p['sale_price'] ?>">
                        <?= htmlspecialchars($p['item_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm">expand_more</span>
            </div>
        </td>
        <td class="py-2 px-2"><input type="text" name="item_desc[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-description" placeholder="Description"></td>
        <td class="py-2 px-2"><input type="number" name="item_qty[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-quantity" value="1" min="1" step="0.01" onchange="calculateTotals()"></td>
        <td class="py-2 px-2"><input type="number" name="item_price[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-xs text-right text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-price" value="0" step="0.01" onchange="calculateTotals()"></td>
        <td class="py-2 px-2 text-right text-slate-800 text-xs font-semibold"><span class="item-total">0.00</span></td>
        <td class="py-2 pl-2 text-center">
            <button type="button" class="h-7 w-7 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors" onclick="this.closest('tr').remove(); calculateTotals();">
                <span class="material-symbols-outlined text-base">delete</span>
            </button>
        </td>
    </tr>
</template>

<script>
function addItemRow() {
    const template = document.getElementById('itemTemplate');
    const clone = template.content.cloneNode(true);
    document.getElementById('itemsBody').appendChild(clone);
}

function updateItemPrice(select) {
    const option = select.options[select.selectedIndex];
    const row = select.closest('tr');
    row.querySelector('.item-price').value = option.dataset.price || 0;
    row.querySelector('.item-description').value = option.text;
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    const items = [];
    
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = qty * price;
        subtotal += total;
        row.querySelector('.item-total').textContent = total.toFixed(2);
        
        const select = row.querySelector('.item-select');
        items.push({
            item_id: select.value || null,
            description: row.querySelector('.item-description').value,
            quantity: qty,
            unit_price: price,
            tax_rate: 0,
            total: total
        });
    });
    
    const tax = subtotal * 0.10;
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax_amount').textContent = tax.toFixed(2);
    document.getElementById('total_amount').textContent = total.toFixed(2);
    
    document.getElementById('input_subtotal').value = subtotal;
    document.getElementById('input_tax_amount').value = tax;
    document.getElementById('input_total_amount').value = total;
    document.getElementById('input_items').value = JSON.stringify(items);
}

document.getElementById('estimateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= BASE_URL ?>/estimates/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log('Response:', text);
        try {
            const data = JSON.parse(text);
            if (data.success) {
                window.location.href = '<?= BASE_URL ?>/estimates/view/' + data.id;
            } else {
                alert('Error: ' + data.message);
            }
        } catch (e) {
            console.error('Parse error:', e);
            alert('Server error. Check console for details.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save estimate.');
    });
});

// Add first row
addItemRow();
</script>