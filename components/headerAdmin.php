<header class="bg-white/95 backdrop-blur shadow-sm py-4 px-6 md:px-8 flex justify-between items-center fixed right-0 top-0 left-0 md:left-64 z-20 border-b border-slate-200">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden p-1 text-slate-600 hover:text-sky-600 focus:outline-none cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <h1 class="text-base md:text-xl font-bold text-slate-800"><?= $page_title ?? 'Admin Panel' ?></h1>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-[10px] md:text-xs bg-sky-50 text-sky-600 font-bold px-3 py-1.5 rounded-full border border-sky-200 uppercase tracking-wider">
            Sesi: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
        </span>
    </div>
</header>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>