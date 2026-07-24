<!-- Title Section -->
<div class="max-w-3xl mx-auto mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Transfer' ?></h1>
            <p class="text-xs text-slate-500">Transfer funds between bank accounts.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/banking/transfers" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="max-w-3xl mx-auto bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="transferForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- From Account -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">From Account *</label>
                <div class="relative">
                    <select name="from_account_id" id="from_account" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="">Select Account</option>
                        <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>" data-balance="<?= $acc['current_balance'] ?>" data-name="<?= htmlspecialchars($acc['account_name']) ?>">
                            <?= htmlspecialchars($acc['account_name']) ?> (Balance: <?= htmlspecialchars($currency) ?> <?= number_format($acc['current_balance'], 2) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
            <!-- To Account -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">To Account *</label>
                <div class="relative">
                    <select name="to_account_id" id="to_account" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 pl-3 pr-8 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none" required>
                        <option value="">Select Account</option>
                        <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>" data-name="<?= htmlspecialchars($acc['account_name']) ?>">
                            <?= htmlspecialchars($acc['account_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base">expand_more</span>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Transfer Date -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Transfer Date *</label>
                <input type="date" name="transfer_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>" required>
            </div>
            <!-- Amount -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Amount *</label>
                <input type="number" name="amount" id="transfer_amount" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" step="0.01" min="0.01" required>
            </div>
            <!-- Fee -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Fee (if any)</label>
                <input type="number" name="fee" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" step="0.01" min="0" value="0">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Reference Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Reference Number</label>
                <input type="text" name="reference_number" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Optional reference">
            </div>
        </div>
        
        <!-- Description (Full Width) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Description</label>
            <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md p-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y" rows="3" placeholder="Transfer description"></textarea>
        </div>
        
        <input type="hidden" name="from_account_name" id="from_account_name">
        <input type="hidden" name="to_account_name" id="to_account_name">
        
        <!-- Summary Box -->
        <div class="p-3 bg-blue-50 text-blue-800 rounded-md text-xs font-medium space-y-1" id="transferSummary" style="display: none;">
            <div class="font-bold text-sm mb-1">Transfer Summary</div>
            <div>From: <span id="summary_from" class="font-semibold text-slate-800"></span></div>
            <div>To: <span id="summary_to" class="font-semibold text-slate-800"></span></div>
            <div>Amount: <span class="font-bold text-slate-900">$<span id="summary_amount"></span></span></div>
        </div>
        
        <div class="border-t border-slate-100 my-4"></div>
        
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">swap_horiz</span>
                <span>Transfer Funds</span>
            </button>
            <a href="<?= BASE_URL ?>/banking/transfers" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all">Cancel</a>
        </div>
    </form>
</div>

<script>
$('#from_account, #to_account').change(function() {
    const fromSelect = $('#from_account option:selected');
    const toSelect = $('#to_account option:selected');
    
    $('#from_account_name').val(fromSelect.data('name'));
    $('#to_account_name').val(toSelect.data('name'));
    
    if ($('#from_account').val() && $('#to_account').val() && $('#transfer_amount').val()) {
        $('#summary_from').text(fromSelect.data('name'));
        $('#summary_to').text(toSelect.data('name'));
        $('#summary_amount').text($('#transfer_amount').val());
        $('#transferSummary').show();
    }
});

$('#transfer_amount').on('input', function() {
    if ($('#from_account').val() && $('#to_account').val() && $(this).val()) {
        $('#summary_amount').text($(this).val());
    }
});

$('#transferForm').submit(function(e) {
    e.preventDefault();
    
    const fromAccount = $('#from_account option:selected');
    const balance = fromAccount.data('balance');
    const amount = parseFloat($('#transfer_amount').val());
    const fee = parseFloat($('input[name="fee"]').val()) || 0;
    
    if (amount + fee > balance) {
        alert('Insufficient funds in the source account.');
        return false;
    }
    
    if ($('#from_account').val() === $('#to_account').val()) {
        alert('Source and destination accounts cannot be the same.');
        return false;
    }
    
    if (!confirm('Are you sure you want to transfer $' + amount.toFixed(2) + '?')) {
        return false;
    }
    
    $.ajax({
        url: '<?= BASE_URL ?>/banking/createTransfer',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                window.location.href = '<?= BASE_URL ?>/banking/transfers';
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});
</script>