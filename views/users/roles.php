<?php $hide_title = true; ?>

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Roles & Permissions Matrix</h1>
                <p class="text-sm text-slate-500">Configure role-based access control (RBAC) and fine-grained system capabilities</p>
            </div>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/users" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Roles List Column -->
        <div class="lg:col-span-1 space-y-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-2">Select User Role</h3>
                <div class="space-y-1.5" id="rolesList">
                    <?php foreach ($roles as $role): ?>
                        <button type="button" 
                                onclick="selectRole(<?= $role['id'] ?>, '<?= htmlspecialchars(addslashes($role['display_name'])) ?>', this)" 
                                class="role-btn w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-all text-left group border border-transparent hover:border-slate-200">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-lg text-slate-400 group-hover:text-primary transition-colors">shield</span>
                                <span><?= htmlspecialchars($role['display_name']) ?></span>
                            </div>
                            <span class="material-symbols-outlined text-base text-slate-300 group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-indigo-50/70 rounded-2xl border border-indigo-100 p-4 text-xs text-indigo-950 space-y-2">
                <div class="flex items-center gap-2 font-bold text-indigo-900">
                    <span class="material-symbols-outlined text-base text-indigo-600">verified_user</span>
                    <span>Admin Privilege</span>
                </div>
                <p class="leading-relaxed text-[11px] text-indigo-700">
                    Users assigned to the <strong>Administrator</strong> role bypass restriction checks and maintain full unrestricted system access.
                </p>
            </div>
        </div>

        <!-- Permissions Matrix Column -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden min-h-[450px] flex flex-col" id="permissionsCard">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Managing Permissions For</span>
                        <h2 class="text-lg font-bold text-slate-900" id="selectedRoleTitle">Select a Role</h2>
                    </div>

                    <div class="flex items-center gap-2 hidden" id="matrixActions">
                        <button type="button" onclick="selectAllPerms()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Select All
                        </button>
                        <button type="button" onclick="deselectAllPerms()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Deselect All
                        </button>
                    </div>
                </div>

                <!-- Body Matrix -->
                <form id="matrixForm" class="p-6 flex-1 flex flex-col justify-between">
                    <div id="permissionsContainer" class="space-y-6">
                        <div class="flex flex-col items-center justify-center py-20 text-slate-400 space-y-3">
                            <span class="material-symbols-outlined text-5xl text-slate-300">touch_app</span>
                            <p class="text-sm font-medium text-slate-500">Click a role from the left menu to view & configure its permission capabilities.</p>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end hidden" id="matrixFooter">
                        <button type="submit" id="savePermsBtn" class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-7 py-2.5 rounded-xl font-semibold shadow-md shadow-primary/20 transition-all">
                            <span class="material-symbols-outlined text-base">save</span>
                            <span>Save Role Permissions</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentRoleId = null;
const allPermissions = <?= json_encode($grouped_permissions) ?>;

function selectRole(roleId, roleName, btn) {
    currentRoleId = roleId;
    
    // Update button states
    document.querySelectorAll('.role-btn').forEach(b => {
        b.classList.remove('bg-primary', 'text-white', 'shadow-md', 'shadow-primary/20', 'border-primary');
        b.classList.add('text-slate-700', 'hover:bg-slate-100');
        const icon = b.querySelector('.material-symbols-outlined:first-child');
        if (icon) icon.classList.replace('text-white', 'text-slate-400');
    });

    if (btn) {
        btn.classList.add('bg-primary', 'text-white', 'shadow-md', 'shadow-primary/20', 'border-primary');
        btn.classList.remove('text-slate-700', 'hover:bg-slate-100');
        const icon = btn.querySelector('.material-symbols-outlined:first-child');
        if (icon) icon.classList.replace('text-slate-400', 'text-white');
    }

    document.getElementById('selectedRoleTitle').textContent = roleName;
    document.getElementById('matrixActions').classList.remove('hidden');
    document.getElementById('matrixFooter').classList.remove('hidden');

    fetchPermissionsForRole(roleId);
}

function fetchPermissionsForRole(roleId) {
    const container = document.getElementById('permissionsContainer');
    container.innerHTML = '<div class="flex items-center justify-center py-16 text-slate-400"><span class="material-symbols-outlined text-2xl animate-spin mr-2">refresh</span> Loading permissions...</div>';

    fetch('<?= BASE_URL ?>/users/getRolePermissions/' + roleId)
        .then(r => r.json())
        .then(assignedPerms => {
            const activePermIds = (assignedPerms || []).map(p => parseInt(p));
            renderPermissionsMatrix(activePermIds);
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<div class="p-4 bg-rose-50 text-rose-600 text-xs rounded-xl">Failed to load permissions.</div>';
        });
}

function renderPermissionsMatrix(assignedPermIds) {
    const container = document.getElementById('permissionsContainer');
    let html = '';

    for (const [moduleName, perms] of Object.entries(allPermissions)) {
        html += `
            <div class="bg-slate-50/60 rounded-xl border border-slate-200/80 overflow-hidden">
                <div class="px-4 py-3 bg-slate-100/70 border-b border-slate-200/80 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base text-primary">folder_open</span>
                        ${moduleName.replace('_', ' ')}
                    </span>
                    <span class="text-[10px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded-full border border-slate-200">${perms.length} capabilities</span>
                </div>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        `;

        perms.forEach(p => {
            const isChecked = assignedPermIds.includes(parseInt(p.id)) ? 'checked' : '';
            html += `
                <label class="flex items-center gap-2.5 p-2.5 bg-white rounded-lg border border-slate-200/70 hover:border-primary/40 transition-colors cursor-pointer group">
                    <input type="checkbox" name="permissions[]" value="${p.id}" ${isChecked} class="w-4 h-4 rounded text-primary focus:ring-primary/20 cursor-pointer">
                    <span class="text-xs font-medium text-slate-700 group-hover:text-slate-900">${p.display_name}</span>
                </label>
            `;
        });

        html += `
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

function selectAllPerms() {
    document.querySelectorAll('#matrixForm input[type="checkbox"]').forEach(c => c.checked = true);
}

function deselectAllPerms() {
    document.querySelectorAll('#matrixForm input[type="checkbox"]').forEach(c => c.checked = false);
}

document.getElementById('matrixForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!currentRoleId) return;

    const btn = document.getElementById('savePermsBtn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Saving...';
    btn.disabled = true;

    const formData = new FormData(this);

    fetch('<?= BASE_URL ?>/users/updateRolePermissions/' + currentRoleId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Role permissions updated successfully!', 'success');
        } else {
            showToast(data.message || 'Error updating permissions.', 'error');
        }
    })
    .catch(() => showToast('Error saving permissions.', 'error'))
    .finally(() => {
        btn.innerHTML = orig;
        btn.disabled = false;
    });
});

// Auto select first role on load
document.addEventListener('DOMContentLoaded', () => {
    const firstRole = document.querySelector('.role-btn');
    if (firstRole) {
        firstRole.click();
    }
});
</script>