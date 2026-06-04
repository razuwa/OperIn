<?php
session_start();
require 'require/koneksi.php';
require 'needLogin.php';

$user_id = $_SESSION['user_id']; 
$error_msg = $_GET['error'] ?? '';
$success_msg = $_GET['success'] ?? '';

// hapus produk
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = (int)$_GET['id'];
    $query_delete = "DELETE FROM products WHERE id = $delete_id AND user_id = $user_id";
    
    if (mysqli_query($koneksi, $query_delete)) {
        header("Location: kelolaProduk.php?success=" . urlencode("Barang berhasil dihapus."));
        exit();
    } else {
        header("Location: kelolaProduk.php?error=" . urlencode("Gagal menghapus barang: " . mysqli_error($koneksi)));
        exit();
    }
}

// savve changes edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = (int)$_POST['product_id'];
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $price = (int)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $faculty_id = (int)$_POST['faculty_id'];
    $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);

    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tmp_name  = $_FILES['image']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $new_file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $folder_destination = "assets/" . $new_file_name;

        if (move_uploaded_file($tmp_name, $folder_destination)) {
            $query_update = "UPDATE products SET name='$name', price=$price, category_id=$category_id, faculty_id=$faculty_id, kondisi='$kondisi', description='$description', image='$folder_destination' WHERE id=$product_id AND user_id=$user_id";
        } else {
            header("Location: kelolaProduk.php?error=" . urlencode("Gagal mengunggah gambar baru."));
            exit();
        }
    } else {
        $query_update = "UPDATE products SET name='$name', price=$price, category_id=$category_id, faculty_id=$faculty_id, kondisi='$kondisi', description='$description' WHERE id=$product_id AND user_id=$user_id";
    }

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: kelolaProduk.php?success=" . urlencode("Informasi barang berhasil diperbarui."));
        exit();
    } else {
        header("Location: kelolaProduk.php?error=" . urlencode("Gagal memperbarui data: " . mysqli_error($koneksi)));
        exit();
    }
}

// ambil data untuk edit
$edit_mode = false;
$product_to_edit = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $query_edit = "SELECT * FROM products WHERE id = $edit_id AND user_id = $user_id";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if ($result_edit && mysqli_num_rows($result_edit) === 1) {
        $edit_mode = true;
        $product_to_edit = mysqli_fetch_assoc($result_edit);
    }
}

// query untuk dropdown
$result_faculties = mysqli_query($koneksi, "SELECT * FROM faculties ORDER BY nama_fakultas ASC");
$result_categories = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY nama_kategori ASC"); 

if (!$result_categories) {
    die("Gagal memuat data master kategori: " . mysqli_error($koneksi));
}

// query tabel utama
$query_tabel = "SELECT p.*, f.nama_fakultas AS fakultas, c.nama_kategori AS kategori 
                FROM products p
                JOIN faculties f ON p.faculty_id = f.id
                JOIN categories c ON p.category_id = c.id
                WHERE p.user_id = $user_id
                ORDER BY p.id DESC";
$result_tabel = mysqli_query($koneksi, $query_tabel);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapak Saya - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-amber-50 min-h-screen antialiased flex flex-col text-gray-800">

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8">
        
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800 tracking-tight">Daftar Produk Saya</h1>
                    <p class="text-xs text-gray-500">Ubah informasi atau hapus produk milik Anda.</p>
                </div>
                <a href="produk.php" class="text-xs font-semibold px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-all">
                    Kembali ke Etalase
                </a>
            </div>

            <?php if (!empty($error_msg)) : ?><div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-xs font-medium"><?= htmlspecialchars($error_msg) ?></div><?php endif; ?>
            <?php if (!empty($success_msg)) : ?><div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-lg text-xs font-medium"><?= htmlspecialchars($success_msg) ?></div><?php endif; ?>

            <?php if ($edit_mode) : ?>
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 max-w-2xl transition-all mx-auto w-full">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                        <h2 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Ubah Data Informasi Produk</h2>
                        <a href="kelolaProduk.php" class="text-xs text-gray-400 hover:text-gray-600 font-medium">Batal</a>
                    </div>
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="grid grid-cols-2 gap-4 text-xs">
                        <input type="hidden" name="product_id" value="<?= $product_to_edit['id'] ?>">
                        
                        <div class="col-span-2">
                            <label class="block text-gray-600 font-semibold mb-1">Nama Produk</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($product_to_edit['name']) ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white">
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 font-semibold mb-1">Harga (Rp)</label>
                            <input type="number" name="price" value="<?= $product_to_edit['price'] ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-gray-600 font-semibold mb-1">Kondisi Produk</label>
                            <select name="kondisi" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white">
                                <option value="Baru" <?= $product_to_edit['kondisi'] === 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Bekas" <?= $product_to_edit['kondisi'] === 'Bekas' ? 'selected' : '' ?>>Bekas</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-600 font-semibold mb-1">Kategori Produk</label>
                            <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white">
                                <?php while ($c = mysqli_fetch_assoc($result_categories)) : ?>
                                    <option value="<?= $c['id'] ?>" <?= $product_to_edit['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-600 font-semibold mb-1">Fakultas / Tempat Pertemuan COD</label>
                            <select name="faculty_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white">
                                <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                                    <option value="<?= $f['id'] ?>" <?= $product_to_edit['faculty_id'] == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nama_fakultas']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-gray-600 font-semibold mb-1">Deskripsi Produk</label>
                            <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:border-sky-500 bg-white"><?= htmlspecialchars($product_to_edit['description']) ?></textarea>
                        </div>

                        <div class="col-span-2 border border-gray-200 rounded-xl p-3 flex items-center gap-4 bg-white">
                            <img src="<?= htmlspecialchars($product_to_edit['image']) ?>" class="w-10 h-10 object-cover rounded border border-gray-200 bg-gray-50">
                            <div class="flex-1">
                                <label class="block text-gray-600 font-semibold mb-0.5">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" accept="image/*" class="text-xs text-gray-400 file:mr-3 file:py-1 file:px-2 file:rounded file:border file:border-gray-300 file:text-xs file:bg-white file:text-slate-700 hover:file:bg-gray-50">
                            </div>
                        </div>

                        <div class="col-span-2 flex gap-2 pt-2">
                            <button type="submit" name="update_product" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg font-bold cursor-pointer transition-all">Simpan Perubahan</button>
                            <a href="kelolaProduk.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold text-center">Batal</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200 uppercase tracking-wider">
                            <th class="py-3 px-4 w-16 text-center">Gambar</th>
                            <th class="py-3 px-4">Nama Produk</th>
                            <th class="py-3 px-4">Kategori</th> 
                            <th class="py-3 px-4">Harga</th>
                            <th class="py-3 px-4 w-20">Kondisi</th>
                            <th class="py-3 px-4">Lokasi COD</th>
                            <th class="py-3 px-4 text-center w-40">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <?php if (mysqli_num_rows($result_tabel) === 0) : ?>
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-400 font-medium">Anda belum pernah mengunggah produk.</td>
                            </tr>
                        <?php else : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_tabel)) : ?>
                                <tr class="hover:bg-gray-50/40 transition-colors">
                                    <td class="py-2.5 px-4 text-center">
                                        <img src="<?= htmlspecialchars($row['image']) ?>" class="w-8 h-8 object-cover rounded border border-gray-200 bg-white mx-auto">
                                    </td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-800"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="py-2.5 px-4">
                                        <span class="bg-sky-50 text-sky-600 border border-sky-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                                            <?= htmlspecialchars($row['kategori']) ?>
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-slate-900 font-medium">Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
                                    <td class="py-2.5 px-4">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wide <?= $row['kondisi'] === 'Baru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-700 border border-gray-300' ?> border">
                                            <?= htmlspecialchars($row['kondisi']) ?>
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-gray-500"><?= htmlspecialchars($row['fakultas']) ?></td>
                                    <td class="py-2.5 px-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="kelolaProduk.php?action=edit&id=<?= $row['id'] ?>" 
                                               class="inline-block bg-white text-gray-700 border border-gray-300 px-2.5 py-1 rounded hover:bg-gray-100 transition-all font-semibold text-center whitespace-nowrap">
                                                Edit
                                            </a>
                                            <a href="kelolaProduk.php?action=delete&id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                               class="inline-block bg-white text-red-600 border border-red-200 px-2.5 py-1 rounded hover:bg-red-50 transition-all font-semibold text-center whitespace-nowrap">
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

</body>
</html>