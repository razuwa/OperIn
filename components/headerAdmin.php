<header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center fixed right-0 top-0 left-64 bg-white/95 backdrop-blur z-20">
    <h1 class="text-xl font-bold text-gray-800">Ringkasan Sistem</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm bg-sky-50 text-sky-600 font-semibold px-3 py-1 rounded-full border border-sky-200">
            Sesi: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
        </span>
    </div>
</header>