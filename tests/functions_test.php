<?php
require __DIR__ . '/../require/functions.php';

function assert_same($expected, $actual, $message) {
    if ($expected !== $actual) {
        echo "FAIL: $message\n";
        echo "Expected: " . var_export($expected, true) . "\n";
        echo "Actual: " . var_export($actual, true) . "\n";
        exit(1);
    }
}

assert_same(true, is_ajax_request(['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']), 'detects XMLHttpRequest ajax header');
assert_same(true, is_ajax_request([], ['ajax' => '1']), 'detects ajax query flag');
assert_same(false, is_ajax_request([], []), 'returns false for normal page request');

assert_same('http://localhost/proses.php?action=role&id=7&ajax=1', add_ajax_flag('http://localhost/proses.php?action=role&id=7'), 'adds ajax flag to existing query');
assert_same('proses.php?ajax=1', add_ajax_flag('proses.php'), 'adds ajax flag to url without query');

assert_same('Rp12.500', format_rupiah(12500), 'formats rupiah consistently');

echo "OK\n";
