<!-- Title Section -->
<div class="max-w-2xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">person_add</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Add User' ?></h1>
            <p class="text-xs text-slate-500">Register a new system user and assign roles.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/users" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="userForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Username -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Username *</label>
                <input type="text" name="username" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="e.g., john_doe">
            </div>
            
            <!-- Full Name -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Full Name *</label>
                <input type="text" name="full_name" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="e.g., John Doe">
            </div>
            
            <!-- Email -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Email *</label>
                <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="e.g., john.doe@example.com">
            </div>
            
            <!-- Phone -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Phone</label>
                <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="e.g., +1 555-0199">
            </div>
            
            <!-- Password -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Password *</label>
                <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="Minimum 6 characters">
            </div>
            
            <!-- Status -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600">Status</label>
                <div class="relative">
                    <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <!-- Roles (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600">Roles</label>
            <div class="border border-slate-200 rounded-md p-3.5 bg-slate-50/50 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <?php foreach ($roles as $role): ?>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20 focus:ring-2" id="role_<?= $role['id'] ?>">
                    <label class="text-xs font-medium text-slate-700 cursor-pointer" for="role_<?= $role['id'] ?>">
                        <strong><?= htmlspecialchars($role['display_name']) ?></strong>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Save User</span>
            </button>
            <a href="<?= BASE_URL ?>/users" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const origText = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">refresh</span> Saving...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/users/create', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= BASE_URL ?>/users';
        } else {
            alert('Error: ' + (data.message || 'Failed to create user'));
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('An error occurred while creating user.');
        console.error(err);
        btn.innerHTML = origText;
        btn.disabled = false;
    });
});
</script>