<?php
session_start();
require 'require/koneksi.php';
require 'require/functions.php';

date_default_timezone_set('Asia/Jakarta');
mysqli_query($koneksi, "SET time_zone = '+07:00'");

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: produk.php"); exit();
}

$filter_status = $_GET['status'] ?? 'pending';

if ($filter_status === 'aktif') {
    $query_data = "SELECT pr.id, p.name AS nama_produk, p.image, u.nama AS nama_penjual, pp.package_name, pay.amount, pay.payment_proof, pr.start_date, pr.end_date
                   FROM promotion_requests pr
                   JOIN products p ON pr.product_id = p.id
                   JOIN users u ON pr.user_id = u.id
                   JOIN promo_packages pp ON pr.package_id = pp.id
                   JOIN payments pay ON pr.id = pay.promotion_req_id
                   WHERE pr.status = 'approved' AND NOW() BETWEEN pr.start_date AND pr.end_date
                   ORDER BY pr.start_date DESC";
    $page_title = "Daftar Iklan Promo Aktif";
} else {
    $query_data = "SELECT pr.id, p.name AS nama_produk, p.image, u.nama AS nama_penjual, pp.package_name, pay.amount, pay.payment_proof, pr.created_at
                   FROM promotion_requests pr
                   JOIN products p ON pr.product_id = p.id
                   JOIN users u ON pr.user_id = u.id
                   JOIN promo_packages pp ON pr.package_id = pp.id
                   JOIN payments pay ON pr.id = pay.promotion_req_id
                   WHERE pr.status = 'pending'
                   ORDER BY pr.created_at ASC";
    $page_title = "Antrean Persetujuan Pembayaran";
}

$result_data = mysqli_query($koneksi, $query_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <?php include 'components/sidebarAdmin.php'; ?>
    <?php include 'components/headerAdmin.php'; ?>

    <main class="md:ml-64 px-4 md:px-8 pb-8 pt-24 md:pt-28">
        <div class="max-w-6xl mx-auto space-y-6">
            
            <?php if (isset($_GET['msg'])) : ?>
                <div id="ajaxMessage" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold shadow-xs">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php else : ?>
                <div id="ajaxMessage" class="hidden px-4 py-3 rounded-xl text-xs font-bold shadow-xs"></div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                            <tr>
                                <?php if ($filter_status === 'aktif') : ?>
                                    <th class="px-6 py-4">Mulai Promo</th>
                                    <th class="px-6 py-4">Berakhir Pada</th>
                                    <th class="px-6 py-4">Barang</th>
                                    <th class="px-6 py-4">Nominal</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                <?php else : ?>
                                    <th class="px-6 py-4">Waktu Request</th>
                                    <th class="px-6 py-4">Barang</th>
                                    <th class="px-6 py-4">Nominal</th>
                                    <th class="px-6 py-4 text-center">Bukti Transfer</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <?php if (mysqli_num_rows($result_data) === 0): ?>
                                <tr><td colspan="5" class="text-center py-12 text-slate-400 font-medium">Tidak ada data untuk ditampilkan pada kategori ini.</td></tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($result_data)) : ?>
                                <tr data-promo-row="<?= $row['id'] ?>" class="hover:bg-slate-50/50 transition-colors">
                                    
                                    <?php if ($filter_status === 'aktif') : ?>
                                        <td class="px-6 py-4 text-emerald-600 font-bold"><?= date('d M Y, H:i', strtotime($row['start_date'])) ?></td>
                                        <td class="px-6 py-4 text-red-500 font-bold"><?= date('d M Y, H:i', strtotime($row['end_date'])) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Foto" loading="lazy" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                                <div>
                                                    <p class="font-bold text-slate-800"><?= htmlspecialchars($row['nama_produk']) ?></p>
                                                    <p class="text-[10px] text-slate-400"><?= htmlspecialchars($row['package_name']) ?> | Oleh: <?= htmlspecialchars($row['nama_penjual']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-500"><?= format_rupiah($row['amount']) ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase">TAYANG</span>
                                        </td>
                                    <?php else : ?>
                                        <td class="px-6 py-4 text-slate-400 font-medium"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Foto" loading="lazy" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                                <div>
                                                    <p class="font-bold text-slate-800"><?= htmlspecialchars($row['nama_produk']) ?></p>
                                                    <p class="text-[10px] text-slate-400"><?= htmlspecialchars($row['package_name']) ?> | Oleh: <?= htmlspecialchars($row['nama_penjual']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-amber-600"><?= format_rupiah($row['amount']) ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank" class="inline-block px-3 py-1 bg-sky-100 text-sky-700 font-bold rounded hover:bg-sky-200 transition-colors">Lihat Struk</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <a href="prosesKelolaPromo.php?action=approve&id=<?= $row['id'] ?>" data-ajax-action="promo" data-confirm="Uang sudah masuk?" class="px-4 py-2 bg-emerald-500 text-white font-bold rounded-lg hover:bg-emerald-600 shadow-xs transition-colors">Setujui</a>
                                                <a href="prosesKelolaPromo.php?action=reject&id=<?= $row['id'] ?>" data-ajax-action="promo" data-confirm="Tolak pengajuan ini?" class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 font-bold rounded-lg hover:bg-red-100 transition-colors">Tolak</a>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script>
        const promoMessage = document.getElementById('ajaxMessage');

        function showPromoMessage(message, success = true) {
            promoMessage.textContent = message;
            promoMessage.className = (success
                ? 'bg-emerald-50 border border-emerald-200 text-emerald-700'
                : 'bg-red-50 border border-red-200 text-red-700') + ' px-4 py-3 rounded-xl text-xs font-bold shadow-xs';
        }

        document.querySelectorAll('[data-ajax-action="promo"]').forEach((link) => {
            link.addEventListener('click', async (event) => {
                event.preventDefault();
                const confirmText = link.dataset.confirm;
                if (confirmText && !confirm(confirmText)) return;

                const originalText = link.textContent;
                link.textContent = 'Memproses...';
                link.classList.add('pointer-events-none', 'opacity-60');

                try {
                    const response = await fetch(link.href + '&ajax=1', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await response.json();
                    showPromoMessage(result.message, result.success);

                    if (result.success) {
                        const row = link.closest('[data-promo-row]');
                        if (row) row.remove();
                    } else {
                        link.textContent = originalText;
                        link.classList.remove('pointer-events-none', 'opacity-60');
                    }
                } catch (error) {
                    showPromoMessage('Gagal menghubungi server. Coba ulangi aksi.', false);
                    link.textContent = originalText;
                    link.classList.remove('pointer-events-none', 'opacity-60');
                }
            });
        });
    </script>
</body>
</html>
