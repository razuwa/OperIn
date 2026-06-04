<?php
session_start();
require 'require/koneksi.php';

// query produk promosi
$query_promo = "SELECT p.*, f.nama_fakultas AS fakultas 
                FROM products p 
                JOIN promotion_requests pr ON p.id = pr.product_id
                JOIN faculties f ON p.faculty_id = f.id 
                WHERE pr.status = 'approved' AND NOW() BETWEEN pr.start_date AND pr.end_date
                ORDER BY p.id DESC 
                LIMIT 5";
$result_promo = mysqli_query($koneksi, $query_promo);

$promo_products = [];
if ($result_promo) {
    while ($row = mysqli_fetch_assoc($result_promo)) {
        $promo_products[] = $row; 
    }
}

// query semua produk
$query_baru = "SELECT p.*, f.nama_fakultas AS fakultas 
               FROM products p 
               JOIN faculties f ON p.faculty_id = f.id 
               ORDER BY p.id DESC 
               LIMIT 10";
$result_baru = mysqli_query($koneksi, $query_baru);

$new_products = [];
if ($result_baru) {
    while ($row = mysqli_fetch_assoc($result_baru)) {
        $new_products[] = $row; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OperIn - Platform Preloved Mahasiswa</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <?php include 'components/greetings.php'; ?>
    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-4 md:px-6 w-full py-8 space-y-12 flex-1">
        
        <section class="space-y-4">
            <div class="flex items-end justify-between border-b border-slate-200 pb-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="w-1 h-5 bg-amber-500 rounded-xs"></span>
                        Produk Rekomendasi 
                        <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-1.5 py-0.5 rounded-sm uppercase tracking-wider border border-amber-200">Promoted</span>
                    </h2>
                </div>
                <a href="fullPromosi.php" class="text-xs font-bold text-sky-600 hover:text-orange-500 transition-colors shrink-0">Lihat Semua →</a>
            </div>
                        
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                <?php if (empty($promo_products)) : ?>
                    <div class="col-span-full bg-white border border-dashed border-slate-300 text-center p-8 rounded-xl text-xs text-slate-400 font-medium">
                        Belum ada produk rekomendasi.
                    </div>
                <?php else : ?>
                    <?php foreach ($promo_products as $p) : ?>
                    <a href="detailProduk.php?id=<?= $p['id'] ?>" class="group block h-full">
                        <div class="bg-white border border-slate-200/70 rounded-xl shadow-xs overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 h-full flex flex-col relative">
                            
                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-md shadow-xs uppercase tracking-wide z-10">STAR</div>

                            <div class="w-full aspect-square bg-slate-50 overflow-hidden shrink-0">
                                <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                                <p class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($p['name']) ?></p>
                                <div class="space-y-1">
                                    <p class="text-base font-bold text-amber-600">Rp<?= number_format($p['price'], 0, ',', '.') ?></p>
                                    <div class="flex justify-between items-center text-[11px] pt-1.5 border-t border-slate-100">
                                        <span class="text-slate-400 font-medium truncate max-w-[65px]"><?= htmlspecialchars($p['fakultas']) ?></span>
                                        <span class="bg-orange-50 text-orange-600 border border-orange-100 px-1.5 py-0.5 rounded font-bold text-[10px]">
                                            <?= htmlspecialchars($p['kondisi']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-end justify-between border-b border-slate-200 pb-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="w-1 h-5 bg-sky-500 rounded-xs"></span>
                        Baru Ditambahkan
                    </h2>
                </div>
                <a href="fullProduk.php" class="text-xs font-bold text-sky-600 hover:text-orange-500 transition-colors shrink-0">Lihat Semua →</a>
            </div>
                        
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                <?php if (empty($new_products)) : ?>
                    <div class="col-span-full text-center py-12 text-slate-400 text-xs font-medium">Etalase masih kosong.</div>
                <?php else : ?>
                    <?php foreach ($new_products as $p) : ?>
                    <a href="detailProduk.php?id=<?= $p['id'] ?>" class="group block h-full">
                        <div class="bg-white border border-slate-200/70 rounded-xl shadow-xs overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 h-full flex flex-col">
                            <div class="w-full aspect-square bg-slate-50 overflow-hidden shrink-0">
                                <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                                <p class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($p['name']) ?></p>
                                <div class="space-y-1">
                                    <p class="text-base font-bold text-slate-900 tracking-tight">Rp<?= number_format($p['price'], 0, ',', '.') ?></p>
                                    <div class="flex justify-between items-center text-[11px] pt-1.5 border-t border-slate-100">
                                        <span class="text-slate-400 font-medium truncate max-w-[65px]"><?= htmlspecialchars($p['fakultas']) ?></span>
                                        <span class="bg-slate-100 text-slate-600 border border-slate-200 px-1.5 py-0.5 rounded font-bold text-[10px]">
                                            <?= htmlspecialchars($p['kondisi']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        
    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>