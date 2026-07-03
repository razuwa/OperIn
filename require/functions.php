<?php

function is_ajax_request($server = null, $request = null) {
    $server = $server ?? $_SERVER;
    $request = $request ?? $_REQUEST;

    $requested_with = strtolower($server['HTTP_X_REQUESTED_WITH'] ?? '');
    return $requested_with === 'xmlhttprequest' || (($request['ajax'] ?? '') === '1');
}

function json_response($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit();
}

function require_admin_session() {
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
        if (is_ajax_request()) {
            json_response(false, 'Akses ditolak. Silakan login sebagai admin.');
        }

        header("Location: produk.php");
        exit();
    }
}

function redirect_with_message($url, $message, $param = 'msg') {
    header("Location: {$url}?{$param}=" . urlencode($message));
    exit();
}

function format_rupiah($amount) {
    return 'Rp' . number_format((float)$amount, 0, ',', '.');
}

function add_ajax_flag($url) {
    $separator = strpos($url, '?') === false ? '?' : '&';
    return $url . $separator . 'ajax=1';
}
