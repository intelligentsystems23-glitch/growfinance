<?php if (!$customer): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Customer not found</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-5xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">edit_note</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Customer Overview</h1>
            <p class="text-xs text-slate-500">Update customer profile, billing addresses, and terms.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/customers/view/<?= $customer['id'] ?>" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back to Customer</span>
    </a>
</div>

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
    <form id="customerForm" class="space-y-6">
        <input type="hidden" name="id" value="<?= $customer['id'] ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Company Information -->
            <div class="space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Company Information</h5>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Company Name *</label>
                    <input type="text" name="company_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['company_name'] ?? '') ?>" required>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Contact Person *</label>
                    <input type="text" name="contact_person" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['contact_person'] ?? '') ?>" required>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Email *</label>
                    <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Phone</label>
                        <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Mobile</label>
                        <input type="text" name="mobile" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['mobile'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Website</label>
                    <input type="url" name="website" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['website'] ?? '') ?>" placeholder="https://">
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Tax/VAT Number</label>
                    <input type="text" name="tax_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['tax_number'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Right Column: Address & Financial Information -->
            <div class="space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Address Information</h5>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Address</label>
                    <textarea name="address" class="w-full bg-slate-50 border border-slate-200 rounded-md p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">City</label>
                        <input type="text" name="city" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['city'] ?? '') ?>">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">State/Province</label>
                        <input type="text" name="state" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['state'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Postal Code</label>
                        <input type="text" name="postal_code" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['postal_code'] ?? '') ?>">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Country</label>
                        <div class="relative">
                            <select name="country" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                                <option value="USA" <?= ($customer['country'] ?? '') == 'USA' ? 'selected' : '' ?>>United States</option>
                                <option value="CAN" <?= ($customer['country'] ?? '') == 'CAN' ? 'selected' : '' ?>>Canada</option>
                                <option value="GBR" <?= ($customer['country'] ?? '') == 'GBR' ? 'selected' : '' ?>>United Kingdom</option>
                                <option value="AUS" <?= ($customer['country'] ?? '') == 'AUS' ? 'selected' : '' ?>>Australia</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600">Notes</label>
            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter customer notes..."><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Customer Since -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Customer Since</label>
                <input type="date" name="customer_since" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($customer['customer_since'] ?? date('Y-m-d')) ?>">
            </div>
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Status</label>
                <div class="relative">
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1" <?= ($customer['status'] ?? 0) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= ($customer['status'] ?? 0) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Update Customer</span>
            </button>
            <a href="<?= BASE_URL ?>/customers/view/<?= $customer['id'] ?>" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const customerForm = document.getElementById('customerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('id', '<?= $customer['id'] ?>');
            
            fetch('<?= BASE_URL ?>/customers/edit/<?= $customer['id'] ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/customers/view/<?= $customer['id'] ?>';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating customer:', error);
                alert('An error occurred while updating the customer.');
            });
        });
    }
});
</script>