<?php
// Tarik master data untuk melengkapi isi dropdown form
$filter_cat_query = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY nama_kategori ASC");
$filter_fac_query = mysqli_query($koneksi, "SELECT * FROM faculties ORDER BY nama_fakultas ASC");

// tangkap status URL
$current_sort = $_GET['sort'] ?? '';
$current_cat = $_GET['category'] ?? '';
$current_fac = $_GET['faculty'] ?? '';
$current_kondisi = $_GET['kondisi'] ?? '';
$current_min = $_GET['min_price'] ?? '';
$current_max = $_GET['max_price'] ?? '';
$current_type = $_GET['type'] ?? '';
?>

<div class="flex justify-end border-b-2 bg-slate-100 border-slate-200 shadow-sm p-3 w-full z-40 relative">
    <div class="max-w-7xl w-full flex justify-end px-4 md:px-6">
        
        <div class="relative inline-block text-left">
            <button onclick="toggleFilter('main-filter')" class="bg-white border border-slate-300 text-slate-700 hover:border-sky-500 hover:text-sky-600 px-4 py-2.5 rounded-xl text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter
            </button>

            <div id="main-filter" class="hidden absolute right-0 mt-3 w-[320px] md:w-[400px] bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 px-5 py-3 flex justify-between items-center">
                    <button onclick="toggleFilter('main-filter')" class="text-slate-400 hover:text-red-500 cursor-pointer transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="fullProduk.php" method="GET" class="p-5 space-y-4 text-left">
                    <?php if(isset($_GET['search'])) echo '<input type="hidden" name="search" value="'.htmlspecialchars($_GET['search']).'">'; ?>
                    <?php if(!empty($current_type)) echo '<input type="hidden" name="type" value="'.htmlspecialchars($current_type).'">'; ?>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
                            <select name="category" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                                <option value="">Semua Kategori</option>
                                <?php while ($c = mysqli_fetch_assoc($filter_cat_query)) : ?>
                                    <option value="<?= $c['id'] ?>" <?= $current_cat == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Urutkan</label>
                            <select name="sort" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                                <option value="">Terbaru (Default)</option>
                                <option value="termurah" <?= $current_sort == 'termurah' ? 'selected' : '' ?>>Termurah</option>
                                <option value="termahal" <?= $current_sort == 'termahal' ? 'selected' : '' ?>>Termahal</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi (COD)</label>
                            <select name="faculty" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                                <option value="">Semua Lokasi</option>
                                <?php while ($f = mysqli_fetch_assoc($filter_fac_query)) : ?>
                                    <option value="<?= $f['id'] ?>" <?= $current_fac == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nama_fakultas']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kondisi</label>
                            <select name="kondisi" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                                <option value="">Semua Kondisi</option>
                                <option value="Baru" <?= $current_kondisi == 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Bekas" <?= $current_kondisi == 'Bekas' ? 'selected' : '' ?>>Bekas</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Harga Min</label>
                            <input type="number" name="min_price" value="<?= htmlspecialchars($current_min) ?>" placeholder="Rp 0" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Harga Max</label>
                            <input type="number" name="max_price" value="<?= htmlspecialchars($current_max) ?>" placeholder="Rp ~" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs bg-slate-50 focus:outline-none focus:border-sky-500 text-slate-700">
                        </div>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <a href="fullProduk.php<?= !empty($current_type) ? '?type=' . htmlspecialchars($current_type) : '' ?>" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-center transition-colors text-xs flex items-center justify-center">Reset</a>
                        <button type="submit" class="w-2/3 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 rounded-xl transition-colors cursor-pointer text-xs shadow-md shadow-sky-600/20">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFilter(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('main-filter');
        const button = e.target.closest('button[onclick="toggleFilter(\'main-filter\')"]');
        if (dropdown && !dropdown.contains(e.target) && !button && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
</script>