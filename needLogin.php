<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php?error=" . urlencode("Silakan login terlebih dahulu."));
    exit();
}

if (!isset($_COOKIE['operin_last_active'])) {
    
    session_unset();
    session_destroy();
    
    //redirect
    header("Location: login.php?error=" . urlencode("Sesi Anda telah berakhir."));
    exit();
}

// tambahkan 5 menit jika user masih aktif
setcookie('operin_last_active', 'true', time() + 300, '/');
?>