<?php
// ============================================================
// login_process.php — Verifikasi kredensial + set session
// ============================================================
require_once '../config/session.php';
require_once '../config/database.php';

// Tolak akses langsung via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// Validasi: field tidak boleh kosong
if ($email === '' || $password === '') {
    header('Location: login.php?error=empty');
    exit;
}

// Cari user berdasarkan email
$pdo  = getDB();
$stmt = $pdo->prepare("SELECT id_user, nama, email, password, role FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verifikasi password bcrypt
if (!$user || !password_verify($password, $user['password'])) {
    // Redirect balik ke login dengan email tetap terisi
    header('Location: login.php?error=invalid&email=' . urlencode($email));
    exit;
}

// Login berhasil — simpan session
setUserSession($user);

// Redirect ke dashboard sesuai role
redirectByRole();