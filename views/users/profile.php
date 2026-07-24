<?php $hide_title = true; ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div class="h-16 w-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-2xl border border-primary/20 shadow-inner">
            <?= htmlspecialchars(strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1))) ?>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($user['full_name']) ?></h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-semibold text-slate-500">@<?= htmlspecialchars($user['username']) ?></span>
                <span class="text-slate-300">•</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200/80 uppercase tracking-wider">
                    <?= htmlspecialchars($_SESSION['role'] ?? 'employee') ?>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Edit Profile Form -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">person</span>
                <span>Personal Profile Settings</span>
            </h3>

            <form id="profileForm" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Username *</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Full Name *</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Email Address *</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-600">Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-1.5 pt-2">
                    <label class="block text-xs font-semibold text-slate-600">New Password</label>
                    <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Leave blank to keep existing password">
                    <p class="text-[11px] text-slate-400">Only fill this out if you wish to change your login password.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" id="saveProfileBtn" class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md shadow-primary/20 transition-all text-xs">
                        <span class="material-symbols-outlined text-base">save</span>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Info Summary Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-xl">info</span>
                    <span>Account Security</span>
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Last Login Session</span>
                        <p class="font-semibold text-slate-800">
                            <?= !empty($user['last_login']) ? date('d-M-Y H:i A', strtotime($user['last_login'])) : 'First Session' ?>
                        </p>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Login IP Address</span>
                        <p class="font-semibold text-slate-800">
                            <?= htmlspecialchars($user['last_login_ip'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Localhost') ?>
                        </p>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Account Creation Date</span>
                        <p class="font-semibold text-slate-800">
                            <?= !empty($user['created_at']) ? date('d-M-Y', strtotime($user['created_at'])) : date('d-M-Y') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProfileBtn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Saving...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/users/profile', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Profile updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error updating profile.', 'error');
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(err => {
        showToast('Error saving profile.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
    });
});
</script>