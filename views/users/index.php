<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">User Management</h1>
        <p class="text-sm text-slate-500 mt-1">Manage system users, roles, and permissions</p>
    </div>
    <div class="flex items-center gap-3">
        <?php if (has_permission('roles.manage')): ?>
        <a href="<?= BASE_URL ?? '' ?>/users/roles" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2.5 rounded-xl font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors">
            <span class="material-symbols-outlined text-sm">lock</span>
            Roles & Permissions
        </a>
        <?php endif; ?>

        <?php if (is_admin()): ?>
        <a href="<?= BASE_URL ?? '' ?>/users/create" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            Add User
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-5">
            <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search by username, email, or full name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="is_active" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="1" <?= ($_GET['is_active'] ?? '') == '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= ($_GET['is_active'] ?? '') == '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="md:col-span-4 flex gap-2">
            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Apply Filters
            </button>
            <a href="<?= BASE_URL ?? '' ?>/users" class="flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors" title="Clear Filters">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Full Name</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Roles</th>
                    <th class="px-6 py-4">Last Login</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($users ?? [])): ?>
                <tr>
                    <td colspan="8">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">people</span>
                            <p class="text-base font-medium text-slate-500">No users found</p>
                            <p class="text-sm mt-1">Click "Add User" to create a new user account</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <a href="<?= BASE_URL ?? '' ?>/users/edit/<?= $user['id'] ?>" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            <?= htmlspecialchars($user['username']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($user['full_name']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($user['phone'] ?: '-') ?></td>
                    <td class="px-6 py-4">
                        <?php if (!empty($user['roles'])): ?>
                            <div class="flex flex-wrap gap-1">
                            <?php foreach (explode(',', $user['roles']) as $role): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-200">
                                    <?= htmlspecialchars(trim($role)) ?>
                                </span>
                            <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-slate-400 text-xs">No roles</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-slate-500">
                        <?php if ($user['last_login']): ?>
                            <span title="<?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>">
                                <?= date('d/m/Y', strtotime($user['last_login'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-slate-400">Never</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($user['is_active']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-50 text-green-600 border-green-200">Active</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-red-50 text-red-600 border-red-200">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if (is_admin()): ?>
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= BASE_URL ?? '' ?>/users/edit/<?= $user['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <?php if ($user['id'] != ($_SESSION['user_id'] ?? 1)): ?>
                            <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>')" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-red-500 transition-colors shadow-sm" title="Deactivate">
                                <span class="material-symbols-outlined text-sm">block</span>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                            <span class="text-slate-400 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tailwind Deactivation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="deleteBackdrop"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="deletePanel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 sm:my-8 sm:w-full sm:max-w-sm">
                <div class="px-6 py-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 flex-shrink-0">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Confirm Deactivation</h3>
                            <p class="text-sm text-slate-500">Are you sure you want to deactivate <strong id="deleteUserName" class="text-slate-900"></strong>?</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 bg-slate-50 px-3 py-2 rounded-lg">The user will no longer be able to log in.</p>
                </div>
                <div class="border-t border-slate-100 px-6 py-4 bg-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-medium hover:bg-red-600 shadow-sm shadow-red-500/30 transition-all">Deactivate</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let userIdToDelete = null;

function deleteUser(id, username) {
    userIdToDelete = id;
    document.getElementById('deleteUserName').textContent = username;
    
    const modal = document.getElementById('deleteModal');
    const backdrop = document.getElementById('deleteBackdrop');
    const panel = document.getElementById('deletePanel');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const backdrop = document.getElementById('deleteBackdrop');
    const panel = document.getElementById('deletePanel');
    
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (userIdToDelete) {
        fetch('<?= BASE_URL ?? '' ?>/users/delete/' + userIdToDelete, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to deactivate user'));
            }
        })
        .catch(err => {
            alert('An error occurred');
            console.error(err);
        })
        .finally(() => closeDeleteModal());
    }
});
</script>

<style>
/* Remove Bootstrap styles that are no longer needed */
</style>