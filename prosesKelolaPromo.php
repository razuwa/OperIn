<?php
session_start();
require 'require/koneksi.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: produk.php"); exit();
}
if (!isset($_GET['action']) || !isset($_GET['id'])) {
    header("Location: kelolaPromoAdmin.php"); exit();
}

$admin_id = $_SESSION['user_id'];
$promo_id = (int)$_GET['id'];
$action = $_GET['action'];

mysqli_begin_transaction($koneksi);
try {
    if ($action === 'approve') {
        // 1. Update status promo dan jadwal tayang
        mysqli_query($koneksi, "UPDATE promotion_requests pr
                                JOIN promo_packages pp ON pr.package_id = pp.id
                                SET pr.status = 'approved', pr.start_date = NOW(), pr.end_date = DATE_ADD(NOW(), INTERVAL pp.duration_days DAY), pr.reviewed_by = $admin_id
                                WHERE pr.id = $promo_id");
        // 2. Update status tabel bayar
        mysqli_query($koneksi, "UPDATE payments SET status = 'verified', verified_at = NOW() WHERE promotion_req_id = $promo_id");
        $msg = "Pembayaran valid. Promo berhasil ditayangkan.";
        
    } elseif ($action === 'reject') {
        mysqli_query($koneksi, "UPDATE promotion_requests SET status = 'rejected', reviewed_by = $admin_id WHERE id = $promo_id");
        mysqli_query($koneksi, "UPDATE payments SET status = 'failed', verified_at = NOW() WHERE promotion_req_id = $promo_id");
        $msg = "Pembayaran ditolak/struk tidak valid.";
    }
    
    mysqli_commit($koneksi);
    header("Location: kelolaPromo.php?msg=" . urlencode($msg));
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    header("Location: kelolaPromo.php?msg=" . urlencode("Gagal memproses validasi."));
}
exit();