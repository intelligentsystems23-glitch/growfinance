<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Products & Services</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your inventory, services, and pricing</p>
    </div>
    <a href="<?= BASE_URL ?? '' ?>/products/create" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
        <span class="material-symbols-outlined text-sm">add</span>
        New Product
    </a>
</div>

<!-- Stock Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <!-- Total Items -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Items</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors"><?= number_format($stockValue['total_items'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">category</span>
            </div>
        </div>
    </div>
    <!-- Total Quantity -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Total Quantity</p>
                <h3 class="text-xl font-bold text-green-600"><?= number_format($stockValue['total_quantity'] ?? 0) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-green-50 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined text-base">inventory_2</span>
            </div>
        </div>
    </div>
    <!-- Stock Value -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Stock Value</p>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($currency) ?> <?= number_format($stockValue['total_value'] ?? 0, 2) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-blue-50 flex items-center justify-center text-blue-500">
                <span class="material-symbols-outlined text-base">account_balance_wallet</span>
            </div>
        </div>
    </div>
    <!-- Low Stock Items -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-0.5">Low Stock Items</p>
                <h3 class="text-xl font-bold text-amber-500"><?= number_format(count($lowStockItems ?? [])) ?></h3>
            </div>
            <div class="h-8 w-8 rounded-md bg-amber-50 flex items-center justify-center text-amber-500">
                <span class="material-symbols-outlined text-base">warning</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-4">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search name, code, SKU..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Category</label>
            <div class="relative">
                <select name="category_id" class="w-full pl-3 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">All Categories</option>
                    <?php foreach ($categories ?? [] as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['category_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Type</label>
            <div class="relative">
                <select name="type" class="w-full pl-3 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">All Types</option>
                    <option value="product" <?= ($_GET['type'] ?? '') == 'product' ? 'selected' : '' ?>>Products</option>
                    <option value="service" <?= ($_GET['type'] ?? '') == 'service' ? 'selected' : '' ?>>Services</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        <div class="md:col-span-2 flex items-center h-full pb-2">
            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" name="low_stock" value="1" class="peer sr-only" <?= isset($_GET['low_stock']) ? 'checked' : '' ?>>
                    <div class="w-4 h-4 border border-slate-300 rounded peer-checked:bg-primary peer-checked:border-primary peer-focus:ring-2 peer-focus:ring-primary/20 transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined text-[12px] text-white opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                    </div>
                </div>
                <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Low Stock Only</span>
            </label>
        </div>
        <div class="md:col-span-1">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors h-[34px]" title="Filter">
                <span class="material-symbols-outlined text-sm">filter_list</span>
            </button>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Image</th>
                    <th class="px-4 py-2">Item Code</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2 text-right">Stock</th>
                    <th class="px-4 py-2 text-right">Sale Price</th>
                    <th class="px-4 py-2 text-right">Purchase Price</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="10">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">inventory_2</span>
                            <p class="text-base font-medium text-slate-500">No products found</p>
                            <p class="text-sm mt-1">Adjust your filters or add a new product.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= BASE_URL ?? '' ?>/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['item_name']) ?>" class="w-7 h-7 rounded object-cover border border-slate-200 shadow-sm">
                        <?php else: ?>
                            <div class="w-7 h-7 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                                <span class="material-symbols-outlined text-sm">inventory_2</span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <a href="<?= BASE_URL ?? '' ?>/products/view/<?= $product['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($product['item_code']) ?>
                        </a>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap font-medium text-slate-900"><?= htmlspecialchars($product['item_name']) ?></td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-slate-600"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                    <td class="px-4 py-1.5 whitespace-nowrap">
                        <?php if ($product['type'] == 'product'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-blue-50 text-blue-600 border-blue-200">Product</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-purple-50 text-purple-600 border-purple-200">Service</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-right">
                        <?php if ($product['type'] == 'product'): ?>
                            <?php if ($product['current_stock'] <= $product['reorder_level']): ?>
                                <span class="text-red-500 font-bold"><?= number_format($product['current_stock']) ?></span>
                            <?php else: ?>
                                <span class="font-medium text-slate-900"><?= number_format($product['current_stock']) ?></span>
                            <?php endif; ?>
                            <small class="text-slate-500 ml-0.5"><?= htmlspecialchars($product['unit'] ?? '') ?></small>
                        <?php else: ?>
                            <span class="text-slate-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-right font-medium text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($product['sale_price'], 2) ?></td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-right font-medium text-slate-600"><?= htmlspecialchars($currency) ?> <?= number_format($product['purchase_price'], 2) ?></td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-center">
                        <?php if ($product['is_active']): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-green-50 text-green-600 border-green-200">Active</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border bg-slate-100 text-slate-600 border-slate-200">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-1.5 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= BASE_URL ?? '' ?>/products/view/<?= $product['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <a href="<?= BASE_URL ?? '' ?>/products/edit/<?= $product['id'] ?>" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <?php if ($product['type'] == 'product'): ?>
                            <button type="button" onclick="showAdjustStock(<?= $product['id'] ?>)" class="h-7 w-7 rounded border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-amber-500 transition-colors shadow-sm" title="Adjust Stock">
                                <span class="material-symbols-outlined text-sm">inventory</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="adjustStockModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="adjustStockBackdrop"></div>
    
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="adjustStockPanel" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 sm:my-8 sm:w-full sm:max-w-lg">
                <div class="border-b border-slate-100 px-6 py-4 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">Adjust Stock</h3>
                    <button type="button" onclick="closeAdjustStock()" class="text-slate-400 hover:text-slate-500 focus:outline-none flex items-center">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form id="adjustStockForm">
                    <div class="px-6 py-5">
                        <input type="hidden" name="product_id" id="adjust_product_id">
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Adjustment Type</label>
                            <div class="relative">
                                <select name="adjustment_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer" required>
                                    <option value="add">Add Stock</option>
                                    <option value="subtract">Remove Stock</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quantity</label>
                            <input type="number" name="quantity" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" min="1" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Notes</label>
                            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50 flex justify-end gap-3">
                        <button type="button" onclick="closeAdjustStock()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-md text-sm font-medium hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90 shadow-sm shadow-primary/30 transition-all">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modal display controls
function showAdjustStock(productId) {
    document.getElementById('adjust_product_id').value = productId;
    
    const modal = document.getElementById('adjustStockModal');
    const backdrop = document.getElementById('adjustStockBackdrop');
    const panel = document.getElementById('adjustStockPanel');
    
    modal.classList.remove('hidden');
    
    // Trigger animations
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function closeAdjustStock() {
    const modal = document.getElementById('adjustStockModal');
    const backdrop = document.getElementById('adjustStockBackdrop');
    const panel = document.getElementById('adjustStockPanel');
    
    // Reverse animations
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('adjustStockForm').reset();
    }, 300);
}

document.addEventListener("DOMContentLoaded", function() {
    // Vanilla JS Form submission for stock adjustment
    const adjustStockForm = document.getElementById('adjustStockForm');
    if (adjustStockForm) {
        adjustStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = document.getElementById('adjust_product_id').value;
            const formData = new FormData(this);
            
            fetch('<?= BASE_URL ?? '' ?>/products/adjustStock/' + productId, {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => {
                location.reload();
            })
            .catch(error => {
                console.error('Error adjusting stock:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});
</script>