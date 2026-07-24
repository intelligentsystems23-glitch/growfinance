<?php $hide_title = true; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Employees</h1>
        <p class="text-sm text-slate-500 mt-1">Manage staff directory and HR information</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?? '' ?>/employees/leaves" class="flex items-center gap-2 bg-white text-slate-700 px-4 py-2.5 rounded-xl font-medium border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors">
            <span class="material-symbols-outlined text-sm">event_busy</span>
            Leave Requests
        </a>
        <a href="<?= BASE_URL ?? '' ?>/employees/create" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium shadow-sm shadow-primary/30 hover:bg-primary/90 hover:shadow-md hover:shadow-primary/40 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-sm">person_add</span>
            Add Employee
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Search by name, email, code..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Department</label>
            <select name="department_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Departments</option>
                <?php foreach ($departments ?? [] as $dept): ?>
                <option value="<?= $dept['id'] ?>" <?= ($_GET['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['department_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                <option value="">All Status</option>
                <option value="active" <?= ($_GET['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($_GET['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="on_leave" <?= ($_GET['status'] ?? '') == 'on_leave' ? 'selected' : '' ?>>On Leave</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition-colors h-[38px]" title="Filter">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
        </div>
    </form>
</div>

<!-- Employees Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Department & Role</th>
                    <th class="px-6 py-4">Contact Details</th>
                    <th class="px-6 py-4 text-center">Joining Date</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="6">
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">badge</span>
                            <p class="text-base font-medium text-slate-500">No employees found</p>
                            <p class="text-sm mt-1">Adjust your filters or add a new employee.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($employees as $emp): ?>
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-primary font-bold shadow-sm">
                                <?= strtoupper(substr($emp['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <a href="<?= BASE_URL ?? '' ?>/employees/view/<?= $emp['id'] ?>" class="font-bold text-slate-900 hover:text-primary transition-colors block">
                                    <?= htmlspecialchars($emp['full_name']) ?>
                                </a>
                                <span class="text-xs text-slate-500 font-mono"><?= htmlspecialchars($emp['employee_code']) ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-700"><?= htmlspecialchars($emp['department_name'] ?? '-') ?></div>
                        <div class="text-xs text-slate-500"><?= htmlspecialchars($emp['designation_name'] ?? '-') ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-slate-600 mb-1">
                            <span class="material-symbols-outlined text-[14px] text-slate-400">mail</span>
                            <?= htmlspecialchars($emp['email']) ?>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600 text-xs">
                            <span class="material-symbols-outlined text-[14px] text-slate-400">call</span>
                            <?= htmlspecialchars($emp['phone'] ?? $emp['mobile'] ?? 'No phone') ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-slate-600">
                        <?= $emp['joining_date'] ? date('d/m/Y', strtotime($emp['joining_date'])) : '-' ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $status_colors = [
                            'active' => 'bg-green-50 text-green-600 border-green-200',
                            'inactive' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'terminated' => 'bg-red-50 text-red-600 border-red-200',
                            'on_leave' => 'bg-amber-50 text-amber-600 border-amber-200'
                        ];
                        $color_class = $status_colors[$emp['status']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $color_class ?>">
                            <?= ucfirst(str_replace('_', ' ', $emp['status'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= BASE_URL ?? '' ?>/employees/view/<?= $emp['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-primary transition-colors shadow-sm" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                            <a href="<?= BASE_URL ?? '' ?>/employees/edit/<?= $emp['id'] ?>" class="h-8 w-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>