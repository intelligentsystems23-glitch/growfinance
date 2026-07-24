<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Journal Entries</h1>
        <p class="text-sm text-slate-500 mt-0.5">Record and manage general ledger entries</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>/accounting/createJournal" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all text-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            New Journal
        </a>
        <a href="<?= BASE_URL ?>/accounting" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-4 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-4">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">From Date</label>
            <input type="date" name="date_from" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($_GET['date_from'] ?? date('Y-m-01')) ?>">
        </div>
        <div class="md:col-span-4">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">To Date</label>
            <input type="date" name="date_to" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600" value="<?= htmlspecialchars($_GET['date_to'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Status</label>
            <div class="relative">
                <select name="status" class="w-full px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">All</option>
                    <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="posted" <?= ($_GET['status'] ?? '') == 'posted' ? 'selected' : '' ?>>Posted</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
        </div>
    </form>
</div>

<!-- Journal Entries Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-2">Journal #</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Reference</th>
                    <th class="px-4 py-2 text-right">Debit</th>
                    <th class="px-4 py-2 text-right">Credit</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2">Created By</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($journals as $journal): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-2">
                        <a href="<?= BASE_URL ?>/accounting/viewJournal/<?= $journal['id'] ?>" class="font-semibold text-primary hover:underline">
                            <?= htmlspecialchars($journal['journal_number']) ?>
                        </a>
                    </td>
                    <td class="px-4 py-2 text-slate-600"><?= date('d/m/Y', strtotime($journal['journal_date'])) ?></td>
                    <td class="px-4 py-2 text-slate-500 truncate max-w-[200px]" title="<?= htmlspecialchars($journal['description']) ?>"><?= htmlspecialchars($journal['description'] ?? '-') ?></td>
                    <td class="px-4 py-2 text-slate-600 font-medium"><?= htmlspecialchars($journal['reference_type'] ?? '-') ?></td>
                    <td class="px-4 py-2 text-right font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($journal['total_debit'], 2) ?></td>
                    <td class="px-4 py-2 text-right font-semibold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($journal['total_credit'], 2) ?></td>
                    <td class="px-4 py-2 text-center">
                        <?php
                        $status_colors = ['draft'=>'bg-amber-50 text-amber-600 border-amber-200','posted'=>'bg-green-50 text-green-600 border-green-200','void'=>'bg-red-50 text-red-600 border-red-200'];
                        $color_class = $status_colors[$journal['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                            <?= ucfirst($journal['status']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-slate-600"><?= htmlspecialchars($journal['created_by_name'] ?? '-') ?></td>
                    <td class="px-4 py-2 text-center flex items-center justify-center gap-1.5">
                        <a href="<?= BASE_URL ?>/accounting/viewJournal/<?= $journal['id'] ?>" class="inline-flex items-center justify-center p-1 bg-white border border-slate-200 text-slate-600 rounded-md hover:bg-slate-50 transition-colors shadow-sm" title="View">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                        <?php if ($journal['status'] == 'draft'): ?>
                        <button onclick="postJournal(<?= $journal['id'] ?>)" class="inline-flex items-center justify-center p-1 bg-green-50 border border-green-200 text-green-600 rounded-md hover:bg-green-100/50 transition-colors" title="Post Journal">
                            <span class="material-symbols-outlined text-sm">done</span>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($journals)): ?>
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center py-6">
                            <span class="material-symbols-outlined text-3xl mb-2 opacity-50">book</span>
                            <p class="text-sm font-medium">No journal entries found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function postJournal(id) {
    if (confirm('Post this journal entry? This action cannot be undone.')) {
        $.ajax({
            url: '<?= BASE_URL ?>/accounting/postJournal/' + id,
            type: 'POST',
            success: function(response) {
                if (response.success) location.reload();
                else alert('Error: ' + response.message);
            }
        });
    }
}
</script>