<?php
session_start();
session_unset();
session_destroy();

// hapus cookie
if (isset($_COOKIE['operin_last_active'])) {
    setcookie('operin_last_active', '', time() - 3600, '/');
}

header("Location: index.php?success=" . urlencode("Anda telah berhasil keluar dari sistem."));
    exit();
?>