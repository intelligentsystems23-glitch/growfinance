<?php $hide_title = true; ?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200/80 shadow-xl p-8 text-center space-y-6 relative overflow-hidden">
        <!-- Background Decorative Accent -->
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- 403 Icon Shield -->
        <div class="mx-auto w-20 h-20 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-inner">
            <span class="material-symbols-outlined text-4xl">lock</span>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 uppercase tracking-widest">
                403 Access Denied
            </span>
            <h1 class="text-2xl font-bold text-slate-900">Restricted Permission</h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                You do not have the required permissions to view or perform actions on this section.
            </p>
            <?php if (!empty($required_perm)): ?>
                <div class="mt-3 p-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-mono text-slate-600">
                    Required Permission: <span class="font-bold text-rose-600"><?= htmlspecialchars($required_perm) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?= BASE_URL ?>/dashboard" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white text-xs font-semibold rounded-xl shadow-lg shadow-primary/25 hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-base">dashboard</span>
                <span>Return to Dashboard</span>
            </a>
            <button onclick="history.back()" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-200 bg-white text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-all">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Go Back</span>
            </button>
        </div>
    </div>
</div>
