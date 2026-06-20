<?php
// ============================================================
// session.php — Manajemen Session & Helper Autentikasi
// ============================================================

require_once __DIR__ . '/config.php';

// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ──────────────────────────────────────────
// CEK STATUS LOGIN
// ──────────────────────────────────────────

/** Cek apakah user sudah login */
function isLoggedIn(): bool {
    return isset($_SESSION['id_user'], $_SESSION['role']);
}

/** Cek apakah user yang login adalah admin */
function isAdmin(): bool {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

/** Cek apakah user yang login adalah pelanggan */
function isPelanggan(): bool {
    return isLoggedIn() && $_SESSION['role'] === 'pelanggan';
}

// ──────────────────────────────────────────
// PAKSA LOGIN / PAKSA ROLE TERTENTU
// ──────────────────────────────────────────

/**
 * Panggil di awal setiap halaman yang butuh login.
 * Jika belum login, redirect ke halaman login.
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }
}

/**
 * Panggil di awal halaman khusus admin.
 * Jika bukan admin, redirect ke dashboard pelanggan.
 */
function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/pelanggan/dashboard.php');
        exit;
    }
}

/**
 * Panggil di awal halaman khusus pelanggan.
 * Jika bukan pelanggan, redirect ke dashboard admin.
 */
function requirePelanggan(): void {
    requireLogin();
    if (!isPelanggan()) {
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
        exit;
    }
}

// ──────────────────────────────────────────
// SIMPAN & HAPUS SESSION SETELAH LOGIN/LOGOUT
// ──────────────────────────────────────────

/**
 * Simpan data user ke session setelah login berhasil.
 * Panggil dari auth/login_process.php
 *
 * @param array $user — baris data user dari tabel users
 */
function setUserSession(array $user): void {
    // Regenerate ID session untuk cegah session fixation attack
    session_regenerate_id(true);

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['role'];
}

/**
 * Hapus semua session dan redirect ke login.
 * Panggil dari auth/logout.php
 */
function destroySession(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}

// ──────────────────────────────────────────
// HELPER REDIRECT BERDASARKAN ROLE
// ──────────────────────────────────────────

/**
 * Setelah login berhasil, arahkan user ke dashboard sesuai role-nya.
 */
function redirectByRole(): void {
    if (isAdmin()) {
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
    } else {
        header('Location: ' . BASE_URL . '/pelanggan/dashboard.php');
    }
    exit;
}