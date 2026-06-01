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
<body>
    <!-- LANDING PAGE -->
    <div id="landingPage"class="h-screen bg-sky-600 flex flex-col items-center pt-20 border-b-1 border-white" >
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
        
        <!-- BUTTON -->
        <div class="flex mt-8 gap-10">
        <a href="produk.php" class="flex items-center bg-orange-400 text-white text-xl font-semibold px-8 py-1 rounded-[1vw] shadow-lg transition-all hover:bg-orange-700 hover:scale-105">
            Mulai Jelajahi
        </a>   
        <a href="login.php" class="flex items-center bg-sky-500 text-white text-xl font-semibold px-8 py-3 rounded-[1vw] shadow-lg transition-all hover:bg-sky-700 hover:scale-105">
            Login dengan SSO 
        </a>
        </div>

        <!-- ACHIEVE? -->
         <div class="grid grid-cols-3 mt-12 gap-x-25 gap-y-1">
            <p class="text-orange-400 text-4xl font-bold">500+</p>
            <p class="text-orange-400 text-4xl font-bold">1200+</p>
            <p class="text-orange-400 text-4xl font-bold">15+</p>
            <p class="text-white text-xl ">Iklan Aktif</p>
            <p class="text-white text-xl ">Mahasiswa</p>
            <p class="text-white text-xl ">Fakultas</p>
         </div>
    </div>

</body>
</html>