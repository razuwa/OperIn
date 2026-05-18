<?php
session_start();
require 'dataProduk.php';

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = $products;
}

$products = $_SESSION['products'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OperIn - Platform Preloved Mahasiswa</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        html {scroll-behavior: smooth;}
    </style>
</head>
<body>
    <!-- PRODUK -->

    <div id="produk" class="flex flex-col min-h-screen bg-amber-50">
        <!-- HEADER -->
        <div class="bg-sky-600 text-white font-semibold p-3 border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4">
            Welcome To Operin: Platform Preloved Mahasiswa
            </div>
        </div>
        <!-- NAVBAR -->
        <?php include 'components/navbar.php'; ?>
        <!-- END OF NAVBAR -->

        <!-- HEADER SECTION PRODUK PROMOSI -->
        <div class="max-w-7xl mx-auto px-4 w-full py-5">
            <!-- PRODUK REKOMENDASI -->
            <div class="flex items-center justify-between py-2 px-5 border-b-2 bg-gray-300 border-sky-300">
                <h2 class="text-black font-semibold text-lg">Produk Rekomendasi</h2>
                <a href="" class="text-sky-500 text-sm hover:text-orange-500 transition-colors">Browse All Product →</a>
            </div>
                        
            <!-- ETALASE -->
            <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 pt-4">
                <?php
                foreach ($products as $index => $p) : ?>
                <a href="detailProduk.php?id=<?= $index ?>" class="h-full">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden cursor-pointer hover:-translate-y-1 hover:shadow-xl transition-all h-full flex flex-col">
                        <img src="<?= $p['image'] ?>" class="w-full aspect-square object-cover bg-gray-100 shrink-0">
                        <div class="p-2.5 flex-1 flex flex-col">
                            <div class="min-h-[3rem]">
                                <p class="text-lg text-gray-700 line-clamp-2 mb-1 leading-tight"><?= $p['name'] ?></p>
                            </div>
                            <div class="mt-auto">
                                <p class="text-base font-semibold text-orange-500 mb-1">
                                    Rp<?= number_format($p['price'], 0, ',', '.') ?>
                                </p>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-400 text-[15px]"><?= $p['fakultas'] ?></span>
                                    <span class="bg-orange-50 text-orange-500 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                                        <?=$p['kondisi']?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>    
        
        <!-- HEADER SECTION PRODUK PROMOSI -->
        <div class="max-w-7xl mx-auto px-4 w-full py-10">
            <!-- PRODUK REKOMENDASI -->
            <div class="flex items-center justify-between py-2 px-5 border-b-2 bg-gray-300 border-sky-300">
                <h2 class="text-black font-semibold text-lg">Baru Ditambahkan</h2>
                <a href="" class="text-sky-500 text-sm hover:text-orange-500 transition-colors">Browse All Product →</a>
            </div>
                        
            <!-- ETALASE -->
            <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 pt-4">
                <?php
                foreach ($products as $index => $p) : ?>
                <a href="detailProduk.php?id=<?= $index ?>" class="h-full">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden cursor-pointer hover:-translate-y-1 hover:shadow-xl transition-all h-full flex flex-col">
                        <img src="<?= $p['image'] ?>" class="w-full aspect-square object-cover bg-gray-100 shrink-0">
                        <div class="p-2.5 flex-1 flex flex-col">
                            <div class="min-h-[3rem]">
                                <p class="text-lg text-gray-700 line-clamp-2 mb-1 leading-tight"><?= $p['name'] ?></p>
                            </div>
                            <div class="mt-auto">
                                <p class="text-base font-semibold text-orange-500 mb-1">
                                    Rp<?= number_format($p['price'], 0, ',', '.') ?>
                                </p>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-400 text-[15px]"><?= $p['fakultas'] ?></span>
                                    <span class="bg-orange-50 text-orange-500 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                                        <?=$p['kondisi']?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>    
        <!-- FOOTER -->
        <?php include 'components/footer.php'; ?>
        <!-- END OF FOOTER -->
    </div>
</body>
</html>