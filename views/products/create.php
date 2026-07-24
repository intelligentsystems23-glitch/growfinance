<!-- Title Section -->
<div class="max-w-4xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Item' ?></h1>
            <p class="text-xs text-slate-500">Configure new inventory product or service details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/products" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<!-- Error Alert Banner -->
<div id="errorAlert" class="max-w-4xl mx-auto p-4 mb-4 text-xs text-red-800 rounded-md bg-red-50 border border-red-200 flex gap-2.5 items-start hidden">
    <span class="material-symbols-outlined text-base text-red-600 mt-0.5">error</span>
    <div class="flex-1">
        <span class="font-bold">Error:</span>
        <span id="errorMessage">An error occurred while saving.</span>
    </div>
    <button type="button" onclick="document.getElementById('errorAlert').classList.add('hidden')" class="text-red-500 hover:text-red-700 font-bold focus:outline-none flex items-center">
        <span class="material-symbols-outlined text-sm">close</span>
    </button>
</div>

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
    <form id="productForm" enctype="multipart/form-data" class="space-y-6">
        <!-- Main Form Grid: 2 Columns for details, 1 Column for image -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Details (Spans 2 columns on medium screens) -->
            <div class="md:col-span-2 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Item Type -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Item Type *</label>
                        <div class="relative">
                            <select name="type" id="itemType" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer" required>
                                <option value="product">Product (Stock Item)</option>
                                <option value="service">Service (Non-Stock)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                    <!-- Category -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Category</label>
                        <div class="relative">
                            <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
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
                </div>
                
                <!-- Item Name -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Item Name *</label>
                    <input type="text" name="item_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="e.g. Wireless Mouse">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Item Code -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Item Code</label>
                        <input type="text" name="item_code" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Auto-generated">
                    </div>
                    <!-- SKU -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">SKU</label>
                        <input type="text" name="sku" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Stock keeping unit">
                    </div>
                    <!-- Barcode -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Barcode</label>
                        <input type="text" name="barcode" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="UPC/EAN barcode">
                    </div>
                </div>
                
                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Description</label>
                    <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter item description..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Unit -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Unit</label>
                        <input type="text" name="unit" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="pcs" placeholder="e.g. pcs, kg, box">
                    </div>
                    <!-- Sale Price -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Sale Price *</label>
                        <input type="number" name="sale_price" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" step="0.01" min="0" required placeholder="0.00">
                    </div>
                    <!-- Purchase Price -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Purchase Price</label>
                        <input type="number" name="purchase_price" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Product Image Picker (Spans 1 column) -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-slate-600">Product Image</label>
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-500 rounded-lg p-5 flex flex-col items-center justify-center cursor-pointer transition-all bg-slate-50 relative group h-48 overflow-hidden">
                    <!-- Loading Spinner Overlay -->
                    <div id="imageSpinner" class="absolute inset-0 bg-white/80 backdrop-blur-[1px] flex flex-col items-center justify-center hidden z-20">
                        <div class="animate-spin rounded-full h-8 w-8 border-2 border-slate-200 border-t-blue-600"></div>
                        <span class="text-[10px] font-semibold text-slate-500 mt-2">Uploading image...</span>
                    </div>

                    <input type="file" name="image" id="productImage" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="text-center space-y-2 pointer-events-none" id="uploadPrompt">
                        <span class="material-symbols-outlined text-4xl text-slate-400 group-hover:text-blue-500 transition-colors">cloud_upload</span>
                        <div class="text-xs font-medium text-slate-600">Drag & drop or Click to upload</div>
                        <div class="text-[10px] text-slate-400">PNG, JPG or WEBP (Max 2MB)</div>
                    </div>
                    <div class="absolute inset-0 hidden items-center justify-center p-2 bg-white rounded-lg" id="previewContainer">
                        <img src="" id="imgPreviewTag" class="max-h-full max-w-full rounded-md object-contain">
                        <button type="button" id="removeImgBtn" class="absolute top-2 right-2 h-6 w-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow z-30">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Toggleable Stock Information Section -->
        <div class="stock-section" id="stockSection">
            <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Stock Information</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <!-- Opening Stock -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Opening Stock</label>
                    <input type="number" name="opening_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0">
                </div>
                <!-- Current Stock -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Current Stock</label>
                    <input type="number" name="current_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0">
                </div>
                <!-- Reorder Level -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Reorder Level</label>
                    <input type="number" name="reorder_level" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="10" min="0">
                </div>
                <!-- Cost Method -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Cost Method</label>
                    <div class="relative">
                        <select name="cost_method" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="Average">Average</option>
                            <option value="FIFO">FIFO</option>
                            <option value="LIFO">LIFO</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Minimum Stock -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Minimum Stock</label>
                    <input type="number" name="min_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0">
                </div>
                <!-- Maximum Stock -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Maximum Stock</label>
                    <input type="number" name="max_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="0" min="0">
                </div>
                <!-- Location -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Location</label>
                    <input type="text" name="location" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Warehouse location">
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span id="btnText">Save Product</span>
            </button>
            <a href="<?= BASE_URL ?>/products" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const itemType = document.getElementById('itemType');
    const stockSection = document.getElementById('stockSection');
    
    // Toggle stock section based on selection
    if (itemType && stockSection) {
        itemType.addEventListener('change', function() {
            if (this.value === 'product') {
                stockSection.style.display = 'block';
            } else {
                stockSection.style.display = 'none';
            }
        });
    }

    // Image preview
    const productImage = document.getElementById('productImage');
    const imgPreviewTag = document.getElementById('imgPreviewTag');
    const previewContainer = document.getElementById('previewContainer');
    const removeImgBtn = document.getElementById('removeImgBtn');
    
    if (productImage) {
        productImage.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imgPreviewTag) imgPreviewTag.src = e.target.result;
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeImgBtn) {
        removeImgBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (productImage) productImage.value = '';
            if (imgPreviewTag) imgPreviewTag.src = '';
            if (previewContainer) {
                previewContainer.classList.remove('flex');
                previewContainer.classList.add('hidden');
            }
        });
    }

    // Vanilla JS Form submission
    const productForm = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const imageSpinner = document.getElementById('imageSpinner');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');

    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Set loading state
            if (submitBtn) submitBtn.disabled = true;
            if (btnText) btnText.textContent = 'Saving...';
            if (errorAlert) errorAlert.classList.add('hidden');
            
            if (productImage && productImage.files.length > 0) {
                if (imageSpinner) imageSpinner.classList.remove('hidden');
            }
            
            fetch('<?= BASE_URL ?>/products/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/products/view/' + data.id;
                } else {
                    if (imageSpinner) imageSpinner.classList.add('hidden');
                    if (submitBtn) submitBtn.disabled = false;
                    if (btnText) btnText.textContent = 'Save Product';
                    
                    if (errorAlert && errorMessage) {
                        errorMessage.textContent = data.message;
                        errorAlert.classList.remove('hidden');
                        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            })
            .catch(error => {
                console.error('Error saving product:', error);
                if (imageSpinner) imageSpinner.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                if (btnText) btnText.textContent = 'Save Product';
                
                if (errorAlert && errorMessage) {
                    errorMessage.textContent = 'An error occurred while saving the product.';
                    errorAlert.classList.remove('hidden');
                    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }
});
</script>