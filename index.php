<?php
session_start();
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
<body class="bg-sky-600 min-h-screen flex flex-col justify-center">

    <div id="landingPage" class="min-h-screen w-full flex flex-col items-center justify-center px-4 py-12 md:py-20 text-center select-none" >
        
        <a href="reset.php" class="block shrink-0">
            <div class="flex items-center bg-sky-500 text-white text-xl md:text-2xl font-bold px-6 md:px-8 py-2 rounded-full shadow-lg transition-all hover:bg-sky-700 hover:scale-105">
                <img src="assets/logo-operin.png" alt="Logo Operin" class="max-h-6 md:max-h-8 pr-3 object-contain">
                <h2>OperIn</h2>
            </div>
        </a>
        
        <div class="mt-8 md:mt-12 space-y-1 md:space-y-2 max-w-3xl">
            <h1 class="text-white font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight">
                Jual Beli Barang Kampus
            </h1>
            <h1 class="text-orange-400 font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight">
                Lebih Mudah & Aman
            </h1>
        </div>

        <div class="text-white mt-6 space-y-1 max-w-2xl px-2">
            <h3 class="text-sm md:text-lg font-medium opacity-95">Platform Marketplace Khusus Mahasiswa Universitas Sebelas Maret</h3>
            <h3 class="text-xs md:text-base font-normal opacity-80 hidden sm:block">Jual, beli, atau cari barang yang kamu butuhkan dengan mudah dan terpercaya.</h3>
        </div>
        
        <div class="flex flex-row mt-8 md:mt-10 gap-4 md:gap-6 w-full sm:w-auto justify-center px-4">
            <a href="produk.php" class="flex items-center justify-center bg-orange-400 text-white text-base md:text-xl font-bold px-6 md:px-8 py-3 rounded-xl shadow-lg transition-all hover:bg-orange-700 hover:scale-105 text-center flex-1 sm:flex-initial">
                Mulai Jelajahi
            </a>   
            <a href="login.php" class="flex items-center justify-center bg-sky-50 text-sky-600 sm:bg-sky-500 sm:text-white text-base md:text-xl font-bold px-6 md:px-8 py-3 rounded-xl shadow-lg transition-all hover:bg-sky-100 sm:hover:bg-sky-700 sm:hover:text-white hover:scale-105 text-center flex-1 sm:flex-initial">
                Login
            </a>
        </div>

        <div class="grid grid-cols-3 mt-12 md:mt-16 gap-x-6 sm:gap-x-12 md:gap-x-20 gap-y-1 max-w-2xl mx-auto border-t border-sky-500/40 pt-6 w-full">
            <div>
                <p class="text-orange-400 text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight">500+</p>
                <p class="text-sky-100 text-xs sm:text-sm md:text-base font-medium mt-0.5">Iklan Aktif</p>
            </div>
            <div>
                <p class="text-orange-400 text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight">1200+</p>
                <p class="text-sky-100 text-xs sm:text-sm md:text-base font-medium mt-0.5">Mahasiswa</p>
            </div>
            <div>
                <p class="text-orange-400 text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight">15+</p>
                <p class="text-sky-100 text-xs sm:text-sm md:text-base font-medium mt-0.5">Fakultas</p>
            </div>
        </div>

    </div>

</body>
</html>