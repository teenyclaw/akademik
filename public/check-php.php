<?php

/**
 * Cek requirement PHP hosting — HAPUS file ini setelah deploy selesai.
 */
header('Content-Type: text/plain; charset=utf-8');

$checks = [
    'PHP version >= 8.3' => version_compare(PHP_VERSION, '8.3.0', '>='),
    'proc_open' => function_exists('proc_open'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'mbstring' => extension_loaded('mbstring'),
    'openssl' => extension_loaded('openssl'),
    'tokenizer' => extension_loaded('tokenizer'),
    'xml' => extension_loaded('xml'),
    'ctype' => extension_loaded('ctype'),
    'json' => extension_loaded('json'),
    'bcmath' => extension_loaded('bcmath'),
    'fileinfo' => extension_loaded('fileinfo'),
    'gd' => extension_loaded('gd'),
];

echo "PHP " . PHP_VERSION . " (" . PHP_SAPI . ")\n";
echo "Document root check — this must be >= 8.3 when opened in BROWSER\n\n";

foreach ($checks as $label => $ok) {
    echo ($ok ? '[OK] ' : '[FAIL] ') . $label . "\n";
}

echo "\n";
if (! version_compare(PHP_VERSION, '8.3.0', '>=')) {
    echo "FIX: cPanel → MultiPHP Manager → set domain to PHP 8.3 or 8.4\n";
    echo "     CLI php -v may differ from web PHP (this file shows WEB PHP).\n";
}
if (! function_exists('proc_open')) {
    echo "NOTE: proc_open disabled — gunakan Metode A di docs/DEPLOY-HOSTING.md\n";
    echo "      (upload vendor/ dari Laragon, jangan composer di server)\n";
}
