<?php if (!$journal): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">Journal entry not found</div>
    <?php return; ?>
<?php endif; ?>

<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Journal Entry <?= htmlspecialchars($journal['journal_number']) ?></h1>
        <p class="text-sm text-slate-500 mt-0.5">Details and items for general ledger voucher</p>
    </div>
    <div class="flex items-center gap-2">
        <?php if ($journal['status'] == 'draft'): ?>
        <button onclick="postJournal(<?= $journal['id'] ?>)" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-green-700 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">done</span>
            <span>Post Entry</span>
        </button>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/accounting/journalEntries" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-lg font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    <!-- Voucher Details -->
    <div class="lg:col-span-4 bg-white rounded-lg border border-slate-200 shadow-sm p-4 h-fit">
        <h3 class="font-bold text-slate-900 text-sm mb-3">Voucher Details</h3>
        <div class="space-y-2 text-xs">
            <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Date:</span> <span class="font-medium text-slate-800"><?= date('d/m/Y', strtotime($journal['journal_date'])) ?></span></div>
            <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Status:</span> 
                <?php
                $status_colors = ['draft'=>'bg-amber-50 text-amber-600 border-amber-200','posted'=>'bg-green-50 text-green-600 border-green-200','void'=>'bg-red-50 text-red-600 border-red-200'];
                $color_class = $status_colors[$journal['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border <?= $color_class ?>">
                    <?= ucfirst($journal['status']) ?>
                </span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Created By:</span> <span class="font-medium text-slate-800"><?= htmlspecialchars($journal['created_by_name'] ?? '-') ?></span></div>
            <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Created At:</span> <span class="font-medium text-slate-800"><?= date('d/m/Y H:i', strtotime($journal['created_at'])) ?></span></div>
            <div class="py-1">
                <span class="text-slate-400 block mb-1">Description:</span>
                <p class="text-slate-700 bg-slate-50 p-2.5 rounded border border-slate-100 leading-relaxed"><?= htmlspecialchars($journal['description']) ?></p>
            </div>
        </div>
    </div>

    <!-- Voucher Items Table -->
    <div class="lg:col-span-8 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden h-fit">
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-semibold text-slate-900 text-sm">Ledger Postings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2">Account</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2 text-right">Debit</th>
                        <th class="px-4 py-2 text-right">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($items as $item): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-2 font-medium text-slate-900"><?= htmlspecialchars($item['account_code']) ?> - <?= htmlspecialchars($item['account_name']) ?></td>
                        <td class="px-4 py-2 text-slate-500"><?= htmlspecialchars($item['description'] ?? '-') ?></td>
                        <td class="px-4 py-2 text-right font-semibold text-slate-700"><?= $item['debit'] > 0 ? htmlspecialchars($currency) . ' ' . number_format($item['debit'], 2) : '-' ?></td>
                        <td class="px-4 py-2 text-right font-semibold text-slate-700"><?= $item['credit'] > 0 ? htmlspecialchars($currency) . ' ' . number_format($item['credit'], 2) : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-slate-50/50 font-bold border-t border-slate-200 text-slate-900">
                    <tr>
                        <td colspan="2" class="px-4 py-2.5 text-right uppercase tracking-wider">Total:</td>
                        <td class="px-4 py-2.5 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($journal['total_debit'], 2) ?></td>
                        <td class="px-4 py-2.5 text-right font-bold text-slate-900"><?= htmlspecialchars($currency) ?> <?= number_format($journal['total_credit'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
function postJournal(id) {
    if (confirm('Post this journal entry? This will update account balances and cannot be undone.')) {
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