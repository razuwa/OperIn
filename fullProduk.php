<?php
session_start();
require 'require/koneksi.php';

// 1. TANGKAP PARAMETER UTAMA
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$faculty = $_GET['faculty'] ?? '';
$kondisi = $_GET['kondisi'] ?? ''; 
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? '';
$type = $_GET['type'] ?? ''; 

// Setup limit halaman
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// 2. KONDISI AWAL JOIN
$base_query = "SELECT p.*, f.nama_fakultas AS fakultas FROM products p ";
$count_query = "SELECT COUNT(*) AS total FROM products p ";

$join_sql = " JOIN faculties f ON p.faculty_id = f.id ";

if ($type === 'promoted') {
    $join_sql .= " JOIN promotion_requests pr ON p.id = pr.product_id ";
}

$base_query .= $join_sql;
$count_query .= $join_sql;

// 3. KONDISI WHERE UTAMA
$where_clauses = ["p.status_barang = 'tersedia'"];

if ($type === 'promoted') {
    $where_clauses[] = "pr.status = 'approved'";
    $where_clauses[] = "NOW() BETWEEN pr.start_date AND pr.end_date";
}

// Filter tambahan dari user
if (!empty($search)) $where_clauses[] = "p.name LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'";
if (!empty($category)) $where_clauses[] = "p.category_id = " . (int)$category;
if (!empty($faculty)) $where_clauses[] = "p.faculty_id = " . (int)$faculty;
if (!empty($kondisi)) $where_clauses[] = "p.kondisi = '" . mysqli_real_escape_string($koneksi, $kondisi) . "'";
if (!empty($min_price)) $where_clauses[] = "p.price >= " . (int)$min_price;
if (!empty($max_price)) $where_clauses[] = "p.price <= " . (int)$max_price;

if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
    $base_query .= $where_sql;
    $count_query .= $where_sql;
}

// Urutan data
if ($sort === 'termurah') $base_query .= " ORDER BY p.price ASC";
elseif ($sort === 'termahal') $base_query .= " ORDER BY p.price DESC";
else $base_query .= " ORDER BY p.id DESC"; 

// Eksekusi data produk
$base_query .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $base_query);
$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Eksekusi total data
$total_result = mysqli_query($koneksi, $count_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

// 4. AMBIL NAMA TEKS UNTUK LABEL FILTER AKTIF
$active_filters = [];
// REVISI: Menghapus label "Jenis: Rekomendasi" dari array ini agar tidak memicu tombol reset filter aktif secara keliru
if (!empty($search)) $active_filters[] = "Pencarian: " . $search;
if (!empty($category)) {
    $q_cat = mysqli_query($koneksi, "SELECT nama_kategori FROM categories WHERE id = " . (int)$category);
    if ($r = mysqli_fetch_assoc($q_cat)) $active_filters[] = "Kategori: " . $r['nama_kategori'];
}
if (!empty($faculty)) {
    $q_fac = mysqli_query($koneksi, "SELECT nama_fakultas FROM faculties WHERE id = " . (int)$faculty);
    if ($r = mysqli_fetch_assoc($q_fac)) $active_filters[] = "Lokasi: " . $r['nama_fakultas'];
}
if (!empty($kondisi)) $active_filters[] = "Kondisi: " . $kondisi;
if (!empty($min_price) || !empty($max_price)) {
    $p_min = !empty($min_price) ? "Rp" . number_format($min_price,0,',','.') : "Rp0";
    $p_max = !empty($max_price) ? "Rp" . number_format($max_price,0,',','.') : "Tak terhingga";
    $active_filters[] = "Harga: $p_min - $p_max";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $type === 'promoted' ? 'Rekomendasi Produk' : 'Katalog Produk' ?> - OperIn</title>
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

    <main class="max-w-7xl mx-auto px-4 md:px-6 w-full py-6 space-y-6 flex-1">
        
        <div class="flex items-end justify-between border-b border-slate-200 pb-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-5 <?= $type === 'promoted' ? 'bg-amber-500' : 'bg-sky-500' ?> rounded-xs"></span>
                    <?= $type === 'promoted' ? 'Semua Produk Rekomendasi' : 'Semua Produk' ?>
                </h2>
            </div>
            <a href="produk.php" class="text-xs font-bold text-sky-600 hover:text-orange-500 transition-colors shrink-0">← Beranda Utama</a>
        </div>

        <?php if (!empty($active_filters)): ?>
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-500 font-medium mr-1">Filter Aktif:</span>
                <?php foreach ($active_filters as $af): ?>
                    <span class="bg-sky-100 border border-sky-200 text-sky-700 border text-[10px] font-bold px-2.5 py-1 rounded-lg">
                        <?= htmlspecialchars($af) ?>
                    </span>
                <?php endforeach; ?>
                <a href="fullProduk.php<?= $type === 'promoted' ? '?type=promoted' : '' ?>" class="text-[10px] font-bold text-red-500 hover:text-red-700 underline ml-2 transition-colors">
                    Reset Filter
                </a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            <?php if (empty($products)): ?>
                <div class="col-span-full bg-white border border-dashed border-slate-300 text-center p-12 rounded-xl text-xs text-slate-400 font-medium">
                    Tidak ada produk yang cocok dengan filter pencarian Anda saat ini.
                </div>
            <?php else: ?>
                <?php foreach ($products as $p) : ?>
                <a href="detailProduk.php?id=<?= $p['id'] ?>" class="group block h-full">
                    <div class="bg-white border border-slate-200/70 rounded-xl shadow-xs overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 h-full flex flex-col relative">
                        
                        <?php if ($type === 'promoted') : ?>
                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-md shadow-xs uppercase tracking-wide z-10">STAR</div>
                        <?php endif; ?>

                        <div class="w-full aspect-square bg-slate-50 overflow-hidden shrink-0">
                            <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                            <p class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-sky-600 transition-colors">
                                <?= htmlspecialchars($p['name']) ?>
                            </p>
                            <div class="space-y-1">
                                <p class="text-base font-bold <?= $type === 'promoted' ? 'text-amber-600' : 'text-slate-900' ?> tracking-tight">
                                    Rp<?= number_format($p['price'], 0, ',', '.') ?>
                                </p>
                                <div class="flex justify-between items-center text-[11px] pt-1.5 border-t border-slate-100">
                                    <span class="text-slate-400 font-medium truncate max-w-[65px]"><?= htmlspecialchars($p['fakultas']) ?></span>
                                    <span class="px-1.5 py-0.5 rounded font-bold text-[10px] <?= $type === 'promoted' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-slate-100 text-slate-600 border-slate-200' ?> border">
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