<?php
if (!$product) {
    echo '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Product not found</div>';
    return;
}

// Map stock state
$current_stock = floatval($product['current_stock'] ?? 0);
$reorder_level = floatval($product['reorder_level'] ?? 0);
$purchase_price = floatval($product['purchase_price'] ?? 0);
$sale_price = floatval($product['sale_price'] ?? 0);

$stockStatus = '';
$stockBadgeColor = '';
if ($product['type'] == 'product') {
    if ($current_stock <= 0) {
        $stockStatus = 'Out of Stock';
        $stockBadgeColor = 'bg-red-100 text-red-700 border-red-200';
    } elseif ($current_stock <= $reorder_level) {
        $stockStatus = 'Low Stock';
        $stockBadgeColor = 'bg-amber-100 text-amber-700 border-amber-200';
    } else {
        $stockStatus = 'In Stock';
        $stockBadgeColor = 'bg-green-100 text-green-700 border-green-200';
    }
}

// Financial math
$totalSalesVal = floatval($product['total_sold'] ?? 0) * $sale_price;
$totalCostVal = floatval($product['total_sold'] ?? 0) * $purchase_price;
$grossProfit = $totalSalesVal - $totalCostVal;
$marginVal = $sale_price - $purchase_price;
$marginPercent = $purchase_price > 0 ? ($marginVal / $purchase_price) * 100 : 0;
$stockValuation = $current_stock * $purchase_price;

$currency_code = strtoupper($currency ?? 'USD');
?>

<div class="space-y-4">
    <!-- Breadcrumbs & Quick Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Products Details</h1>
            <p class="text-xs text-slate-400 mt-0.5">
                <a href="<?= BASE_URL ?>/products" class="hover:text-primary transition-colors">Inventory</a> 
                <span class="mx-1 text-slate-300">&gt;</span> 
                <a href="<?= BASE_URL ?>/products" class="hover:text-primary transition-colors">Products</a> 
                <span class="mx-1 text-slate-300">&gt;</span> 
                <span class="text-slate-600 font-medium"><?= htmlspecialchars($product['item_name'] ?? '') ?></span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= BASE_URL ?>/products" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back</span>
            </a>
            <a href="<?= BASE_URL ?>/products/edit/<?= $product['id'] ?>" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit Item</span>
            </a>
        </div>
    </div>

    <!-- Product Details Card -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-6">
        <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
            <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-base text-slate-500">inventory_2</span>
                Product Details
            </h5>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Info Panel -->
            <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4 border-r border-slate-100 pr-0 lg:pr-6">
                <div class="flex items-start gap-4">
                    <?php if ($product['image']): ?>
                        <img src="<?= BASE_URL . '/' . $product['image'] ?>" alt="<?= htmlspecialchars($product['item_name']) ?>" class="w-16 h-16 rounded-md object-cover border border-slate-200 shadow-sm flex-shrink-0">
                    <?php else: ?>
                        <div class="w-16 h-16 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl">image</span>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h2 class="text-base font-bold text-slate-900 leading-tight"><?= htmlspecialchars($product['item_name'] ?? '') ?></h2>
                            <?php if ($product['type'] == 'product'): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $stockBadgeColor ?>">
                                    <?= $stockStatus ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Item ID: <?= htmlspecialchars($product['item_code'] ?? '') ?></p>
                        
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium border bg-blue-50 text-blue-600 border-blue-200">
                                <?= ucfirst($product['type'] ?? '') ?>
                            </span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium border <?= ($product['is_active'] ?? 0) == 1 ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-50 text-slate-600 border-slate-200' ?>">
                                <?= ($product['is_active'] ?? 0) == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Representations (Switch Indicators) -->
                <div class="flex-1 space-y-2.5 text-xs border-t border-slate-50 pt-4 w-full">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Active Status</span>
                        <span class="h-2 w-2 rounded-full <?= ($product['is_active'] ?? 0) == 1 ? 'bg-green-500' : 'bg-slate-300' ?>"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Track Quantity</span>
                        <span class="h-2 w-2 rounded-full <?= $product['type'] == 'product' ? 'bg-blue-500' : 'bg-slate-300' ?>"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Ready for Sale</span>
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Available in POS</span>
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    </div>
                </div>
            </div>

            <!-- Right Details Columns (Specs Grid) -->
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-xs pl-0 lg:pl-6">
                <!-- Col 1 -->
                <div class="space-y-4">
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Product Code</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['item_code'] ?? 'N/A') ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">SKU Code</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['sku'] ?: 'N/A') ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Barcode</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['barcode'] ?: 'N/A') ?></div>
                    </div>
                </div>
                <!-- Col 2 -->
                <div class="space-y-4">
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Category</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['category_name'] ?: 'Uncategorized') ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Unit of Measure</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['unit'] ?: 'pcs') ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Storage Location</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['location'] ?: 'Not Specified') ?></div>
                    </div>
                </div>
                <!-- Col 3 -->
                <div class="space-y-4">
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Cost per Unit</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($currency_code) ?> <?= number_format($purchase_price, 2) ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Cost Method</div>
                        <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($product['cost_method'] ?? 'Average') ?></div>
                    </div>
                    <div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[9px] mb-1">Minimum Stock</div>
                        <div class="font-bold text-slate-900 text-sm"><?= number_format($product['min_stock'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Financial Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card 1: Total Sales -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm group">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Sales Value</span>
                <span class="material-symbols-outlined text-slate-400 text-base">analytics</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($currency_code) ?> <?= number_format($totalSalesVal, 2) ?></h3>
            <p class="text-[10px] text-slate-400 mt-1">Calculated from <span class="font-semibold text-slate-600"><?= number_format($product['total_sold'] ?? 0) ?></span> units sold</p>
        </div>

        <!-- Card 2: Total Gross Profit -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm group">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Gross Profit</span>
                <span class="material-symbols-outlined text-slate-400 text-base">payments</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 <?= $grossProfit >= 0 ? 'text-green-600' : 'text-red-500' ?>"><?= htmlspecialchars($currency_code) ?> <?= number_format($grossProfit, 2) ?></h3>
            <p class="text-[10px] text-slate-400 mt-1">Margin value is <span class="font-semibold text-slate-600"><?= htmlspecialchars($currency_code) ?> <?= number_format($marginVal, 2) ?></span> per unit</p>
        </div>

        <!-- Card 3: Profitability Margin -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm group">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Profitability Margin</span>
                <span class="material-symbols-outlined text-slate-400 text-base">trending_up</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900"><?= number_format($marginPercent, 2) ?>%</h3>
            <p class="text-[10px] text-slate-400 mt-1">Based on unit cost markup ratio</p>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Left Column: Stock Overview (Spans 4 columns) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex flex-col gap-5">
                <div>
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">monitoring</span>
                        Stock Overview
                    </h4>
                    <p class="text-[10px] text-slate-400 mt-1">Current status summary of stock levels and allocation</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-3 flex flex-col justify-center">
                        <div class="flex items-center gap-1.5 text-slate-400 mb-1">
                            <span class="material-symbols-outlined text-sm">attach_money</span>
                            <span class="text-[9px] font-semibold uppercase">Total Value</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900"><?= htmlspecialchars($currency_code) ?> <?= number_format($stockValuation, 2) ?></span>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-3 flex flex-col justify-center">
                        <div class="flex items-center gap-1.5 text-slate-400 mb-1">
                            <span class="material-symbols-outlined text-sm">warning</span>
                            <span class="text-[9px] font-semibold uppercase">Reorder Lvl</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900"><?= number_format($reorder_level) ?></span>
                    </div>
                </div>

                <!-- Available Stock Progress Bar (Pills representation) -->
                <?php if ($product['type'] == 'product'): ?>
                <div class="space-y-3 border-t border-slate-50 pt-4">
                    <div>
                        <div class="flex justify-between items-center text-[10px] font-bold text-slate-700">
                            <span>Available Stock</span>
                            <span class="text-green-600 font-semibold">Ready for use</span>
                        </div>
                        
                        <!-- Mini pills visualizer -->
                        <div class="flex gap-[3px] mt-1.5">
                            <?php
                            // Calculate filled pills out of 24 max
                            $maxRef = max(floatval($product['max_stock'] ?? 0), $reorder_level * 2, 24);
                            $fillRatio = $maxRef > 0 ? ($current_stock / $maxRef) : 0;
                            $filledPills = min(24, max(0, round($fillRatio * 24)));
                            
                            for ($i = 1; $i <= 24; $i++) {
                                if ($i <= $filledPills) {
                                    echo '<div class="h-5 flex-1 bg-blue-500 rounded-[2px] transition-all"></div>';
                                } else {
                                    echo '<div class="h-5 flex-1 bg-slate-100 rounded-[2px] transition-all"></div>';
                                }
                            }
                            ?>
                        </div>
                        <div class="flex justify-between items-center text-[9px] text-slate-400 mt-1">
                            <span><?= number_format($current_stock) ?> <?= htmlspecialchars($product['unit'] ?? '') ?></span>
                            <span>Max: <?= number_format(max(floatval($product['max_stock'] ?? 0), $current_stock)) ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center text-[10px] font-bold text-slate-700">
                            <span>Warning Stock Limits</span>
                            <span class="text-amber-500 font-semibold">Configured</span>
                        </div>
                        
                        <!-- Warning pills visualizer -->
                        <div class="flex gap-[3px] mt-1.5">
                            <?php
                            $minStock = floatval($product['min_stock'] ?? 0);
                            $warningRatio = $current_stock > 0 ? ($minStock / $current_stock) : 0;
                            $filledWarning = min(24, max(0, round($warningRatio * 24)));
                            
                            for ($i = 1; $i <= 24; $i++) {
                                if ($i <= $filledWarning) {
                                    echo '<div class="h-5 flex-1 bg-amber-500 rounded-[2px] transition-all"></div>';
                                } else {
                                    echo '<div class="h-5 flex-1 bg-slate-100 rounded-[2px] transition-all"></div>';
                                }
                            }
                            ?>
                        </div>
                        <div class="flex justify-between items-center text-[9px] text-slate-400 mt-1">
                            <span>Min Warning Lvl: <?= number_format($minStock) ?></span>
                            <span>Current: <?= number_format($current_stock) ?></span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-6 text-slate-400 border-t border-slate-50 pt-4">
                    <span class="material-symbols-outlined text-3xl opacity-50 mb-1">dashboard_customize</span>
                    <p class="text-xs">No stock limits configured for services.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Tabulated View (Spans 8 columns) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-100 bg-slate-50/50 px-4">
                    <button onclick="switchTab('movements')" id="tabBtn-movements" class="tab-btn px-4 py-3 border-b-2 border-primary text-primary font-bold text-xs uppercase tracking-wider transition-all focus:outline-none">
                        Movements
                    </button>
                    <button onclick="switchTab('actions')" id="tabBtn-actions" class="tab-btn px-4 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-bold text-xs uppercase tracking-wider transition-all focus:outline-none">
                        Quick Actions
                    </button>
                    <button onclick="switchTab('pricing')" id="tabBtn-pricing" class="tab-btn px-4 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-bold text-xs uppercase tracking-wider transition-all focus:outline-none">
                        Pricing & Valuation
                    </button>
                </div>

                <!-- Tab: Movements -->
                <div id="tabContent-movements" class="tab-pane">
                    <?php if ($product['type'] == 'product'): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Type</th>
                                    <th class="px-4 py-2 text-right">Qty</th>
                                    <th class="px-4 py-2 text-right">Price</th>
                                    <th class="px-4 py-2 text-right">Cost</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2">Reference</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($movements as $m): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-2.5 text-slate-500 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                                    <td class="px-4 py-2.5">
                                        <?php
                                        $movement_types = [
                                            'purchase' => 'bg-green-50 text-green-700 border-green-200',
                                            'sale' => 'bg-red-50 text-red-700 border-red-200',
                                            'adjustment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'return' => 'bg-blue-50 text-blue-700 border-blue-200'
                                        ];
                                        $color_class = $movement_types[$m['movement_type']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                        ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border <?= $color_class ?>">
                                            <?= ucfirst($m['movement_type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-bold <?= in_array($m['movement_type'], ['purchase', 'return']) ? 'text-green-600' : 'text-red-500' ?>">
                                        <?= in_array($m['movement_type'], ['purchase', 'return']) ? '+' : '-' ?><?= number_format($m['quantity']) ?>
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-slate-900 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($sale_price, 2) ?></td>
                                    <td class="px-4 py-2.5 text-right text-slate-600 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($m['unit_price'], 2) ?></td>
                                    <td class="px-4 py-2.5 text-right text-slate-900 font-bold"><?= htmlspecialchars($currency_code) ?> <?= number_format($m['quantity'] * $m['unit_price'], 2) ?></td>
                                    <td class="px-4 py-2.5 text-slate-600">
                                        <?php if (!empty($m['reference_type']) && !empty($m['reference_id'])): ?>
                                            <a href="<?= BASE_URL ?>/<?= htmlspecialchars($m['reference_type']) ?>s/view/<?= htmlspecialchars($m['reference_id']) ?>" class="text-primary hover:underline font-semibold">
                                                <?= ucfirst(htmlspecialchars($m['reference_type'])) ?> #<?= htmlspecialchars($m['reference_id']) ?>
                                            </a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($m['notes'] ?? '-') ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($movements)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-slate-400 py-10">No stock movements recorded yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-12 text-slate-400">
                        <span class="material-symbols-outlined text-4xl opacity-50 mb-2">work_history</span>
                        <p class="text-sm font-semibold">Service Profile</p>
                        <p class="text-xs mt-1">This item represents a service and does not track inventory quantities or stock movements.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Actions -->
                <div id="tabContent-actions" class="tab-pane hidden p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <?php if ($product['type'] == 'product'): ?>
                        <!-- Action: Adjust Stock -->
                        <button onclick="showAdjustStock()" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-300 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-amber-500 group-hover:scale-110 transition-transform">inventory</span>
                            <span class="text-xs font-bold text-slate-700">Adjust Stock Level</span>
                        </button>
                        <!-- Action: Print Barcode -->
                        <button onclick="printBarcode(<?= $product['id'] ?>)" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-blue-500 group-hover:scale-110 transition-transform">barcode_scanner</span>
                            <span class="text-xs font-bold text-slate-700">Print Barcode</span>
                        </button>
                        <?php endif; ?>
                        
                        <!-- Action: Duplicate Product -->
                        <button onclick="duplicateProduct(<?= $product['id'] ?>)" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-400 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-slate-500 group-hover:scale-110 transition-transform">content_copy</span>
                            <span class="text-xs font-bold text-slate-700">Duplicate Item</span>
                        </button>
                        
                        <!-- Action: Create Invoice -->
                        <a href="<?= BASE_URL ?>/invoices/create" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-primary/40 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-primary group-hover:scale-110 transition-transform">receipt_long</span>
                            <span class="text-xs font-bold text-slate-700">Invoice Item</span>
                        </a>
                        
                        <!-- Action: Toggle Status -->
                        <?php if ($product['is_active']): ?>
                        <button onclick="deactivateProduct(<?= $product['id'] ?>)" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-red-300 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-red-500 group-hover:scale-110 transition-transform">block</span>
                            <span class="text-xs font-bold text-red-600">Deactivate Item</span>
                        </button>
                        <?php else: ?>
                        <button onclick="activateProduct(<?= $product['id'] ?>)" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-green-300 transition-all flex flex-col items-center justify-center text-center gap-2 group">
                            <span class="material-symbols-outlined text-xl text-green-500 group-hover:scale-110 transition-transform">check_circle</span>
                            <span class="text-xs font-bold text-green-600">Activate Item</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tab: Pricing -->
                <div id="tabContent-pricing" class="tab-pane hidden p-5">
                    <table class="w-full text-xs divide-y divide-slate-100">
                        <tr class="py-3 flex justify-between items-center">
                            <td class="font-medium text-slate-500">Retail Sale Price:</td>
                            <td class="text-right text-slate-900 font-bold text-sm"><?= htmlspecialchars($currency_code) ?> <?= number_format($sale_price, 2) ?></td>
                        </tr>
                        <tr class="py-3 flex justify-between items-center">
                            <td class="font-medium text-slate-500">Average Unit Cost:</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($purchase_price, 2) ?></td>
                        </tr>
                        <tr class="py-3 flex justify-between items-center">
                            <td class="font-medium text-slate-500">Gross Margin per Unit:</td>
                            <td class="text-right font-bold <?= $marginVal >= 0 ? 'text-green-600' : 'text-red-500' ?>">
                                <?= htmlspecialchars($currency_code) ?> <?= number_format($marginVal, 2) ?>
                                (<?= number_format($marginPercent, 1) ?>%)
                            </td>
                        </tr>
                        <?php if ($product['type'] == 'product'): ?>
                        <tr class="py-3 flex justify-between items-center">
                            <td class="font-medium text-slate-500">Current Stock Value (at Cost):</td>
                            <td class="text-right text-slate-900 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($stockValuation, 2) ?></td>
                        </tr>
                        <tr class="py-3 flex justify-between items-center">
                            <td class="font-medium text-slate-500">Potential Total Sales Value:</td>
                            <td class="text-right text-green-600 font-semibold"><?= htmlspecialchars($currency_code) ?> <?= number_format($current_stock * $sale_price, 2) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="adjustStockModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="adjustStockBackdrop"></div>
    
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="adjustStockPanel" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 sm:my-8 sm:w-full sm:max-w-lg">
                <div class="border-b border-slate-100 px-6 py-4 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">Adjust Stock Level</h3>
                    <button type="button" onclick="closeAdjustStock()" class="text-slate-400 hover:text-slate-500 focus:outline-none flex items-center">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form id="adjustStockForm">
                    <div class="px-6 py-5">
                        <input type="hidden" name="product_id" id="adjust_product_id" value="<?= $product['id'] ?>">
                        
                        <div class="p-3 bg-blue-50 border border-blue-100 text-blue-800 rounded-md text-xs flex gap-2.5 items-start mb-4">
                            <span class="material-symbols-outlined text-base text-blue-600 mt-0.5">info</span>
                            <div>
                                <span class="font-bold">Current Quantity in Stock:</span> <?= number_format($current_stock) ?> <?= htmlspecialchars($product['unit'] ?? '') ?>.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Adjustment Type</label>
                            <div class="relative">
                                <select name="adjustment_type" id="adjustment_type" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer" required>
                                    <option value="add">Add Stock (+)</option>
                                    <option value="subtract">Remove Stock (-)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quantity</label>
                            <input type="number" name="quantity" id="adjust_quantity" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" min="1" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Notes / Reason</label>
                            <textarea name="notes" class="w-full bg-slate-50 border border-slate-200 rounded-md p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none" rows="2" placeholder="e.g. Stock take correction, wastage..." required></textarea>
                        </div>

                        <div class="p-3 bg-amber-50 border border-amber-100 text-amber-800 rounded-md text-xs flex gap-2.5 items-start hidden" id="stockWarning">
                            <span class="material-symbols-outlined text-base text-amber-600 mt-0.5">warning</span>
                            <div id="warningMessage">Removing stock will decrease available inventory. Make sure this is correct.</div>
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
// Tab Switching Controller
function switchTab(tabId) {
    // Hide all contents
    const contents = document.querySelectorAll('.tab-pane');
    contents.forEach(content => content.classList.add('hidden'));

    // Reset button states
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-primary', 'text-primary');
        btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700');
    });

    // Show target content
    const targetContent = document.getElementById('tabContent-' + tabId);
    if (targetContent) targetContent.classList.remove('hidden');

    // Highlight target button
    const targetBtn = document.getElementById('tabBtn-' + tabId);
    if (targetBtn) {
        targetBtn.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700');
        targetBtn.classList.add('border-primary', 'text-primary');
    }
}

// Modal Controls
function showAdjustStock() {
    const modal = document.getElementById('adjustStockModal');
    const backdrop = document.getElementById('adjustStockBackdrop');
    const panel = document.getElementById('adjustStockPanel');
    
    modal.classList.remove('hidden');
    
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
    
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('adjustStockForm').reset();
        document.getElementById('stockWarning').classList.add('hidden');
    }, 300);
}

document.addEventListener("DOMContentLoaded", function() {
    const adjustmentType = document.getElementById('adjustment_type');
    const stockWarning = document.getElementById('stockWarning');
    
    if (adjustmentType && stockWarning) {
        adjustmentType.addEventListener('change', function() {
            if (this.value === 'subtract') {
                stockWarning.classList.remove('hidden');
            } else {
                stockWarning.classList.add('hidden');
            }
        });
    }

    // Modal submit via vanilla JS fetch
    const adjustStockForm = document.getElementById('adjustStockForm');
    if (adjustStockForm) {
        adjustStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const quantityInput = document.getElementById('adjust_quantity');
            const quantity = parseInt(quantityInput.value);
            const type = document.getElementById('adjustment_type').value;
            const currentStock = <?= intval($current_stock) ?>;
            
            if (type === 'subtract' && quantity > currentStock) {
                alert('Cannot remove more than current stock (' + currentStock + ')');
                return false;
            }
            
            if (!confirm('Are you sure you want to adjust the inventory level?')) {
                return false;
            }
            
            const formData = new FormData(this);
            
            fetch('<?= BASE_URL ?>/products/adjustStock/<?= $product['id'] ?>', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error adjusting stock:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});

function printBarcode(productId) {
    window.open('<?= BASE_URL ?>/products/barcode/' + productId, '_blank', 'width=400,height=300');
}

function duplicateProduct(productId) {
    if (confirm('Create a duplicate of this product?')) {
        window.location.href = '<?= BASE_URL ?>/products/duplicate/' + productId;
    }
}

function deactivateProduct(productId) {
    if (confirm('Are you sure you want to deactivate this product?')) {
        fetch('<?= BASE_URL ?>/products/delete/' + productId, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function activateProduct(productId) {
    if (confirm('Activate this product?')) {
        fetch('<?= BASE_URL ?>/products/activate/' + productId, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>