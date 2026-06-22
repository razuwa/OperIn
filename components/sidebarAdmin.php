<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-sky-600 text-white flex flex-col justify-between shadow-xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div>
        <div class="p-5 border-b border-white/10 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="assets/logo-operin.png" alt="Logo" class="max-h-8 brightness-0 invert">
                <span class="font-bold text-2xl tracking-tight">AdminPanel</span>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden text-white focus:outline-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200/60 px-4 mb-2">Menu Utama</p>
            
            <a href="dashboardAdmin.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm <?= ($current_page == 'dashboardAdmin.php') ? 'bg-sky-700 text-white font-bold' : 'text-sky-100 hover:bg-sky-500/50 font-medium' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V16zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V16z"></path></svg>
                Semua Produk
            </a>
            
            <a href="produk.php" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sky-100 hover:bg-sky-500/50 rounded-xl transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Lihat Etalase
            </a>

            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200/60 px-4 pt-4 mb-2">Promosi</p>
            
            <a href="kelolaPromo.php?status=aktif" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm <?= ($current_page == 'kelolaPromo.php' && isset($_GET['status']) && $_GET['status'] === 'aktif') ? 'bg-sky-700 text-white font-bold' : 'text-sky-100 hover:bg-sky-500/50' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Produk Dipromosikan
            </a>
            
            <a href="kelolaPromo.php?status=pending" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm <?= ($current_page == 'kelolaPromo.php' && (!isset($_GET['status']) || $_GET['status'] === 'pending')) ? 'bg-sky-700 text-white font-bold' : 'text-sky-100 hover:bg-sky-500/50' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Menunggu Persetujuan
            </a>

            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200/60 px-4 pt-4 mb-2">Pengguna</p>
            
            <a href="kelolaUser.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm <?= ($current_page == 'kelolaUser.php') ? 'bg-sky-700 text-white font-bold' : 'text-sky-100 hover:bg-sky-500/50' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen User
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-white/10">
        <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')" class="flex items-center gap-3 px-4 py-2.5 bg-red-500 hover:bg-red-600 rounded-xl text-sm font-semibold transition-all shadow-md text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
        </a>
    </div>
</aside>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-20 hidden"></div>