<?php
// ============================================================
// config.php — Konstanta Global Aplikasi
// ============================================================

// Nama & URL Aplikasi
define('APP_NAME', 'LapanganKu');
define('BASE_URL',  'http://localhost/lapanganku'); // Ganti sesuai folder proyekmu di htdocs

// Database
define('DB_HOST',    'localhost');
define('DB_NAME',    'lapanganku');
define('DB_USER',    'root');       // Ganti jika pakai user MySQL lain
define('DB_PASS',    '');           // Ganti jika MySQL-mu punya password
define('DB_CHARSET', 'utf8mb4');

// Timezone
date_default_timezone_set('Asia/Makassar'); // WITA

// Mode Debug (set FALSE saat produksi)
define('DEBUG_MODE', true);