<?php $hide_title = true; ?>

<?php
// Group metadata & icon mappings
$groupMeta = [
    'general' => [
        'title' => 'General Settings',
        'desc' => 'System preferences, currency definitions, and date formats',
        'icon' => 'tune',
        'color' => 'bg-indigo-500'
    ],
    'company' => [
        'title' => 'Company Information',
        'desc' => 'Business profile, contact details, tax numbers, and logo',
        'icon' => 'domain',
        'color' => 'bg-emerald-500'
    ],
    'invoice' => [
        'title' => 'Invoice & Finance',
        'desc' => 'Document numbering prefixes, tax rates, terms, and footers',
        'icon' => 'receipt_long',
        'color' => 'bg-amber-500'
    ],
    'email' => [
        'title' => 'Email & SMTP',
        'desc' => 'Outbound mail server configuration and sender credentials',
        'icon' => 'mail',
        'color' => 'bg-sky-500'
    ],
    'tax' => [
        'title' => 'Tax & Rates',
        'desc' => 'VAT and sales tax rules',
        'icon' => 'percent',
        'color' => 'bg-purple-500'
    ]
];

$groups = array_keys($settings);
$firstGroup = reset($groups) ?: 'general';
?>

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-2xl">settings</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">System Settings</h1>
                    <p class="text-sm text-slate-500">Configure core preferences, business identity, and system defaults</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System Operational
            </span>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form id="settingsForm" enctype="multipart/form-data">
        <!-- Dynamic Tabs Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-2 mb-6">
            <div class="flex flex-wrap gap-2">
                <?php foreach ($groups as $index => $groupKey): ?>
                    <?php 
                    $meta = $groupMeta[$groupKey] ?? [
                        'title' => ucfirst($groupKey),
                        'desc' => 'Settings for ' . $groupKey,
                        'icon' => 'extension',
                        'color' => 'bg-slate-500'
                    ];
                    $isActive = ($groupKey === $firstGroup);
                    ?>
                    <button type="button" 
                            class="settings-tab group flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 cursor-pointer <?= $isActive ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' ?>"
                            data-tab="<?= htmlspecialchars($groupKey) ?>">
                        <span class="material-symbols-outlined text-lg transition-transform group-hover:scale-110"><?= htmlspecialchars($meta['icon']) ?></span>
                        <span><?= htmlspecialchars($meta['title']) ?></span>
                        <span class="ml-1 px-2 py-0.5 rounded-full text-[11px] <?= $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200' ?>">
                            <?= count($settings[$groupKey]) ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Panels Container -->
        <div class="settings-panels">
            <?php foreach ($settings as $groupKey => $items): ?>
                <?php 
                $meta = $groupMeta[$groupKey] ?? [
                    'title' => ucfirst($groupKey) . ' Settings',
                    'desc' => 'Configure parameters for ' . $groupKey,
                    'icon' => 'tune'
                ];
                $isShown = ($groupKey === $firstGroup);
                ?>
                <div class="settings-panel transition-opacity duration-300 <?= $isShown ? 'block' : 'hidden' ?>" data-panel="<?= htmlspecialchars($groupKey) ?>">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                        <!-- Panel Header -->
                        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white shadow-sm border border-slate-200/60 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-xl"><?= htmlspecialchars($meta['icon']) ?></span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base"><?= htmlspecialchars($meta['title']) ?></h3>
                                    <p class="text-xs text-slate-500"><?= htmlspecialchars($meta['desc']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Body Grid -->
                        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($items as $setting): ?>
                                <?php
                                $val = $setting['setting_value'] ?? '';
                                $key = $setting['setting_key'];
                                $name = "settings[{$key}]";
                                $type = $setting['setting_type'] ?? 'text';
                                $desc = $setting['description'] ?? null;
                                $label = $setting['label'] ?? ucwords(str_replace('_', ' ', $key));
                                $rawOptions = $setting['options'] ?? null;
                                $options = [];

                                if ($rawOptions) {
                                    $decoded = json_decode($rawOptions, true);
                                    if (is_array($decoded)) {
                                        $options = $decoded;
                                    }
                                }
                                
                                // Textareas & files take full row width
                                $isFullWidth = in_array($type, ['textarea', 'file']);
                                ?>
                                <div class="<?= $isFullWidth ? 'md:col-span-2' : 'col-span-1' ?> bg-slate-50/40 p-4 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block text-sm font-semibold text-slate-800">
                                            <?= htmlspecialchars($label) ?>
                                        </label>
                                        <span class="text-[11px] font-mono text-slate-400">key: <?= htmlspecialchars($key) ?></span>
                                    </div>
                                    
                                    <?php if ($desc): ?>
                                        <p class="text-xs text-slate-500 mb-3 leading-relaxed"><?= htmlspecialchars($desc) ?></p>
                                    <?php endif; ?>

                                    <!-- Render Control based on type -->
                                    <?php if ($type === 'select'): ?>
                                        <div class="relative">
                                            <select name="<?= $name ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer text-slate-800 font-medium pr-10 shadow-sm">
                                                <?php foreach ($options as $optVal => $optLabel): ?>
                                                    <option value="<?= htmlspecialchars($optVal) ?>" <?= (string)$val === (string)$optVal ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($optLabel) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                                <span class="material-symbols-outlined text-lg">expand_more</span>
                                            </div>
                                        </div>

                                    <?php elseif ($type === 'boolean'): ?>
                                        <div class="flex items-center gap-4 mt-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="<?= $name ?>" value="1" <?= $val == '1' ? 'checked' : '' ?> class="sr-only peer">
                                                <div class="px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all shadow-sm">
                                                    Yes / Enabled
                                                </div>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="<?= $name ?>" value="0" <?= $val == '0' ? 'checked' : '' ?> class="sr-only peer">
                                                <div class="px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all shadow-sm">
                                                    No / Disabled
                                                </div>
                                            </label>
                                        </div>

                                    <?php elseif ($type === 'textarea'): ?>
                                        <textarea name="<?= $name ?>" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-800 shadow-sm resize-y" placeholder="Enter <?= htmlspecialchars(strtolower($label)) ?>..."><?= htmlspecialchars($val) ?></textarea>

                                    <?php elseif ($type === 'file'): ?>
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                                            <div class="w-24 h-24 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0 relative group" id="previewContainer_<?= htmlspecialchars($key) ?>">
                                                <?php if (!empty($val) && file_exists(BASE_PATH . '/' . $val)): ?>
                                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($val) ?>" alt="Logo" class="w-full h-full object-contain p-2" id="previewImg_<?= htmlspecialchars($key) ?>">
                                                <?php else: ?>
                                                    <div class="flex flex-col items-center text-slate-400" id="previewPlaceholder_<?= htmlspecialchars($key) ?>">
                                                        <span class="material-symbols-outlined text-3xl">image</span>
                                                        <span class="text-[10px]">No Logo</span>
                                                    </div>
                                                    <img src="" alt="Preview" class="w-full h-full object-contain p-2 hidden" id="previewImg_<?= htmlspecialchars($key) ?>">
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="space-y-2 flex-1">
                                                <input type="file" name="settings[<?= htmlspecialchars($key) ?>]" id="file_<?= htmlspecialchars($key) ?>" accept="image/*" class="hidden" onchange="previewSettingImage(this, '<?= htmlspecialchars($key) ?>')">
                                                <label for="file_<?= htmlspecialchars($key) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors cursor-pointer">
                                                    <span class="material-symbols-outlined text-base">cloud_upload</span>
                                                    Choose Image File
                                                </label>
                                                <p class="text-[11px] text-slate-400">Supported formats: PNG, JPG, WEBP, SVG (Max size 2MB)</p>
                                            </div>
                                        </div>

                                    <?php elseif ($type === 'number'): ?>
                                        <input type="number" name="<?= $name ?>" value="<?= htmlspecialchars($val) ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-800 shadow-sm" step="any">

                                    <?php elseif ($type === 'email'): ?>
                                        <input type="email" name="<?= $name ?>" value="<?= htmlspecialchars($val) ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-800 shadow-sm" placeholder="email@domain.com">

                                    <?php else: ?>
                                        <input type="text" name="<?= $name ?>" value="<?= htmlspecialchars($val) ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-800 shadow-sm">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sticky Bottom Action Footer -->
        <div class="sticky bottom-4 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/80 shadow-lg px-6 py-4 flex items-center justify-between z-30">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="material-symbols-outlined text-base text-amber-500">info</span>
                <span>Changes will take effect system-wide immediately after saving.</span>
            </div>
            
            <button type="submit" id="saveSettingsBtn" class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-7 py-3 rounded-xl font-semibold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-lg">save</span>
                <span>Save All Settings</span>
            </button>
        </div>
    </form>
</div>

<!-- Custom Toast Notification Container -->
<div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>

<script>
// Tab Switching Animation & Handling
document.querySelectorAll('.settings-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const tabName = this.getAttribute('data-tab');

        // Reset tab button states
        document.querySelectorAll('.settings-tab').forEach(t => {
            t.classList.remove('bg-primary', 'text-white', 'shadow-md', 'shadow-primary/20');
            t.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-100/80');
            
            const badge = t.querySelector('span:last-child');
            if (badge) {
                badge.classList.remove('bg-white/20', 'text-white');
                badge.classList.add('bg-slate-100', 'text-slate-600');
            }
        });
        
        // Active tab button state
        this.classList.add('bg-primary', 'text-white', 'shadow-md', 'shadow-primary/20');
        this.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-100/80');
        const activeBadge = this.querySelector('span:last-child');
        if (activeBadge) {
            activeBadge.classList.add('bg-white/20', 'text-white');
            activeBadge.classList.remove('bg-slate-100', 'text-slate-600');
        }
        
        // Switch Panels
        document.querySelectorAll('.settings-panel').forEach(panel => {
            panel.classList.add('hidden');
            panel.classList.remove('block');
        });

        const activePanel = document.querySelector(`.settings-panel[data-panel="${tabName}"]`);
        if (activePanel) {
            activePanel.classList.remove('hidden');
            activePanel.classList.add('block');
        }
    });
});

// Image Preview Helper
function previewSettingImage(input, key) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('previewImg_' + key);
            const placeholder = document.getElementById('previewPlaceholder_' + key);
            
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Toast Notification Function
function triggerToast(message, type = 'success') {
    if (typeof window.showToast === 'function') {
        window.showToast(message, type);
    }
}

// AJAX Form Submit with File Support
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = document.getElementById('saveSettingsBtn');
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">refresh</span><span>Saving Changes...</span>';
    btn.disabled = true;
    
    fetch('<?= BASE_URL ?>/settings/save', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            triggerToast(data.message || 'Settings saved successfully!', 'success');
        } else {
            triggerToast(data.message || 'Error saving settings.', 'error');
        }
    })
    .catch(error => {
        console.error('Settings save error:', error);
        triggerToast('An unexpected error occurred while saving.', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
});
</script>