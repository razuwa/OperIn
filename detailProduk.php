<?php
session_start();
require 'require/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Menentukan status login user
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

$query = "SELECT p.*, f.nama_fakultas AS fakultas, u.nama AS nama_penjual, u.whatsapp, c.nama_kategori AS kategori
          FROM products p
          JOIN faculties f ON p.faculty_id = f.id
          JOIN users u ON p.user_id = u.id
          JOIN categories c ON p.category_id = c.id
          WHERE p.id = $id";

$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: produk.php?error=Produk tidak ditemukan");
    exit();
}

$p = mysqli_fetch_assoc($result);

// KONDISIONAL URL: Tentukan arah link berdasarkan status login
if ($is_logged_in) {
    $button_url = "https://wa.me/" . $p['whatsapp'] . "?text=" . urlencode("Halo " . $p['nama_penjual'] . ", saya tertarik dengan produk " . $p['name'] . " di OperIn.");
} else {
    // Melempar ke login.php jika belum login
    $button_url = "login.php?error=" . urlencode("Silakan login terlebih dahulu untuk menghubungi penjual.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($p['name']) ?> - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-amber-50">

    <?php include 'components/navbar.php'; ?>
    <div class="max-w-4xl mx-auto mt-10 px-6">
    
        <p class="text-sm text-gray-400 mb-6">
            <a href="produk.php" class="hover:text-sky-400">Beranda</a> > <?= htmlspecialchars($p['name']) ?>
        </p>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 flex gap-10">
            <a href="produk.php" class="flex border bg-sky-600 border-sky-900 max-h-8 items-center pb-2 px-2 hover:bg-sky-800 rounded-lg">
                <div class="text-2xl text-white">
                    ← 
                </div>
            </a>

            <div class="w-72 h-72 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col justify-between flex-1">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mt-3 mb-2"><?= htmlspecialchars($p['name']) ?></h1>
                    
                    <p class="text-3xl font-bold text-orange-500 mb-4">
                        Rp<?= number_format($p['price'], 0, ',', '.') ?>
                    </p>
                    
                    <div class="flex items-center mb-2 gap-2 text-sm text-gray-500 flex-wrap">
                        <span class="bg-sky-50 text-sky-600 border border-sky-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                            <?= htmlspecialchars($p['kategori']) ?>
                        </span>
                        
                        <span class="bg-orange-50 text-orange-500 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                            <?= htmlspecialchars($p['kondisi']) ?>
                        </span>
                        
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?= htmlspecialchars($p['fakultas']) ?>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-400 mb-4">Penjual: <span class="font-semibold text-gray-600"><?= htmlspecialchars($p['nama_penjual']) ?></span></p>
                    
                    <p class="text-gray-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                </div>

                <div class="flex gap-3 mt-8">
                    <a href="<?= $button_url ?>" 
                       <?= $is_logged_in ? 'target="_blank"' : '' ?>
                       class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all font-semibold flex items-center gap-2">
                        Hubungi Penjual via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>