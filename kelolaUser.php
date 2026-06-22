<?php
session_start();
require 'require/koneksi.php';

date_default_timezone_set('Asia/Jakarta');
mysqli_query($koneksi, "SET time_zone = '+07:00'");

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: produk.php"); exit();
}

$page_title = "Manajemen Pengguna";

$query_users = "SELECT u.*, f.nama_fakultas 
                FROM users u 
                LEFT JOIN faculties f ON u.faculty_id = f.id 
                ORDER BY u.created_at DESC";
$result_users = mysqli_query($koneksi, $query_users);
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
        <div class="max-w-7xl mx-auto space-y-6">
            
            <?php if (isset($_GET['msg'])) : ?>
                <div class="bg-sky-50 border border-sky-200 text-sky-700 px-4 py-3 rounded-xl text-xs font-bold shadow-xs">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4">Informasi Pengguna</th>
                                <th class="px-6 py-4 text-center">Fakultas</th>
                                <th class="px-6 py-4 text-center">Role / Jabatan</th>
                                <th class="px-6 py-4 text-center">Status Akun</th>
                                <th class="px-6 py-4 text-center">Aksi Administrator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <?php if (mysqli_num_rows($result_users) === 0): ?>
                                <tr><td colspan="5" class="text-center py-12 text-slate-400 font-medium">Tidak ada pengguna.</td></tr>
                            <?php else: ?>
                                <?php while ($u = mysqli_fetch_assoc($result_users)) : ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0">
                                                <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($u['nama']) ?></p>
                                                <p class="text-[10px] text-slate-400"><?= htmlspecialchars($u['email']) ?> | WA: <?= htmlspecialchars($u['whatsapp']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-medium text-slate-500">
                                        <?= htmlspecialchars($u['nama_fakultas']) ?>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase <?= $u['role'] === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' ?>">
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase <?= $u['status'] === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= htmlspecialchars($u['status']) ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-4 font-bold">
                                            <a href="prosesKelolaUser.php?action=reset&id=<?= $u['id'] ?>" onclick="return confirm('Reset password pengguna ini menjadi 123456?')" class="text-sky-600 hover:underline">
                                                Reset
                                            </a>
                                            
                                            <?php if ($u['id'] !== $_SESSION['user_id']) : ?>
                                                <span class="text-slate-200">|</span>
                                                <a href="prosesKelolaUser.php?action=role&id=<?= $u['id'] ?>" onclick="return confirm('Ubah role pengguna ini?')" class="text-amber-600 hover:underline">
                                                    Ubah Role
                                                </a>
                                                
                                                <span class="text-slate-200">|</span>
                                                <?php if ($u['status'] === 'active') : ?>
                                                    <a href="prosesKelolaUser.php?action=status&id=<?= $u['id'] ?>" onclick="return confirm('Blokir pengguna ini dari aplikasi?')" class="text-orange-600 hover:underline">
                                                        Ban
                                                    </a>
                                                <?php else : ?>
                                                    <a href="prosesKelolaUser.php?action=status&id=<?= $u['id'] ?>" onclick="return confirm('Buka blokir pengguna ini?')" class="text-emerald-600 hover:underline">
                                                        Unban
                                                    </a>
                                                <?php endif; ?>

                                                <span class="text-slate-200">|</span>
                                                <a href="prosesKelolaUser.php?action=delete&id=<?= $u['id'] ?>" onclick="return confirm('BAHAYA: Hapus pengguna ini PERMANEN beserta seluruh barang dagangannya?')" class="text-red-600 hover:underline">
                                                    Hapus
                                                </a>
                                            <?php else : ?>
                                                <span class="text-slate-200">|</span>
                                                <span class="text-slate-400 font-normal italic">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>