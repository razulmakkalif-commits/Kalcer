<?php
// ============================================================
// database.php — Koneksi PDO ke MySQL (database: lapanganku)
// ============================================================

require_once __DIR__ . '/config.php';

function getDB(): PDO {
    static $pdo = null; // Singleton — koneksi hanya dibuat sekali

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname='    . DB_NAME
             . ';charset='   . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Lempar exception jika error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Hasil query berupa array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                     // Gunakan prepared statement asli
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                // Mode development: tampilkan detail error
                die('<pre style="color:red;padding:20px">
[DATABASE ERROR]
Pesan  : ' . $e->getMessage() . '
File   : ' . $e->getFile() . '
Baris  : ' . $e->getLine() . '

Cek kembali:
  - Apakah XAMPP/Laragon sudah aktif?
  - Apakah database "lapanganku" sudah dibuat?
  - Apakah username/password MySQL benar di config.php?
</pre>');
            } else {
                // Mode produksi: jangan tampilkan detail error ke user
                die('Koneksi database gagal. Silakan coba beberapa saat lagi.');
            }
        }
    }

    return $pdo;
}