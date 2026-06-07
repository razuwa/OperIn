<?php
session_start();
require 'require/koneksi.php'; 

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php?error=" . urlencode("Silakan Login Terlebih Dahulu"));
    exit();
}

$error_msg = $_GET['error'] ?? '';
$success_msg = $_GET['success'] ?? '';

// hapus produk
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = (int)$_GET['id'];
    $query_delete = "DELETE FROM products WHERE id = $delete_id";
    if (mysqli_query($koneksi, $query_delete)) {
        header("Location: dashboardAdmin.php?success=" . urlencode("Produk berhasil dihapus"));
        exit();
    } else {
        header("Location: dashboardAdmin.php?error=" . urlencode("Gagal menghapus produk: " . mysqli_error($koneksi)));
        exit();
    }
}

// simpan produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = (int)$_POST['product_id'];
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $price = (int)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $faculty_id = (int)$_POST['faculty_id'];
    $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);

    // Cek apakah ada file gambar baru yang diunggah
    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tmp_name  = $_FILES['image']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $new_file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $folder_destination = "assets/" . $new_file_name;

        if (move_uploaded_file($tmp_name, $folder_destination)) {
            $query_update = "UPDATE products SET name='$name', price=$price, category_id=$category_id, faculty_id=$faculty_id, kondisi='$kondisi', description='$description', image='$folder_destination' WHERE id=$product_id";
        } else {
            header("Location: dashboardAdmin.php?error=" . urlencode("Gagal mengunggah gambar baru"));
            exit();
        }
    } else {
        $query_update = "UPDATE products SET name='$name', price=$price, category_id=$category_id, faculty_id=$faculty_id, kondisi='$kondisi', description='$description' WHERE id=$product_id";
    }

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: dashboardAdmin.php?success=" . urlencode("Produk berhasil diperbarui"));
        exit();
    } else {
        header("Location: dashboardAdmin.php?error=" . urlencode("Gagal memperbarui database: " . mysqli_error($koneksi)));
        exit();
    }
}

// ambil data untuk edit
$edit_mode = false;
$product_to_edit = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $query_edit = "SELECT * FROM products WHERE id = $edit_id";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if ($result_edit && mysqli_num_rows($result_edit) === 1) {
        $edit_mode = true;
        $product_to_edit = mysqli_fetch_assoc($result_edit);
    }
}

// ambil data untuk statistik
$total_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM products"))['total'];
$total_mahasiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role = 'mahasiswa'"))['total'];
$total_fakultas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM faculties"))['total'];

$result_faculties = mysqli_query($koneksi, "SELECT * FROM faculties ORDER BY nama_fakultas ASC");
$result_categories = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY nama_kategori ASC");

// mengambil semua data produk
$query_tabel = "SELECT p.*, f.nama_fakultas AS fakultas, c.nama_kategori AS kategori 
                FROM products p
                JOIN faculties f ON p.faculty_id = f.id
                JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";
$result_tabel = mysqli_query($koneksi, $query_tabel);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen flex text-gray-800 antialiased">

    <?php include 'components/sidebarAdmin.php'; ?>
    
    <div class="flex-1 flex flex-col pl-64">
        
        <?php include 'components/headerAdmin.php'; ?>

        <main class="p-8 max-w-7xl w-full mx-auto space-y-8 mt-16">
            
            <?php if (!empty($error_msg)) : ?><div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium"><?= htmlspecialchars($error_msg) ?></div><?php endif; ?>
            <?php if (!empty($success_msg)) : ?><div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-xs font-medium"><?= htmlspecialchars($success_msg) ?></div><?php endif; ?>

            <?php if ($edit_mode) : ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-2xl transition-all">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Ubah Informasi Barang #<?= $product_to_edit['id'] ?></h2>
                        <a href="dashboardAdmin.php" class="text-xs text-gray-400 hover:text-gray-600 font-medium">Batalkan</a>
                    </div>
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="grid grid-cols-2 gap-4 text-xs">
                        <input type="hidden" name="product_id" value="<?= $product_to_edit['id'] ?>">
                        
                        <div class="col-span-2">
                            <label class="block text-gray-500 font-semibold mb-1">Nama Produk</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($product_to_edit['name']) ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-sky-500">
                        </div>
                        
                        <div>
                            <label class="block text-gray-500 font-semibold mb-1">Harga (Rp)</label>
                            <input type="number" name="price" value="<?= $product_to_edit['price'] ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-gray-500 font-semibold mb-1">Kondisi</label>
                            <select name="kondisi" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-sky-500 bg-white">
                                <option value="Baru" <?= $product_to_edit['kondisi'] === 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Bekas" <?= $product_to_edit['kondisi'] === 'Bekas' ? 'selected' : '' ?>>Bekas</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-500 font-semibold mb-1">Kategori Produk</label>
                            <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-sky-500 bg-white">
                                <?php while ($c = mysqli_fetch_assoc($result_categories)) : ?>
                                    <option value="<?= $c['id'] ?>" <?= $product_to_edit['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-500 font-semibold mb-1">Fakultas / Lokasi COD</label>
                            <select name="faculty_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-sky-500 bg-white">
                                <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                                    <option value="<?= $f['id'] ?>" <?= $product_to_edit['faculty_id'] == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nama_fakultas']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-500 font-semibold mb-1">Deskripsi Produk</label>
                            <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500"><?= htmlspecialchars($product_to_edit['description']) ?></textarea>
                        </div>

                        <div class="col-span-2 border border-gray-200 rounded-xl p-3 flex items-center gap-4 bg-gray-50">
                            <img src="<?= htmlspecialchars($product_to_edit['image']) ?>" class="w-12 h-12 object-cover rounded border border-gray-300 bg-white">
                            <div class="flex-1">
                                <label class="block text-gray-600 font-semibold mb-0.5">Ganti Gambar Produk (Opsional)</label>
                                <input type="file" name="image" accept="image/*" class="text-xs text-gray-400 file:mr-3 file:py-1 file:px-2 file:rounded file:border file:border-gray-300 file:text-xs file:bg-white file:text-slate-700 hover:file:bg-gray-50">
                            </div>
                        </div>

                        <div class="col-span-2 flex gap-2 pt-2">
                            <button type="submit" name="update_product" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg font-bold cursor-pointer transition-all">Simpan Perubahan</button>
                            <a href="dashboardAdmin.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold text-center">Batal</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Total Produk Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_produk ?></h3>
                    </div>
                    <div class="p-3 bg-sky-50 rounded-xl text-sky-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Pengguna Terdaftar</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_mahasiswa ?></h3>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-xl text-orange-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Fakultas Terlibat</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_fakultas ?></h3>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="font-bold text-lg text-gray-800">Daftar Produk Etalase</h2>
                    <span class="text-xs font-semibold bg-gray-200 text-gray-600 px-2.5 py-1 rounded-md">Mode Moderasi</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-semibold border-b border-gray-100">
                                <th class="p-4 pl-6 w-20">Foto</th>
                                <th class="p-4">Nama Barang</th>
                                <th class="p-4">Kategori</th> 
                                <th class="p-4">Fakultas</th>
                                <th class="p-4">Harga</th>
                                <th class="p-4 text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            <?php if (mysqli_num_rows($result_tabel) === 0) : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-400 font-medium">Tidak ada data produk di database.</td>
                                </tr>
                            <?php else : ?>
                                <?php while ($row = mysqli_fetch_assoc($result_tabel)) : ?>
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="p-4 pl-6">
                                        <img src="<?= htmlspecialchars($row['image']) ?>" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                    </td>
                                    <td class="p-4 font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="p-4">
                                        <span class="bg-sky-50 text-sky-600 border border-sky-200 px-2 py-0.5 rounded text-xs font-medium">
                                            <?= htmlspecialchars($row['kategori']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded text-xs font-medium text-gray-600">
                                            <?= htmlspecialchars($row['fakultas']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-semibold text-orange-500">Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
                                    <td class="p-4 text-center">
                                        <div class="inline-flex gap-2">
                                            <a href="dashboardAdmin.php?action=edit&id=<?= $row['id'] ?>" class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1 rounded-lg text-xs font-semibold transition-all">
                                                Edit
                                            </a>
                                            <a href="dashboardAdmin.php?action=delete&id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus produk \'<?= addslashes($row['name']) ?>\' ini secara permanen?')" 
                                               class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-xs font-semibold transition-all">
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>