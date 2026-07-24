<?php $hide_title = true; ?>

<?php if (!$user): ?>
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-semibold">User account not found.</div>
    <?php return; ?>
<?php endif; ?>

<!-- Title Section -->
<div class="max-w-2xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">manage_accounts</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit User - <?= htmlspecialchars($user['username']) ?></h1>
            <p class="text-xs text-slate-500">Update account credentials, contact info, and assigned roles.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/users" class="px-3.5 py-1.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
    <form id="userEditForm" class="space-y-4">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Username -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Username *</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
            </div>

            <!-- Full Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Full Name *</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
            </div>

            <!-- Phone -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Password</label>
                <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Leave blank to keep current">
                <p class="text-[10px] text-slate-400">Leave empty if you don't want to change password.</p>
            </div>

            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Account Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none cursor-pointer">
                        <option value="1" <?= $user['is_active'] ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= !$user['is_active'] ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="space-y-1.5 pt-2">
            <label class="block text-xs font-semibold text-slate-600">Assigned Roles</label>
            <div class="border border-slate-200/80 rounded-xl p-4 bg-slate-50/50 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <?php foreach ($roles as $role): ?>
                <label class="flex items-center gap-2.5 p-2 bg-white rounded-lg border border-slate-200/70 hover:border-primary/40 cursor-pointer transition-colors">
                    <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" class="w-4 h-4 rounded text-primary focus:ring-primary/20" id="role_<?= $role['id'] ?>" <?= in_array($role['id'], $user['role_ids'] ?? []) ? 'checked' : '' ?>>
                    <span class="text-xs font-semibold text-slate-700"><?= htmlspecialchars($role['display_name']) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="border-t border-slate-100 my-4"></div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-semibold transition-all shadow-md shadow-primary/20 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">save</span>
                <span>Save Changes</span>
            </button>
            <a href="<?= BASE_URL ?>/users" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('userEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Saving...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/users/edit/<?= $user['id'] ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= BASE_URL ?>/users';
        } else {
            alert('Error: ' + (data.message || 'Failed to update user'));
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('An error occurred while updating user.');
        console.error(err);
        btn.innerHTML = orig;
        btn.disabled = false;
    });
});
</script>