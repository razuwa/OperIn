<?php
session_start();
require 'require/koneksi.php';

// batas produk per halaman 
$limit = 20;

//default halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }

$offset = ($page - 1) * $limit;

// tentukan jumlah halaman
$total_query = "SELECT COUNT(*) AS total FROM products";
$total_result = mysqli_query($koneksi, $total_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

//ambil 20 data produk pertama 
$query = "SELECT p.*, f.nama_fakultas AS fakultas 
          FROM products p 
          JOIN faculties f ON p.faculty_id = f.id 
          ORDER BY p.id DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[$row['id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-amber-50 min-h-screen flex flex-col">

    <?php include 'components/navbar.php'; ?>

    <div class="max-w-6xl mx-auto px-4 w-full py-8 flex-1">
        <div class="flex items-center justify-between py-2 px-5 border-b-2 bg-gray-300 border-sky-300 mb-6">
            <h1 class="text-black font-semibold text-lg">Semua Produk Terdaftar</h1>
            <a href="produk.php" class="text-sky-500 text-sm hover:text-orange-500 transition-colors">← Kembali ke Home</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php if (empty($products)): ?>
                <p class="text-center text-gray-500 col-span-full py-10">Belum ada produk yang dijual.</p>
            <?php else: ?>
                <?php foreach ($products as $index => $p) : ?>
                <a href="detailProduk.php?id=<?= $index ?>" class="h-full">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:-translate-y-1 hover:shadow-xl transition-all h-full flex flex-col">
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full aspect-square object-cover bg-gray-100 shrink-0">
                        <div class="p-2.5 flex-1 flex flex-col">
                            <div class="min-h-[3rem]">
                                <p class="text-base font-medium text-gray-700 line-clamp-2 mb-1 leading-tight"><?= htmlspecialchars($p['name']) ?></p>
                            </div>
                            <div class="mt-auto">
                                <p class="text-base font-semibold text-orange-500 mb-1">
                                    Rp<?= number_format($p['price'], 0, ',', '.') ?>
                                </p>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-400 text-[15px]"><?= htmlspecialchars($p['fakultas']) ?></span>
                                    <span class="bg-orange-50 text-orange-500 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-semibold">
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

        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-10">
            <?php if ($page > 1): ?>
                <a href="fullProduk.php?page=<?= $page - 1 ?>" class="px-3 py-1.5 bg-sky-500 text-white rounded-lg text-sm font-semibold hover:bg-sky-600">Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="fullProduk.php?page=<?= $i ?>" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold <?= $i === $page ? 'bg-sky-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="fullProduk.php?page=<?= $page + 1 ?>" class="px-3 py-1.5 bg-sky-500 text-white rounded-lg text-sm font-semibold hover:bg-sky-600">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>