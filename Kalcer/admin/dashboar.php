<?php
// 1. Inisialisasi Session & Proteksi Halaman
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php'; // Memuat fungsi getDB()
require_once __DIR__ . '/../config/config.php';   // Memuat BASE_URL

// Cek apakah user sudah login dan memiliki role admin
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
}

// 2. QUERY DATABASE (Menggunakan fungsi getDB())
try {
    // Panggil fungsi getDB() untuk mendapatkan objek koneksi PDO
    $db = getDB();

    // A. Hitung Total Pemesanan (Semua Status)
    $stmt_total = $db->query("SELECT COUNT(*) FROM `pemesanan`");
    $total_booking = $stmt_total->fetchColumn();

    // B. Hitung Total Pemesanan dengan Status 'pending'
    $stmt_pending = $db->query("SELECT COUNT(*) FROM `pemesanan` WHERE `status` = 'pending'");
    $total_pending = $stmt_pending->fetchColumn();

    // C. Hitung Jumlah Lapangan yang Aktif
    $stmt_lapangan = $db->query("SELECT COUNT(*) FROM `lapangan` WHERE `status` = 'aktif'");
    $total_lapangan = $stmt_lapangan->fetchColumn();

    // D. Hitung Total Pendapatan dari Pemesanan 'dikonfirmasi' atau 'selesai'
    $stmt_pendapatan = $db->query("SELECT SUM(`total_harga`) FROM `pemesanan` WHERE `status` IN ('dikonfirmasi', 'selesai')");
    $total_pendapatan = $stmt_pendapatan->fetchColumn() ?? 0;

    // E. Ambil 5 Data Pemesanan Terbaru untuk Tabel
    $query_terbaru = "SELECT p.*, u.nama, u.no_hp, l.nama_lapangan, l.jenis_lantai, j.jam_mulai, j.jam_selesai, j.tanggal
                      FROM `pemesanan` p
                      JOIN `users` u ON p.id_user = u.id_user
                      JOIN `lapangan` l ON p.id_lapangan = l.id_lapangan
                      JOIN `jadwal_slot` j ON p.id_slot = j.id_slot
                      ORDER BY p.created_at DESC 
                      LIMIT 5";
    $stmt_terbaru = $db->query($query_terbaru);
    $pemesanan_terbaru = $stmt_terbaru->fetchAll();

} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die("Gagal mengambil data dashboard: " . $e->getMessage());
    } else {
        die("Terjadi kesalahan pada sistem.");
    }
}

// 3. Panggil Komponen Navigasi & Header Atas
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--green-dark);">Dashboard Admin</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>.</p>
    </div>
    <div class="text-muted small fw-medium">
        <i class="fa-solid fa-calendar-day text-success me-1"></i> <?= date('d M Y'); ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success" style="background-color: rgba(40, 167, 69, 0.1); width: 48px; height: 48px; display: grid; place-items: center; border-radius: 10px;">
                    <i class="fa-solid fa-receipt fa-lg"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-medium">Total Booking</p>
                    <h4 class="fw-bold mb-0"><?= $total_booking; ?></h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon text-warning" style="background-color: rgba(255, 193, 7, 0.1); width: 48px; height: 48px; display: grid; place-items: center; border-radius: 10px;">
                    <i class="fa-solid fa-hourglass-half fa-lg"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-medium">Pending</p>
                    <h4 class="fw-bold mb-0"><?= $total_pending; ?></h4>
                </div>
            </div>
        </div>
    </div>
    
    <div