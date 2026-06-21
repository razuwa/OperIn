<?php
session_start();
require 'require/koneksi.php';

// tangkap parameter dari navbar dan filter: Kategori, Sort, Fakultas, Harga
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$faculty = $_GET['faculty'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? '';

// setup limit halaman
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// query dasar
$base_query = "SELECT p.*, f.nama_fakultas AS fakultas FROM products p ";
$count_query = "SELECT COUNT(*) AS total FROM products p "; 

$join_faculty = " JOIN faculties f ON p.faculty_id = f.id ";
$base_query .= $join_faculty;
$count_query .= $join_faculty;

// kondisi where untuk filtering
$where_clauses = [];

if (!empty($search)) {
    $where_clauses[] = "p.name LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'";
}
if (!empty($category)) {
    $where_clauses[] = "p.category_id = " . (int)$category;
}
if (!empty($faculty)) {
    $where_clauses[] = "p.faculty_id = " . (int)$faculty;
}
if (!empty($min_price)) {
    $where_clauses[] = "p.price >= " . (int)$min_price;
}
if (!empty($max_price)) {
    $where_clauses[] = "p.price <= " . (int)$max_price;
}

// jika ada filter yang aktif 
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
    $base_query .= $where_sql;
    $count_query .= $where_sql;
}

// total halaman untuk page filter
$total_result = mysqli_query($koneksi, $count_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

// sorting
if ($sort === 'termurah') {
    $base_query .= " ORDER BY p.price ASC";
} elseif ($sort === 'termahal') {
    $base_query .= " ORDER BY p.price DESC";
} else {
    $base_query .= " ORDER BY p.id DESC"; 
}

// Tambahkan limitasi data per halaman dan eksekusi
$base_query .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $base_query);

$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>html { scroll-behavior: smooth; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <div class="bg-sky-700 text-white text-xs py-2 shadow-xs z-10">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <span>Hai <strong><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Mahasiswa' ?></strong>! Welcome to OperIn UNS.</span>
        </div>
    </div>

    <?php include 'components/navbar.php'; ?>
    <?php include 'components/filters.php'; ?>

    <main class="max-w-7xl mx-auto px-4 md:px-6 w-full py-8 space-y-6 flex-1">
        
        <div class="flex items-end justify-between border-b border-slate-200 pb-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-5 bg-sky-500 rounded-xs"></span>
                    Semua Produk
                </h2>
            </div>
            <a href="produk.php" class="text-xs font-bold text-sky-600 hover:text-orange-500 transition-colors shrink-0">← Beranda Utama</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            <?php if (empty($products)): ?>
                <div class="col-span-full bg-white border border-dashed border-slate-300 text-center p-12 rounded-xl text-xs text-slate-400 font-medium">
                    Tidak ada produk yang cocok dengan filter pencarian Anda saat ini.
                </div>
            <?php else: ?>
                <?php foreach ($products as $p) : ?>
                <a href="detailProduk.php?id=<?= $p['id'] ?>" class="group block h-full">
                    <div class="bg-white border border-slate-200/70 rounded-xl shadow-xs overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 h-full flex flex-col relative">
                        
                        <div class="w-full aspect-square bg-slate-50 overflow-hidden shrink-0">
                            <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        
                        <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                            <p class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-sky-600 transition-colors">
                                <?= htmlspecialchars($p['name']) ?>
                            </p>
                            <div class="space-y-1">
                                <p class="text-base font-bold text-slate-900 tracking-tight">
                                    Rp<?= number_format($p['price'], 0, ',', '.') ?>
                                </p>
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

        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center gap-1.5 pt-8">
            <?php 
                // untuk mempertahankan filter yang aktif saat pindah halaman
                function getPageUrl($pageNum) {
                    $params = $_GET;
                    $params['page'] = $pageNum;
                    return '?' . http_build_query($params);
                }
            ?>
            
            <?php if ($page > 1): ?>
                <a href="fullProduk.php<?= getPageUrl($page - 1) ?>" class="px-3 py-1.5 bg-white text-slate-700 border border-slate-200 rounded-lg text-xs font-bold hover:bg-slate-100 transition-colors">Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="fullProduk.php<?= getPageUrl($i) ?>" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors <?= $i === $page ? 'bg-sky-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="fullProduk.php<?= getPageUrl($page + 1) ?>" class="px-3 py-1.5 bg-white text-slate-700 border border-slate-200 rounded-lg text-xs font-bold hover:bg-slate-100 transition-colors">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>