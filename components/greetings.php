<div class="bg-sky-700 text-white text-xs py-2 shadow-xs z-10">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
        <span>Hai <strong><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Mahasiswa' ?></strong>! Welcome to OperIn.</span>
    </div>
</div>