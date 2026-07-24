<!-- Title Section -->
<div class="max-w-5xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Vendor' ?></h1>
            <p class="text-xs text-slate-500">Configure new supplier profile, banking details, and terms.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/vendors" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
    <form id="vendorForm" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Company Information -->
            <div class="space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Company Information</h5>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Company Name *</label>
                    <input type="text" name="company_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Vendor Category</label>
                    <div class="relative">
                        <select name="vendor_category_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>">
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Contact Person *</label>
                    <input type="text" name="contact_person" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Email *</label>
                        <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Website</label>
                        <input type="url" name="website" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="https://">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Phone</label>
                        <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Mobile</label>
                        <input type="text" name="mobile" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Tax ID/VAT Number</label>
                    <input type="text" name="tax_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>
            
            <!-- Right Column: Address & Payment Information -->
            <div class="space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Address Information</h5>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Address</label>
                    <textarea name="address" class="w-full bg-slate-50 border border-slate-200 rounded-md p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="2"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">City</label>
                        <input type="text" name="city" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">State/Province</label>
                        <input type="text" name="state" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Postal Code</label>
                        <input type="text" name="postal_code" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Country</label>
                        <div class="relative">
                            <select name="country" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                                <option value="USA">United States</option>
                                <option value="CAN">Canada</option>
                                <option value="GBR">United Kingdom</option>
                                <option value="AUS">Australia</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                </div>
                
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 pt-2">Payment Information</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Payment Terms</label>
                        <div class="relative">
                            <select name="payment_terms" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                                <option value="Net 15">Net 15</option>
                                <option value="Net 30" selected>Net 30</option>
                                <option value="Net 45">Net 45</option>
                                <option value="Net 60">Net 60</option>
                                <option value="Due on Receipt">Due on Receipt</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Credit Limit</label>
                        <input type="number" name="credit_limit" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0" step="0.01">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Bank Name</label>
                    <input type="text" name="bank_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Account Number</label>
                        <input type="text" name="bank_account_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Routing Number</label>
                        <input type="text" name="bank_routing_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600">Notes</label>
            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter notes..."></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Status</label>
                <div class="relative">
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Save Vendor</span>
            </button>
            <a href="vendors" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const vendorForm = document.getElementById('vendorForm');
    if (vendorForm) {
        vendorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= BASE_URL ?>/vendors/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/vendors';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving vendor:', error);
                alert('An error occurred while saving the vendor.');
            });
        });
    }
});
</script>