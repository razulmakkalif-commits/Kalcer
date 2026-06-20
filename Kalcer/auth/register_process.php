<?php
// ============================================================
// register_process.php — Validasi & simpan akun pelanggan baru
// ============================================================
require_once '../config/session.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Ambil & bersihkan input
$nama             = trim($_POST['nama']             ?? '');
$email            = trim($_POST['email']            ?? '');
$no_hp            = trim($_POST['no_hp']            ?? '');
$password         = trim($_POST['password']         ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Helper redirect balik dengan data lama
function backWithError(string $error, array $old): void {
    $q = http_build_query([
        'error' => $error,
        'nama'  => $old['nama'],
        'email' => $old['email'],
        'no_hp' => $old['no_hp'],
    ]);
    header("Location: register.php?$q");
    exit;
}

$old = compact('nama', 'email', 'no_hp');

// Validasi: semua field wajib diisi
if ($nama === '' || $email === '' || $no_hp === '' || $password === '' || $confirm_password === '') {
    backWithError('empty', $old);
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    backWithError('email', $old);
}

// Validasi nomor HP: hanya angka, 10–15 digit
if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
    backWithError('phone', $old);
}

// Validasi panjang password
if (strlen($password) < 6) {
    backWithError('password', $old);
}

// Validasi konfirmasi password
if ($password !== $confirm_password) {
    backWithError('confirm', $old);
}

$pdo = getDB();

// Cek apakah email sudah terdaftar
$cek = $pdo->prepare("SELECT id_user FROM users WHERE email = ? LIMIT 1");
$cek->execute([$email]);
if ($cek->fetch()) {
    backWithError('exists', $old);
}

// Hash password dengan bcrypt
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Simpan user baru (role default: pelanggan)
$insert = $pdo->prepare(
    "INSERT INTO users (nama, email, password, no_hp, role) VALUES (?, ?, ?, ?, 'pelanggan')"
);
$insert->execute([$nama, $email, $hashed, $no_hp]);

// Redirect ke login dengan pesan sukses
header('Location: login.php?success=register');
exit;