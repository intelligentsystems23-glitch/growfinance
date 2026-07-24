<!-- Title Section -->
<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
            <span class="material-symbols-outlined text-xl">add_circle</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900"><?= $title ?? 'Create New Entry' ?></h1>
            <p class="text-xs text-slate-500">Configure new system registration details.</p>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/accounting/journalEntries" class="px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Back</span>
    </a>
</div>

<div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
    <form id="journalForm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <div class="space-y-1.5 mb-3.5">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Journal Date *</label>
                    <input type="date" name="journal_date" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            <div class="md:col-span-3">
                <div class="space-y-1.5 mb-3.5">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Description *</label>
                    <input type="text" name="description" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1.5 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required placeholder="Journal entry description">
                </div>
            </div>
        </div>
        
        <h5 class="mt-3">Journal Items</h5>
        <div class="table-responsive">
            <table class="w-full text-left text-sm border-collapse" id="itemsTable">
                <thead>
                    <tr>
                        <th class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 pb-2" style="width: 30%;">Account</th>
                        <th class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 pb-2" style="width: 35%;">Description</th>
                        <th class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 pb-2" style="width: 15%;">Debit</th>
                        <th class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 pb-2" style="width: 15%;">Credit</th>
                        <th class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 pb-2" style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition-all inline-flex items-center gap-1" onclick="addItemRow()"><i class="fas fa-plus"></i> Add Line</button>
                        </td>
                    </tr>
                    <tr class="font-bold text-slate-900">
                        <td colspan="2" class="text-right">Total:</td>
                        <td class="text-right">$<span id="totalDebit">0.00</span></td>
                        <td class="text-right">$<span id="totalCredit">0.00</span></td>
                        <td></td>
                    </tr>
                    <tr id="differenceRow" style="display: none;">
                        <td colspan="2" class="text-right text-danger">Difference:</td>
                        <td colspan="2" class="text-center text-danger"><span id="difference">0.00</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <input type="hidden" name="total_debit" id="input_total_debit">
        <input type="hidden" name="total_credit" id="input_total_credit">
        <input type="hidden" name="items" id="input_items">
        <input type="hidden" name="status" value="draft">
        
        <div class="border-t border-slate-100 my-4"></div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold transition-all shadow-sm inline-flex items-center gap-1.5"><i class="fas fa-save"></i> Save Journal</button>
        <a href="<?= BASE_URL ?>/accounting/journalEntries" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-sm font-semibold text-center transition-all inline-flex">Cancel</a>
    </form>
</div>

<template id="itemTemplate">
    <tr>
        <td>
            <select name="account_id[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all account-select" onchange="calculateTotals()" required>
                <option value="">Select Account</option>
                <?php foreach ($accounts as $acc): ?>
                <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['account_code']) ?> - <?= htmlspecialchars($acc['account_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="text" name="item_desc[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-description" placeholder="Description"></td>
        <td><input type="number" name="debit[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-debit" value="0" min="0" step="0.01" onchange="calculateTotals()"></td>
        <td><input type="number" name="credit[]" class="w-full bg-slate-50 border border-slate-200 rounded-md py-1 px-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all item-credit" value="0" min="0" step="0.01" onchange="calculateTotals()"></td>
        <td><button type="button" class="h-7 w-7 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors" onclick="removeItemRow(this)"><i class="fas fa-trash"></i></button></td>
    </tr>
</template>

<script>
function addItemRow() {
    const template = document.getElementById('itemTemplate');
    const clone = template.content.cloneNode(true);
    document.getElementById('itemsBody').appendChild(clone);
}

function removeItemRow(btn) {
    btn.closest('tr').remove();
    calculateTotals();
}

function calculateTotals() {
    let totalDebit = 0, totalCredit = 0;
    const items = [];
    
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const account = row.querySelector('.account-select');
        const debit = parseFloat(row.querySelector('.item-debit').value) || 0;
        const credit = parseFloat(row.querySelector('.item-credit').value) || 0;
        
        if (debit > 0 && credit > 0) {
            alert('A line cannot have both debit and credit values.');
            row.querySelector('.item-credit').value = 0;
            return;
        }
        
        totalDebit += debit;
        totalCredit += credit;
        
        if (account.value) {
            items.push({
                account_id: account.value,
                description: row.querySelector('.item-description').value,
                debit: debit,
                credit: credit
            });
        }
    });
    
    $('#totalDebit').text(totalDebit.toFixed(2));
    $('#totalCredit').text(totalCredit.toFixed(2));
    
    const diff = Math.abs(totalDebit - totalCredit);
    if (diff > 0.01) {
        $('#differenceRow').show();
        $('#difference').text(diff.toFixed(2));
    } else {
        $('#differenceRow').hide();
    }
    
    $('#input_total_debit').val(totalDebit);
    $('#input_total_credit').val(totalCredit);
    $('#input_items').val(JSON.stringify(items));
}

$('#journalForm').submit(function(e) {
    e.preventDefault();
    
    const totalDebit = parseFloat($('#input_total_debit').val()) || 0;
    const totalCredit = parseFloat($('#input_total_credit').val()) || 0;
    
    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        alert('Debits and credits must be equal!');
        return false;
    }
    
    if (totalDebit === 0) {
        alert('Please enter at least one journal item.');
        return false;
    }
    
    $.ajax({
        url: '<?= BASE_URL ?>/accounting/createJournal',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) window.location.href = '<?= BASE_URL ?>/accounting/viewJournal/' + response.id;
            else alert('Error: ' + response.message);
        }
    });
});

addItemRow();
addItemRow();
</script>