<?php if (!$expense): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Expense not found</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-3xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">edit_note</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Edit Expense' ?></h1>
            <p class="text-xs text-slate-500">Update expense and payout details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/expenses" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-3xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="expenseForm" class="space-y-4">
        <input type="hidden" name="id" value="<?= $expense['id'] ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Expense Date -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Expense Date *</label>
                <input type="date" name="expense_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars(date('Y-m-d', strtotime($expense['expense_date']))) ?>" required>
            </div>
            
            <!-- Category -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Category *</label>
                <div class="relative">
                    <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($expense['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Vendor/Supplier -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Vendor/Supplier</label>
                <div class="relative">
                    <select name="vendor_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="">Select Vendor (Optional)</option>
                        <?php foreach ($vendors as $vendor): ?>
                        <option value="<?= $vendor['id'] ?>" <?= ($expense['vendor_id'] ?? '') == $vendor['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($vendor['company_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            
            <!-- Amount -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Amount *</label>
                <input type="number" name="amount" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($expense['amount']) ?>" step="0.01" min="0" required>
            </div>
        </div>
        
        <!-- Description (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600">Description *</label>
            <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2" required placeholder="Enter description..."><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Payment Method -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Payment Method</label>
                <div class="relative">
                    <select name="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="">Select Method</option>
                        <option value="cash" <?= ($expense['payment_method'] ?? '') == 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="bank_transfer" <?= ($expense['payment_method'] ?? '') == 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                        <option value="credit_card" <?= ($expense['payment_method'] ?? '') == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                        <option value="check" <?= ($expense['payment_method'] ?? '') == 'check' ? 'selected' : '' ?>>Check</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            
            <!-- Reference Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Reference Number</label>
                <input type="text" name="reference_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($expense['reference_number'] ?? '') ?>" placeholder="Check #, Transaction ID, etc.">
            </div>
            
            <!-- Payment Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Payment Status</label>
                <div class="relative">
                    <select name="payment_status" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="pending" <?= ($expense['payment_status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="paid" <?= ($expense['payment_status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <!-- Notes (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600">Additional Notes</label>
            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2" placeholder="Enter notes..."><?= htmlspecialchars($expense['notes'] ?? '') ?></textarea>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Update Expense</span>
            </button>
            <a href="<?= BASE_URL ?>/expenses" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const expenseForm = document.getElementById('expenseForm');
    if (expenseForm) {
        expenseForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('id', '<?= $expense['id'] ?>');
            
            fetch('<?= BASE_URL ?>/expenses/edit/<?= $expense['id'] ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/expenses';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating expense:', error);
                alert('An error occurred while updating the expense.');
            });
        });
    }
});
</script>
