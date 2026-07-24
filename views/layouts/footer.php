    </main>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity"></div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        const sidebar = document.querySelector('aside');
        const toggleBtn = document.getElementById('mobileSidebarToggle');
        const desktopToggleBtn = document.getElementById('desktopSidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');
        const body = document.body;

        // Load saved state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
            if (desktopToggleBtn) {
                desktopToggleBtn.innerHTML = '<span class="material-symbols-outlined">menu</span>';
            }
        }

        if (desktopToggleBtn) {
            desktopToggleBtn.addEventListener('click', function() {
                body.classList.toggle('sidebar-collapsed');
                const isCollapsed = body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                if (isCollapsed) {
                    desktopToggleBtn.innerHTML = '<span class="material-symbols-outlined">menu</span>';
                } else {
                    desktopToggleBtn.innerHTML = '<span class="material-symbols-outlined">menu_open</span>';
                }
            });
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        $(document).ready(function() {
            document.body.style.visibility = 'visible';
            document.body.style.opacity = '1';

            if ($('.select2').length) {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }

            if ($.fn.DataTable && $('.dataTable').length) {
                $('.dataTable').DataTable({
                    pageLength: 25,
                    responsive: true
                });
            }

            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        function formatCurrency(amount) {
            const currencyStr = typeof SYSTEM_CURRENCY !== 'undefined' ? SYSTEM_CURRENCY + ' ' : '$';
            return currencyStr + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,' );
        }

        function confirmDelete(message = 'Are you sure?') {
            return confirm(message);
        }

        function showToast(message, type = 'success', duration = 4000) {
            let container = document.getElementById('toastContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toastContainer';
                container.className = 'fixed top-5 right-5 z-[99999] flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            const isError = type === 'error' || type === 'danger';
            const isWarning = type === 'warning';

            const borderClass = isSuccess ? 'border-emerald-500/40 shadow-emerald-950/20' : (isError ? 'border-rose-500/40 shadow-rose-950/20' : (isWarning ? 'border-amber-500/40 shadow-amber-950/20' : 'border-sky-500/40 shadow-sky-950/20'));
            const iconBg = isSuccess ? 'bg-emerald-500/20 text-emerald-400' : (isError ? 'bg-rose-500/20 text-rose-400' : (isWarning ? 'bg-amber-500/20 text-amber-400' : 'bg-sky-500/20 text-sky-400'));
            const iconName = isSuccess ? 'check_circle' : (isError ? 'error' : (isWarning ? 'warning' : 'info'));
            const barBg = isSuccess ? 'bg-emerald-500' : (isError ? 'bg-rose-500' : (isWarning ? 'bg-amber-500' : 'bg-sky-500'));

            toast.className = `pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 rounded-2xl border ${borderClass} bg-slate-900/95 text-white shadow-2xl backdrop-blur-xl transition-all duration-300 transform translate-x-10 opacity-0 scale-95`;

            toast.innerHTML = `
                <div class="w-9 h-9 rounded-xl ${iconBg} flex items-center justify-center font-bold flex-shrink-0 shadow-inner mt-0.5">
                    <span class="material-symbols-outlined text-xl">${iconName}</span>
                </div>
                <div class="flex-1 pr-2">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">${type}</h4>
                    <p class="text-xs font-medium text-slate-100 leading-relaxed">${message}</p>
                </div>
                <button type="button" class="text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-800" onclick="dismissToast(this.parentElement)">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-800">
                    <div class="h-full ${barBg} transition-all linear" style="width: 100%; transition-duration: ${duration}ms;"></div>
                </div>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-10', 'opacity-0', 'scale-95');
                toast.classList.add('translate-x-0', 'opacity-100', 'scale-100');
                const bar = toast.querySelector('.absolute div');
                if (bar) bar.style.width = '0%';
            });

            const timer = setTimeout(() => dismissToast(toast), duration);
            toast.dataset.timerId = timer;
        }

        function dismissToast(toast) {
            if (!toast) return;
            if (toast.dataset.timerId) clearTimeout(toast.dataset.timerId);
            toast.classList.remove('translate-x-0', 'opacity-100', 'scale-100');
            toast.classList.add('translate-x-12', 'opacity-0', 'scale-90');
            setTimeout(() => toast.remove(), 300);
        }

        window.showToast = showToast;
    </script>

    <div id="toastContainer" class="fixed top-5 right-5 z-[99999] flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast(<?= json_encode($_SESSION['flash_message']) ?>, <?= json_encode($_SESSION['flash_type'] ?? 'info') ?>);
        });
    </script>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
</body>
</html>
