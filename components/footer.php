<!-- FOOTER -->
<footer class="bg-sky-600 text-white pt-12 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8 border-b border-sky-400 pb-8">
        <div>
            <div class="flex items-center gap-2 mb-4">
                <img src="assets/logo-operin.png" alt="Logo Operin" class="max-h-8 brightness-0 invert">
                <span class="font-bold text-2xl">OperIn</span>
            </div>
            <p class="text-sky-100 text-sm leading-6">
                Platform jual beli barang preloved khusus mahasiswa UNS untuk menemukan, menjual, dan mempromosikan barang kampus dengan lebih mudah.
            </p>
        </div>

        <div>
            <h3 class="font-bold text-lg mb-4">Layanan</h3>
            <ul class="space-y-2 text-sm text-sky-100">
                <li><button type="button" data-footer-info="bantuan" class="hover:text-orange-400 transition-colors text-left cursor-pointer">Bantuan</button></li>
                <li><a href="tambahBarang.php" class="hover:text-orange-400 transition-colors">Cara Jual</a></li>
                <li><a href="fullProduk.php" class="hover:text-orange-400 transition-colors">Cara Beli</a></li>
                <li><button type="button" data-footer-info="keamanan" class="hover:text-orange-400 transition-colors text-left cursor-pointer">Keamanan</button></li>
            </ul>
        </div>

        <div>
            <h3 class="font-bold text-lg mb-4">Tentang OperIn</h3>
            <ul class="space-y-2 text-sm text-sky-100">
                <li><button type="button" data-footer-info="tentang" class="hover:text-orange-400 transition-colors text-left cursor-pointer">Tentang Kami</button></li>
                <li><button type="button" data-footer-info="privasi" class="hover:text-orange-400 transition-colors text-left cursor-pointer">Kebijakan Privasi</button></li>
                <li><button type="button" data-footer-info="syarat" class="hover:text-orange-400 transition-colors text-left cursor-pointer">Syarat & Ketentuan</button></li>
            </ul>
        </div>

        <div>
            <h3 class="font-bold text-lg mb-4">Ikuti Kami</h3>
            <div class="flex gap-4 mb-4">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram OperIn" class="p-2 bg-sky-700 rounded-full hover:bg-orange-400 transition-all"><img src="assets/instagram.svg" alt="" class="w-5 h-5"></a>
                <a href="https://github.com/" target="_blank" rel="noopener noreferrer" aria-label="GitHub OperIn" class="p-2 bg-sky-700 rounded-full hover:bg-orange-400 transition-all"><img src="assets/github.svg" alt="" class="w-5 h-5"></a>
                <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" aria-label="TikTok OperIn" class="p-2 bg-sky-700 rounded-full hover:bg-orange-400 transition-all"><img src="assets/tiktok.svg" alt="" class="w-5 h-5"></a>
            </div>
            <a href="mailto:help@operin.id" class="text-sm text-sky-100 font-medium hover:text-orange-400 transition-colors">Email: help@operin.id</a>
        </div>
    </div>

    <div class="text-center mt-8 text-sky-200 text-xs px-6">
        <p>&copy; 2026 OperIn. All rights reserved. Platform Preloved Mahasiswa.</p>
    </div>
</footer>

<div id="footerInfoModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white text-slate-800 rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 id="footerInfoTitle" class="font-bold text-lg text-slate-900">Informasi OperIn</h2>
            <button type="button" id="footerInfoClose" class="text-slate-400 hover:text-slate-700 text-2xl leading-none cursor-pointer" aria-label="Tutup">&times;</button>
        </div>
        <div class="px-5 py-4">
            <p id="footerInfoBody" class="text-sm leading-6 text-slate-600"></p>
        </div>
    </div>
</div>

<script>
    const footerInfo = {
        bantuan: {
            title: 'Bantuan',
            body: 'Butuh bantuan menggunakan OperIn? Hubungi help@operin.id atau gunakan menu profil untuk mengelola barang, promosi, dan data akun.'
        },
        keamanan: {
            title: 'Keamanan',
            body: 'OperIn menyarankan transaksi dilakukan dengan komunikasi yang jelas, pengecekan kondisi barang, dan bukti pembayaran yang valid sebelum promo disetujui admin.'
        },
        tentang: {
            title: 'Tentang Kami',
            body: 'OperIn dibuat sebagai marketplace preloved khusus mahasiswa UNS agar barang kampus yang masih layak pakai bisa dijual dan ditemukan lebih mudah.'
        },
        privasi: {
            title: 'Kebijakan Privasi',
            body: 'Data akun seperti nama, email, WhatsApp, dan fakultas digunakan untuk kebutuhan identitas penjual, komunikasi pembeli, dan pengelolaan akun oleh admin.'
        },
        syarat: {
            title: 'Syarat & Ketentuan',
            body: 'Pengguna bertanggung jawab atas kebenaran data barang, kondisi produk, dan bukti pembayaran. Admin dapat menolak promo atau membatasi akun yang melanggar aturan.'
        }
    };

    const footerModal = document.getElementById('footerInfoModal');
    const footerTitle = document.getElementById('footerInfoTitle');
    const footerBody = document.getElementById('footerInfoBody');
    const footerClose = document.getElementById('footerInfoClose');

    function closeFooterInfo() {
        footerModal.classList.add('hidden');
        footerModal.classList.remove('flex');
    }

    document.querySelectorAll('[data-footer-info]').forEach((button) => {
        button.addEventListener('click', () => {
            const info = footerInfo[button.dataset.footerInfo];
            if (!info) return;
            footerTitle.textContent = info.title;
            footerBody.textContent = info.body;
            footerModal.classList.remove('hidden');
            footerModal.classList.add('flex');
        });
    });

    footerClose.addEventListener('click', closeFooterInfo);
    footerModal.addEventListener('click', (event) => {
        if (event.target === footerModal) closeFooterInfo();
    });
</script>
<!-- END OF FOOTER -->
