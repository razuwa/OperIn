<?php
session_start();
require 'require/koneksi.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: produk.php"); exit();
}

// PERBAIKAN: Menambahkan p.image pada kueri SELECT
$query_pending = "SELECT pr.id, p.name AS nama_produk, p.image, u.nama AS nama_penjual, pp.package_name, pay.amount, pay.payment_proof, pr.created_at
                  FROM promotion_requests pr
                  JOIN products p ON pr.product_id = p.id
                  JOIN users u ON pr.user_id = u.id
                  JOIN promo_packages pp ON pr.package_id = pp.id
                  JOIN payments pay ON pr.id = pay.promotion_req_id
                  WHERE pr.status = 'pending'
                  ORDER BY pr.created_at ASC";
$result_pending = mysqli_query($koneksi, $query_pending);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Pembayaran Promo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 p-4 md:p-8">

    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-slate-900">Validasi Transfer Promo</h1>
            <a href="dashboardAdmin.php" class="text-xs font-bold text-slate-600 hover:underline">← Dashboard</a>
        </div>

        <?php if (isset($_GET['msg'])) : ?>
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold shadow-xs">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap">
                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4 text-center">Bukti Transfer</th>
                        <th class="px-6 py-4 text-center">Setujui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <?php if (mysqli_num_rows($result_pending) === 0): ?>
                        <tr><td colspan="5" class="text-center py-12 text-slate-400 font-medium">Tidak ada antrean validasi pembayaran saat ini.</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result_pending)) : ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-slate-400"><?= date('d M, H:i', strtotime($row['created_at'])) ?></td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Foto Produk" loading="lazy" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0 shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800"><?= htmlspecialchars($row['nama_produk']) ?></p>
                                        <p class="text-[10px] text-slate-400">Paket: <?= htmlspecialchars($row['package_name']) ?> | Oleh: <?= htmlspecialchars($row['nama_penjual']) ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-bold text-amber-600">Rp<?= number_format($row['amount'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank" class="inline-block px-3 py-1 bg-sky-100 text-sky-700 font-bold rounded hover:bg-sky-200 transition-colors">
                                    Lihat Struk
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="prosesKelolaPromo.php?action=approve&id=<?= $row['id'] ?>" onclick="return confirm('Setujui?')" class="px-3 py-1.5 bg-emerald-500 text-white font-bold rounded-lg hover:bg-emerald-600 shadow-xs">Setujui</a>
                                    <a href="prosesKelolaPromo.php?action=reject&id=<?= $row['id'] ?>" onclick="return confirm('Tolak?')" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 font-bold rounded-lg hover:bg-red-100">Tolak</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>