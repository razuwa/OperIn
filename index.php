
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
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- LANDING PAGE -->
    <div id="landingPage"class="h-screen bg-sky-600 flex flex-col items-center pt-20 " >
        <!-- LOGO -->
        <a href="reset.php">
            <div class="flex items-center bg-sky-500 text-white text-2xl font-bold px-8 py-2 rounded-full shadow-lg transition-all hover:bg-sky-700 hover:scale-105">
                <img src="assets/logo-operin.png" alt="Logo Operin" class="max-h-8 pr-3">
                <h2>OperIn</h2>
            </div>
        </a>
        
        <!-- TAGLINE -->
        <h1 class="text-white font-bold text-6xl mt-10">
        Jual Beli Barang Kampus
        </h1>
        <h1 class="text-orange-400 font-bold text-6xl mt-2">
        Lebih Mudah & Aman
        </h1>
        <h3 class="text-lg text-white mt-6">Platform Marketplace Khusus Mahasiswa Universitas Sebelas  Maret</h3>
        <h3 class="text-lg text-white mt-1">Jual, beli, atau cari barang yang kamu butuhkan dengan mudah dan terpercaya.</h3>
        
        <!-- YANG 2 ITU -->
        <div class="flex mt-8 gap-10">
        <a href="#produk" class="flex items-center bg-orange-400 text-white text-xl font-semibold px-8 py-1 rounded-[1vw] shadow-lg transition-all hover:bg-orange-700 hover:scale-105">
            Mulai Jelajahi
        </a>   
        <a href="login.html" class="flex items-center bg-sky-500 text-white text-xl font-semibold px-8 py-3 rounded-[1vw] shadow-lg transition-all hover:bg-sky-700 hover:scale-105">
            Login dengan SSO 
        </a>
        </div>

        <!-- YANG 3 -->
         <div class="grid grid-cols-3 mt-12 gap-x-25 gap-y-1">
            <p class="text-orange-400 text-4xl font-bold">500+</p>
            <p class="text-orange-400 text-4xl font-bold">1200+</p>
            <p class="text-orange-400 text-4xl font-bold">15+</p>
            <p class="text-white text-xl ">Iklan Aktif</p>
            <p class="text-white text-xl ">Mahasiswa</p>
            <p class="text-white text-xl ">Fakultas</p>
         </div>
    </div>

    <!-- PRODUK -->
    <div id="produk" class="flex flex-col min-h-screen bg-amber-50">
        <div class="flex items-center text-white font-semibold px-30 p-3 bg-sky-600 min-w-full border-b-1 border-t-1 border-white">
            Welcome To Operin: Platform Preloved Mahasiswa
        </div>
        <!-- LOGO -->
        <div class="flex items-center px-30 pt-4 pb-4 bg-sky-600 min-w-full text-white font-bold text-4xl shadow-lg ">
            <img src="assets/logo-operin.png" alt="Logo Operin" class="max-h-8 pr-2"> Operin
            <!-- SEARCH BAR -->
            <form class="flex">
                <input type="text" name="search" id="search" placeholder="Cari Barang..." 
                class="w-lg pl-5 p-1 ml-40 text-black font-normal text-lg bg-white border-1 border-orange-400 focus:outline-orange-400 shadow-lg rounded-lg">
                <button type="submit" class="ml-2 px-4 py-1 bg-orange-400 text-white text-lg font-medium rounded-lg hover:bg-orange-700 transition-all">
                    <img src="assets/search.svg" alt="" class="max-h-6">
                </button>
            </form>
            <!-- 3 PRINTILAN -->
            <div class="flex ml-30 gap-5">
                <a href="">
                    <svg class="w-7 h-7 text-gray-800 dark:text-white hover:text-orange-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                    </svg>
                </a>
                <a href="tambahBarang.html">
                    <svg class="w-7 h-7 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </a>
                <a href="login.html">
                    <svg class="w-7 h-7 text-gray-800 dark:text-white hover:text-orange-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- KATEGORI-KATEGORI -->
        <div class="flex justify-center gap-8 border-b-2 border-amber-300 shadow-lg p-4">
            <a href="">lorem</a>
            <a href="">lorem</a>
            <a href="">lorem</a>
            <a href="">lorem</a>
        </div>


        <!-- HEADER SECTION -->
        <div class="flex flex-col mx-30 mt-12">
            <div class="flex items-center justify-between pb-3 border-b-2 border-sky-300">
                <h2 class="text-black font-semibold text-lg">Baru Ditambahkan</h2>
                <a href="" class="text-sky-500 text-sm hover:text-orange-500 transition-colors">Browse All Product →</a>
            </div>

            <!-- ETALASE -->
            <div id="productGrid" class="grid grid-cols-5 gap-4 mt-4 pb-12">
            <?php

                foreach ($products as $index => $p) : ?>
                <a href="detailProduk.php?id=<?= $index ?>">
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:-translate-y-1 hover:shadow-lg transition-all">
                    <img src="<?= $p['image'] ?>" class="w-full aspect-square object-cover bg-gray-100">
                    <div class="p-2.5">
                        <p class="text-sm text-gray-700 line-clamp-2 mb-1"><?= $p['name'] ?></p>
                        <p class="text-base font-semibold text-orange-500 mb-1">
                        Rp<?= number_format($p['price'], 0, ',', '.') ?>
                        </p>
                        <div class="flex justify-between text-xs">
                        <span class="bg-orange-50 text-orange-500 border border-orange-200 px-1.5 py-0.5 rounded text-[10px]">
                            <?= $p['kondisi'] ?>
                        </span>
                        <span class="text-gray-400"><?= $p['fakultas'] ?></span>
                        </div>
                    </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>


    </div>
</body>
</html>