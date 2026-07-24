<?php
if (!$product) {
    echo '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Product not found</div>';
    return;
}
?>

<!-- Title Section -->
<div class="max-w-5xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">edit_note</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Product Overview</h1>
            <p class="text-xs text-slate-500">Update item pricing, category, and inventory levels.</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>/products/view/<?= $product['id'] ?>" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">visibility</span>
            <span>View</span>
        </a>
        <a href="<?= BASE_URL ?>/products" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back</span>
        </a>
    </div>
</div>

<!-- Error Alert Banner -->
<div id="errorAlert" class="max-w-5xl mx-auto p-4 mb-4 text-xs text-red-800 rounded-md bg-red-50 border border-red-200 flex gap-2.5 items-start hidden">
    <span class="material-symbols-outlined text-base text-red-600 mt-0.5">error</span>
    <div class="flex-1">
        <span class="font-bold">Error:</span>
        <span id="errorMessage">An error occurred while saving.</span>
    </div>
    <button type="button" onclick="document.getElementById('errorAlert').classList.add('hidden')" class="text-red-500 hover:text-red-700 font-bold focus:outline-none flex items-center">
        <span class="material-symbols-outlined text-sm">close</span>
    </button>
</div>

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
    <form id="productForm" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Core Fields -->
            <div class="lg:col-span-2 space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Item Specifications</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Item Type *</label>
                        <div class="relative">
                            <select name="type" id="itemType" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer" required>
                                <option value="product" <?= ($product['type'] ?? '') == 'product' ? 'selected' : '' ?>>Product (Stock Item)</option>
                                <option value="service" <?= ($product['type'] ?? '') == 'service' ? 'selected' : '' ?>>Service (Non-Stock)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Category</label>
                        <div class="relative">
                            <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                <?php foreach ($categories ?? [] as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= ($product['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Item Name *</label>
                    <input type="text" name="item_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['item_name'] ?? '') ?>" required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Item Code</label>
                        <input type="text" name="item_code" class="w-full bg-slate-200 border border-slate-300 rounded-md py-1.5 px-3 text-sm text-slate-600 outline-none cursor-not-allowed" value="<?= htmlspecialchars($product['item_code'] ?? '') ?>" readonly>
                        <small class="block text-[10px] text-slate-400">Item code cannot be changed</small>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">SKU</label>
                        <input type="text" name="sku" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['sku'] ?? '') ?>">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Barcode</label>
                        <input type="text" name="barcode" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['barcode'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Description</label>
                    <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Enter item description..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Unit</label>
                        <input type="text" name="unit" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['unit'] ?? '') ?>" placeholder="e.g., pcs, kg, box">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Sale Price *</label>
                        <input type="number" name="sale_price" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['sale_price'] ?? 0) ?>" step="0.01" min="0" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Purchase Price</label>
                        <input type="number" name="purchase_price" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['purchase_price'] ?? 0) ?>" step="0.01" min="0">
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Image Manager -->
            <div class="lg:col-span-1 space-y-4">
                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Item Media</h5>
                
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-slate-600">Product Image</label>
                    
                    <div class="border-2 border-dashed border-slate-200 rounded-lg p-4 bg-slate-50/50 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <!-- Loading Spinner Overlay -->
                        <div id="imageSpinner" class="absolute inset-0 bg-white/80 backdrop-blur-[1px] flex flex-col items-center justify-center hidden z-10">
                            <div class="animate-spin rounded-full h-8 w-8 border-2 border-slate-200 border-t-blue-600"></div>
                            <span class="text-[10px] font-semibold text-slate-500 mt-2">Uploading image...</span>
                        </div>
                        
                        <div id="imageWrapper" class="mb-3 <?= $product['image'] ? '' : 'hidden' ?>">
                            <img id="currentImage" src="<?= $product['image'] ? (BASE_URL . '/' . $product['image']) : '#' ?>" alt="Preview" class="h-28 rounded object-cover border border-slate-200 shadow-sm mx-auto">
                        </div>
                        
                        <div id="imagePlaceholder" class="h-10 w-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-400 mb-3 <?= $product['image'] ? 'hidden' : '' ?>">
                            <span class="material-symbols-outlined text-xl">image</span>
                        </div>
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="cursor-pointer bg-white hover:bg-slate-50 border border-slate-200 shadow-sm text-xs font-semibold text-slate-700 px-3 py-1.5 rounded-md transition-all flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">upload_file</span>
                                <span id="uploadBtnText"><?= $product['image'] ? 'Change image' : 'Choose file' ?></span>
                                <input type="file" name="image" id="productImage" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div id="imageName" class="text-[10px] text-slate-400 mt-2 truncate w-full text-center"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stock Information Section (Conditionally displayed) -->
        <div class="stock-section space-y-4 mt-6" id="stockSection" style="<?= ($product['type'] ?? '') == 'product' ? '' : 'display: none;' ?>">
            <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Stock Details</h5>
            
            <div class="p-3 bg-blue-50/50 border border-blue-100 text-blue-800 rounded-md text-xs flex gap-2.5 items-start">
                <span class="material-symbols-outlined text-base text-blue-600 mt-0.5">info</span>
                <div>
                    <span class="font-bold">Current Stock Level:</span> <?= number_format($product['current_stock'] ?? 0) ?> <?= htmlspecialchars($product['unit'] ?? '') ?>.
                    <p class="text-slate-500 mt-0.5">To modify inventory quantities, please use the "Adjust Stock" action button from the products index dashboard list.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Reorder Level</label>
                    <input type="number" name="reorder_level" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['reorder_level'] ?? 0) ?>" min="0">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Costing Method</label>
                    <div class="relative">
                        <select name="cost_method" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="Average" <?= ($product['cost_method'] ?? '') == 'Average' ? 'selected' : '' ?>>Average</option>
                            <option value="FIFO" <?= ($product['cost_method'] ?? '') == 'FIFO' ? 'selected' : '' ?>>FIFO</option>
                            <option value="LIFO" <?= ($product['cost_method'] ?? '') == 'LIFO' ? 'selected' : '' ?>>LIFO</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Storage Location</label>
                    <input type="text" name="location" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['location'] ?? '') ?>" placeholder="Warehouse location">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Minimum Stock Warning Level</label>
                    <input type="number" name="min_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['min_stock'] ?? 0) ?>" min="0">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Maximum Stock Warning Level</label>
                    <input type="number" name="max_stock" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= htmlspecialchars($product['max_stock'] ?? 0) ?>" min="0">
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Item Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1" <?= ($product['is_active'] ?? 0) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= ($product['is_active'] ?? 0) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span id="btnText">Update Product</span>
            </button>
            <a href="<?= BASE_URL ?>/products/view/<?= $product['id'] ?>" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const itemType = document.getElementById('itemType');
    const stockSection = document.getElementById('stockSection');
    
    // Toggle stock section based on product type choice
    if (itemType && stockSection) {
        itemType.addEventListener('change', function() {
            if (this.value === 'product') {
                stockSection.style.display = 'block';
            } else {
                stockSection.style.display = 'none';
            }
        });
    }
    
    // Image uploader previewing controls
    const productImage = document.getElementById('productImage');
    const imageWrapper = document.getElementById('imageWrapper');
    const currentImage = document.getElementById('currentImage');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const uploadBtnText = document.getElementById('uploadBtnText');
    const imageName = document.getElementById('imageName');
    
    if (productImage) {
        productImage.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (currentImage) currentImage.src = e.target.result;
                    if (imageWrapper) imageWrapper.classList.remove('hidden');
                    if (imagePlaceholder) imagePlaceholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
                if (uploadBtnText) uploadBtnText.textContent = 'Change image';
                if (imageName) imageName.textContent = file.name;
            }
        });
    }
    
    // Vanilla JS Form Submission
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
            formData.append('id', '<?= $product['id'] ?>');
            
            // Set loading state
            if (submitBtn) submitBtn.disabled = true;
            if (btnText) btnText.textContent = 'Updating...';
            if (errorAlert) errorAlert.classList.add('hidden');
            
            if (productImage && productImage.files.length > 0) {
                if (imageSpinner) imageSpinner.classList.remove('hidden');
            }
            
            fetch('<?= BASE_URL ?>/products/edit/<?= $product['id'] ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/products/view/<?= $product['id'] ?>?msg=updated';
                } else {
                    if (imageSpinner) imageSpinner.classList.add('hidden');
                    if (submitBtn) submitBtn.disabled = false;
                    if (btnText) btnText.textContent = 'Update Product';
                    
                    if (errorAlert && errorMessage) {
                        errorMessage.textContent = data.message;
                        errorAlert.classList.remove('hidden');
                        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            })
            .catch(error => {
                console.error('Error updating product:', error);
                if (imageSpinner) imageSpinner.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                if (btnText) btnText.textContent = 'Update Product';
                
                if (errorAlert && errorMessage) {
                    errorMessage.textContent = 'An error occurred while updating the product.';
                    errorAlert.classList.remove('hidden');
                    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }
});
</script>