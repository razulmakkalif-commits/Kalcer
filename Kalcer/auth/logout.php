<?php
// ============================================================
// logout.php — Hapus session & redirect ke login
// ============================================================
require_once '../config/session.php';

// Hanya proses jika user memang sedang login
if (isLoggedIn()) {
    destroySession(); // fungsi ini sudah handle destroy + redirect
}

// Fallback jika belum login
header('Location: login.php');
exit;