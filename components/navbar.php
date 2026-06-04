<header class="sticky top-0 z-50 w-full flex flex-col">

    <nav class="bg-sky-600 py-3 md:py-4 shadow-lg w-full">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap md:flex-nowrap items-center justify-between gap-x-4 gap-y-3 md:gap-y-0">
            
            <div class="flex items-center text-white font-bold text-2xl md:text-3xl shrink-0 order-1">
                <a href="index.php" class="flex items-center">
                    <img src="assets/logo-operin.png" alt="Logo Operin" class="max-h-7 md:max-h-8 pr-2">
                    <span>OperIn</span>
                </a>
            </div>

            <div class="flex gap-4 md:gap-5 shrink-0 text-white items-center order-2 md:order-3">
                <a href="kelolaProduk.php" class="hover:text-orange-400">
                    <img src="assets/pencil.svg" alt="Edit" class="w-6 h-6">
                </a>
                <a href="tambahBarang.php" class="hover:text-orange-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
                
                <div class="relative inline-block">
                    <button id="profileBtn" class="hover:text-orange-400 focus:outline-none cursor-pointer flex items-center text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </button>

                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl py-1.5 border border-gray-200 z-50">
                        <?php if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) : ?>
                            <a href="login.php" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-orange-500 transition-colors">
                                Login
                            </a>
                        <?php else : ?>
                            <?php if ($_SESSION['role'] === 'admin') : ?>
                                <a href="dashboardAdmin.php" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                    Dashboard Admin
                                </a>
                            <?php endif; ?>
                            
                            <hr class="border-gray-100 my-1">
                            
                            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')" class="block px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                Logout
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                </div>

            <form action="produk.php" method="GET" class="w-full md:w-auto md:flex-1 max-w-2xl order-3 md:order-2">
                <div class="flex w-full">
                    <input type="text" name="search" id="search" placeholder="Cari Barang..." 
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    class="w-full pl-4 p-2 text-black font-normal text-sm md:text-base bg-white border border-orange-400 focus:outline-none rounded-l-lg">
                    <button type="submit" class="px-4 bg-orange-400 text-white rounded-r-lg hover:bg-orange-500 transition-all cursor-pointer shrink-0 flex items-center justify-center">
                        <img src="assets/search.svg" alt="Cari" class="max-h-5 md:max-h-6">
                    </button>
                </div>
            </form>

        </div>
    </nav>

    
</header>
<div class="flex justify-center gap-4 md:gap-8 border-b-2 bg-amber-50 border-gray-300 shadow-lg p-3 md:p-4 w-full text-xs md:text-sm">
    <a href="" class="text-slate-600 hover:text-orange-500 transition-colors">Kategori</a>
    <a href="" class="text-slate-600 hover:text-orange-500 transition-colors">Urutkan</a>
    <a href="" class="text-slate-600 hover:text-orange-500 transition-colors">Filter</a>
    <a href="" class="text-slate-600 hover:text-orange-500 transition-colors">Promo</a>
</div>

<script>
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target) && e.target !== profileBtn) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>