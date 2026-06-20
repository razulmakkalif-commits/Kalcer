<?php
// 1. Ambil konfigurasi global untuk BASE_URL dan Session
// Menggunakan require_once dengan path relatif dari posisi file ini (includes/)
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Deteksi status login dan role user
$is_logged_in = isset($_SESSION['id_user']);
$role         = $is_logged_in ? $_SESSION['role'] : null;
$nama_user    = $is_logged_in ? $_SESSION['nama'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= defined('APP_NAME') ? APP_NAME : 'LapanganKu'; ?> - Sistem Booking</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/dashboard.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-success" href="<?= BASE_URL; ?>/index.php">
            <img src="<?= BASE_URL; ?>/assets/img/logo.png" alt="Logo" width="30" class="d-inline-block align-text-top me-2">
            LAPANGANKU
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL; ?>/index.php"><i class="fa-solid fa-house me-1"></i> Beranda</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-circle text-success me-1"></i> 
                            <?= htmlspecialchars($nama_user); ?> 
                            <span class="badge bg-secondary ms-1 text-uppercase" style="font-size: 10px;"><?= $role; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL; ?>/auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Keluar</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light me-2" href="<?= BASE_URL; ?>/auth/login.php">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-success" href="<?= BASE_URL; ?>/auth/register.php">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <?php 
        // Jika user sudah masuk, panggil file sidebar spesifik dari folder includes/
        if ($is_logged_in) {
            if ($role === 'admin') {
                include __DIR__ . '/sidebar_admin.php';
            } else if ($role === 'pelanggan') {
                include __DIR__ . '/sidebar_pelanggan.php';
            }
        } 
        ?>
        
        <main class="<?= $is_logged_in ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4' : 'col-md-12 px-md-4 py-4'; ?>"></main>