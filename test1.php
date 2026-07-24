<?php
/**
 * Kravio Support Intelligence Dashboard
 * PHP & HTML version
 */

// Mock data for the dashboard
$current_tickets = 3484;
$resolution_avg = 486;
$sla_compliance = 92;

$tickets = [
    [
        'id' => '#KR-4821',
        'subject' => 'System integration failure on main checkout...',
        'priority' => 'High',
        'assigned' => ['name' => 'Sarah J.', 'avatar' => 'https://picsum.photos/seed/sarah/100'],
        'status' => 'IN PROGRESS',
        'created' => 'Oct 12, 09:42 AM',
        'sla' => '24m 12s left'
    ],
    [
        'id' => '#KR-4818',
        'subject' => 'Account access issues after recent update',
        'priority' => 'Medium',
        'assigned' => ['name' => 'Marcus T.', 'avatar' => 'https://picsum.photos/seed/marcus/100'],
        'status' => 'WAITING',
        'created' => 'Oct 12, 08:15 AM',
        'sla' => '1h 45m left'
    ],
    [
        'id' => '#KR-4815',
        'subject' => 'API key documentation clarification',
        'priority' => 'Low',
        'assigned' => ['name' => 'Elena R.', 'avatar' => 'https://picsum.photos/seed/elena/100'],
        'status' => 'RESOLVED',
        'created' => 'Oct 11, 04:30 PM',
        'sla' => 'Completed'
    ]
];

$updates = [
    ['type' => 'update', 'title' => 'Ticket Updated', 'desc' => 'Ticket #4928 was resolved by Sarah Jenkins.', 'time' => '2 mins ago'],
    ['type' => 'add', 'title' => 'New Client Added', 'desc' => 'TechNova Solutions joined the platform.', 'time' => '1 hour ago'],
    ['type' => 'warning', 'title' => 'SLA Alert', 'desc' => 'Critical breach risk for Ticket #4821.', 'time' => '5 hours ago']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kravio - Support Intelligence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.344.0/font/lucide.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-[#f8f9fa] text-slate-800 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-screen w-64 bg-[#f1f4f6] flex flex-col py-6 border-r border-slate-200/50">
        <div class="px-8 mb-10">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 font-headline">Kravio</h1>
            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold mt-1">Support Intelligence</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <div class="bg-white shadow-sm text-slate-800 font-semibold flex items-center gap-3 px-6 py-3 cursor-pointer rounded-lg relative border-l-4 border-slate-600 mb-4">
                <i class="lucide-layout-dashboard w-5 h-5"></i>
                <span class="text-sm">Overview</span>
            </div>
            
            <div class="px-6 py-3 flex flex-col gap-2">
                <div class="flex items-center gap-3 text-slate-500 hover:text-slate-800 transition-all cursor-pointer">
                    <i class="lucide-ticket w-5 h-5"></i>
                    <span class="text-sm">Tickets</span>
                </div>
            </div>

            <div class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:text-slate-800 transition-all cursor-pointer">
                <i class="lucide-users w-5 h-5"></i>
                <span class="text-sm">Clients</span>
            </div>
            
            <div class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:text-slate-800 transition-all cursor-pointer">
                <i class="lucide-headset w-5 h-5"></i>
                <span class="text-sm">Agents</span>
            </div>
        </nav>

        <div class="px-4 mt-auto pt-4 border-t border-slate-200/50">
            <div class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-red-500 cursor-pointer">
                <i class="lucide-log-out w-5 h-5"></i>
                <span class="text-sm">Log Out</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 bg-[#f8f9fa]">
        <header class="sticky top-0 z-40 flex items-center justify-between px-8 h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/50">
            <div class="relative flex items-center">
                <i class="lucide-search absolute left-3 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Search interactions..." class="bg-slate-100 border-none rounded-full pl-10 pr-4 py-1.5 text-xs outline-none w-64">
            </div>
            <div class="flex items-center gap-6">
                <i class="lucide-bell w-5 h-5 text-slate-400 cursor-pointer"></i>
                <i class="lucide-settings w-5 h-5 text-slate-400 cursor-pointer"></i>
                <div class="h-8 w-8 rounded-full bg-slate-200 overflow-hidden border border-slate-200">
                    <img src="https://picsum.photos/seed/user/200" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <div class="p-10 max-w-[1600px] mx-auto w-full">
            <section class="mb-10">
                <h2 class="text-3xl font-bold tracking-tight text-slate-800 mb-2 font-headline">Hello, Admin User 👋</h2>
                <p class="text-slate-500 text-sm font-medium">Here are the latest insights from your customer interactions.</p>
            </section>

            <div class="grid grid-cols-12 gap-8">
                <div class="col-span-9 space-y-8">
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-50">
                            <span class="text-slate-500 text-[11px] font-bold uppercase block mb-4">Tickets</span>
                            <div class="text-3xl font-bold font-headline"><?php echo number_format($current_tickets); ?></div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-50">
                            <span class="text-slate-500 text-[11px] font-bold uppercase block mb-4">Daily Resolution</span>
                            <div class="text-3xl font-bold font-headline"><?php echo $resolution_avg; ?></div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-50">
                            <span class="text-slate-500 text-[11px] font-bold uppercase block mb-4">SLA Compliance</span>
                            <div class="text-3xl font-bold font-headline"><?php echo $sla_compliance; ?>%</div>
                        </div>
                    </div>

                    <!-- Tickets Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold font-headline">SLA Monitoring</h3>
                            <button class="bg-slate-800 text-white px-4 py-2 rounded-lg text-xs font-bold">New Ticket</button>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-500">
                                <tr>
                                    <th class="px-8 py-4">ID</th>
                                    <th class="px-4 py-4">Subject</th>
                                    <th class="px-4 py-4">Priority</th>
                                    <th class="px-4 py-4">Status</th>
                                    <th class="px-8 py-4 text-right">SLA Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                    <td class="px-8 py-5 text-xs font-bold"><?php echo $ticket['id']; ?></td>
                                    <td class="px-4 py-5 text-sm"><?php echo $ticket['subject']; ?></td>
                                    <td class="px-4 py-5">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 uppercase">
                                            <?php echo $ticket['priority']; ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-5 text-xs uppercase font-bold"><?php echo $ticket['status']; ?></td>
                                    <td class="px-8 py-5 text-right text-xs font-bold"><?php echo $ticket['sla']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Activity Sidebar -->
                <div class="col-span-3">
                    <div class="bg-white p-8 rounded-xl border border-slate-50 h-full shadow-sm">
                        <h3 class="text-sm font-bold uppercase mb-8 font-headline">Latest Updates</h3>
                        <div class="space-y-8">
                            <?php foreach ($updates as $update): ?>
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full mt-1 shrink-0 <?php echo $update['type'] == 'warning' ? 'bg-red-500' : 'bg-blue-500'; ?>"></div>
                                <div>
                                    <p class="text-xs font-bold"><?php echo $update['title']; ?></p>
                                    <p class="text-[11px] text-slate-500"><?php echo $update['desc']; ?></p>
                                    <span class="text-[9px] text-slate-400"><?php echo $update['time']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
