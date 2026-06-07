<?php
session_start();
require 'require/koneksi.php';
require 'needLogin.php'; 

$user_id = $_SESSION['user_id'];
$error_msg = $_GET['error'] ?? '';
$success_msg = $_GET['success'] ?? '';

// update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $whatsapp_raw = trim($_POST['whatsapp']);
    $faculty_id = (int)$_POST['faculty_id'];

    // convert whatsapp
    $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp_raw);
    if (str_starts_with($whatsapp, '08')) {
        $whatsapp = '62' . substr($whatsapp, 1);
    } elseif (str_starts_with($whatsapp, '8')) {
        $whatsapp = '62' . $whatsapp;
    }

    // validasi duplikasi email
    $cek_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email' AND id != $user_id");
    if (mysqli_num_rows($cek_email) > 0) {
        header("Location: profil.php?error=" . urlencode("Email sudah digunakan oleh mahasiswa lain."));
        exit();
    }

    $query_update = "UPDATE users SET nama='$nama', email='$email', whatsapp='$whatsapp', faculty_id=$faculty_id WHERE id=$user_id";
    
    if (mysqli_query($koneksi, $query_update)) {
        $_SESSION['user_name'] = $nama;
        header("Location: profil.php?success=" . urlencode("Profil Anda berhasil diperbarui."));
        exit();
    } else {
        header("Location: profil.php?error=" . urlencode("Gagal memperbarui database: " . mysqli_error($koneksi)));
        exit();
    }
}

// user saat ini
$query_user = "SELECT u.*, f.nama_fakultas AS fakultas 
               FROM users u 
               LEFT JOIN faculties f ON u.faculty_id = f.id 
               WHERE u.id = $user_id";
$result_user = mysqli_query($koneksi, $query_user);
$u = mysqli_fetch_assoc($result_user);

// statisik user
$total_produk_saya = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM products WHERE user_id = $user_id"))['total'];

//dropdown
$result_faculties = mysqli_query($koneksi, "SELECT * FROM faculties ORDER BY nama_fakultas ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <?php include 'components/greetings.php'; ?>
    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-5xl w-full mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs text-slate-400">
                <a href="produk.php" class="hover:text-sky-600 transition-colors">Beranda</a> 
                <span class="mx-2">/</span> 
                <span class="text-slate-600 font-medium">Profil Saya</span>
            </p>
            <a href="produk.php" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="border-b border-slate-200 pb-3 mb-6">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-1 h-5 bg-sky-500 rounded-xs"></span>
                Pengaturan Akun
            </h1>
        </div>

        <?php if (!empty($error_msg)) : ?><div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs font-medium mb-4"><?= htmlspecialchars($error_msg) ?></div><?php endif; ?>
        <?php if (!empty($success_msg)) : ?><div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-xl text-xs font-medium mb-4"><?= htmlspecialchars($success_msg) ?></div><?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            
            <div class="md:col-span-4 bg-white border border-slate-200/60 p-6 rounded-2xl shadow-xs text-center space-y-4">
                <div class="w-20 h-20 rounded-full bg-linear-to-tr from-sky-500 to-sky-600 text-white flex items-center justify-center font-bold text-2xl border-4 border-white shadow-md mx-auto">
                    <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($u['nama']) ?></h2>
                    <p class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($u['email']) ?></p>
                </div>
                
                <div class="inline-block bg-slate-50 border border-slate-200 rounded-xl px-4 py-1.5 text-center w-full">
                    <span class="text-xs font-bold text-sky-600 capitalize"><?= htmlspecialchars($u['role']) ?></span>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-around text-center">
                    <div>
                        <p class="text-xs font-bold text-slate-400">Barang Diposting</p>
                        <p class="text-xl font-extrabold text-slate-800 mt-0.5"><?= $total_produk_saya ?></p>
                    </div>
                    <div class="w-px h-8 bg-slate-200 my-auto"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400">Etalase Saya</p>
                        <a href="kelolaProduk.php" class="inline-block text-xs font-bold text-sky-500 hover:text-orange-500 transition-colors mt-1">Kelola Profil </a>
                    </div>
                </div>
            </div>
            <div class="md:col-span-8 bg-white border border-slate-200/60 p-6 rounded-2xl shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100">Ubah Profil</h3>
                
                <form method="POST" action="" class="space-y-4 text-xs font-medium text-slate-600">
                    
                    <div>
                        <label class="block mb-1 text-slate-500">Nama Lengkap Mahasiswa</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($u['nama']) ?>" required 
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:border-sky-500 bg-slate-50/50">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-slate-500">Alamat Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required 
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:border-sky-500 bg-slate-50/50">
                        </div>
                        <div>
                            <label class="block mb-1 text-slate-500">Nomor WhatsApp (Format: 08xx / 628xx)</label>
                            <input type="text" name="whatsapp" value="<?= htmlspecialchars($u['whatsapp']) ?>" required 
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:border-sky-500 bg-slate-50/50">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-slate-500">Fakultas / Lokasi Pertemuan Default</label>
                        <select name="faculty_id" required 
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:border-sky-500 bg-white cursor-pointer">
                            <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                                <option value="<?= $f['id'] ?>" <?= $u['faculty_id'] == $f['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f['nama_fakultas']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" name="update_profile" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg transition-all shadow-xs cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>