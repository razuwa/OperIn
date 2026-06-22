<?php
session_start();
require 'require/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ajukanPromo.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$package_id = (int)$_POST['package_id'];

// Validasi kepemilikan dan status (seperti sebelumnya)
$cek_milik = mysqli_query($koneksi, "SELECT id FROM products WHERE id = $product_id AND user_id = $user_id");
if (mysqli_num_rows($cek_milik) === 0) {
    header("Location: ajukanPromo.php?error=" . urlencode("Produk tidak valid.")); exit();
}

$cek_promo = mysqli_query($koneksi, "SELECT id FROM promotion_requests WHERE product_id = $product_id AND status IN ('pending', 'approved') AND (end_date IS NULL OR end_date > NOW())");
if (mysqli_num_rows($cek_promo) > 0) {
    header("Location: ajukanPromo.php?error=" . urlencode("Barang ini masih memiliki promo aktif/pending.")); exit();
}

// Ambil harga paket untuk dimasukkan ke tabel payments
$query_harga = mysqli_query($koneksi, "SELECT price FROM promo_packages WHERE id = $package_id");
$harga_paket = mysqli_fetch_assoc($query_harga)['price'];

// PROSES UPLOAD FILE BUKTI BAYAR
$target_dir = "assets/";
$file_extension = strtolower(pathinfo($_FILES["bukti_bayar"]["name"], PATHINFO_EXTENSION));
$new_filename = "pay_" . time() . "_" . rand(100,999) . "." . $file_extension;
$target_file = $target_dir . $new_filename;

// Hanya izinkan gambar
if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'webp'])) {
    header("Location: ajukanPromo.php?error=" . urlencode("Format file harus JPG/PNG/WEBP.")); exit();
}

if (!move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $target_file)) {
    header("Location: ajukanPromo.php?error=" . urlencode("Gagal mengunggah foto bukti bayar.")); exit();
}

// TRANSAKSI DATABASE (Memasukkan ke 2 tabel sekaligus)
mysqli_begin_transaction($koneksi);
try {
    // 1. Insert ke tabel promotion_requests
    $q_promo = "INSERT INTO promotion_requests (user_id, product_id, package_id, status) VALUES ($user_id, $product_id, $package_id, 'pending')";
    mysqli_query($koneksi, $q_promo);
    
    // Ambil ID dari promotion_request yang baru saja masuk
    $promo_req_id = mysqli_insert_id($koneksi);

    // 2. Insert ke tabel payments
    $q_pay = "INSERT INTO payments (promotion_req_id, amount, payment_proof, status) VALUES ($promo_req_id, $harga_paket, '$target_file', 'pending')";
    mysqli_query($koneksi, $q_pay);

    // Jika sukses berdua, permanenkan data
    mysqli_commit($koneksi);
    header("Location: ajukanPromo.php?success=" . urlencode("Pengajuan dan bukti bayar berhasil terkirim."));
} catch (Exception $e) {
    // Jika ada yang gagal, batalkan semua insert dan hapus foto
    mysqli_rollback($koneksi);
    if(file_exists($target_file)) unlink($target_file);
    header("Location: ajukanPromo.php?error=" . urlencode("Terjadi kesalahan sistem database."));
}
exit();