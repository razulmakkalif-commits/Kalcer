<?php
require_once '../config/session.php';

if (isLoggedIn()) { redirectByRole(); }

$error = $_GET['error'] ?? '';
$old   = [
    'nama'  => $_GET['nama']  ?? '',
    'email' => $_GET['email'] ?? '',
    'no_hp' => $_GET['no_hp'] ?? '',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — LapanganKu</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green-dark:  #1a3a2a; --green-mid: #2d6a4f; --green-field: #40916c;
      --green-light: #74c69d; --accent: #f9c74f; --white: #ffffff;
      --gray-100: #f4f6f4; --gray-300: #c8d5cc; --gray-500: #6b7f74;
      --gray-700: #374940; --red: #e63946; --font: 'Plus Jakarta Sans', sans-serif; --radius: 12px;
    }
    body {
      font-family: var(--font); min-height: 100vh;
      display: grid; grid-template-columns: 1fr 1fr; background: var(--green-dark);
    }
    .panel-left {
      position: relative; display: flex; flex-direction: column;
      justify-content: flex-end; padding: 48px; overflow: hidden;
    }
    .field-art { position: absolute; inset: 0; opacity: 0.18; }
    .field-art svg { width: 100%; height: 100%; }
    .brand {
      position: absolute; top: 48px; left: 48px; z-index: 1;
      display: flex; align-items: center; gap: 10px;
    }
    .brand-icon { width: 40px; height: 40px; background: var(--accent); border-radius: 8px; display: grid; place-items: center; font-size: 20px; }
    .brand-name { font-size: 1.2rem; font-weight: 700; color: var(--white); }
    .panel-left-content { position: relative; z-index: 1; }
    .tagline { font-size: 2.2rem; font-weight: 700; color: var(--white); line-height: 1.2; letter-spacing: -0.5px; margin-bottom: 16px; }
    .tagline span { color: var(--accent); }
    .tagline-sub { font-size: 0.95rem; color: var(--green-light); line-height: 1.6; max-width: 300px; }
    .steps { margin-top: 32px; display: flex; flex-direction: column; gap: 14px; }
    .step { display: flex; align-items: flex-start; gap: 12px; }
    .step-num {
      width: 28px; height: 28px; border-radius: 50%; background: rgba(249,199,79,0.2);
      border: 1.5px solid var(--accent); color: var(--accent);
      font-size: 0.8rem; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; margin-top: 2px;
    }
    .step-text { font-size: 0.88rem; color: var(--green-light); line-height: 1.5; }
    .panel-right {
      background: var(--white); display: flex; align-items: center;
      justify-content: center; padding: 48px 40px;
    }
    .form-box { width: 100%; max-width: 420px; }
    .form-title { font-size: 1.6rem; font-weight: 700; color: var(--green-dark); margin-bottom: 6px; letter-spacing: -0.3px; }
    .form-subtitle { font-size: 0.9rem; color: var(--gray-500); margin-bottom: 28px; }
    .alert { padding: 12px 16px; border-radius: var(--radius); font-size: 0.88rem; margin-bottom: 20px; font-weight: 500; }
    .alert-error { background: #fdecea; color: var(--red); border: 1px solid #f5c2c2; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--gray-700); margin-bottom: 7px; }
    .input-wrap { position: relative; }
    .input-wrap .icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray-300); font-size: 1rem; pointer-events: none; }
    .form-group input {
      width: 100%; padding: 12px 14px 12px 40px;
      border: 1.5px solid var(--gray-300); border-radius: var(--radius);
      font-family: var(--font); font-size: 0.95rem; color: var(--gray-700);
      background: var(--gray-100); transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .form-group input:focus { border-color: var(--green-field); box-shadow: 0 0 0 3px rgba(64,145,108,0.15); background: var(--white); }
    .form-group input.error { border-color: var(--red); }
    .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--gray-500); cursor: pointer; font-size: 1rem; padding: 0; }
    .pw-hint { font-size: 0.78rem; color: var(--gray-500); margin-top: 5px; }
    .btn-submit {
      width: 100%; padding: 13px; background: var(--green-field); color: var(--white);
      font-family: var(--font); font-size: 0.95rem; font-weight: 600;
      border: none; border-radius: var(--radius); cursor: pointer;
      transition: background 0.2s, transform 0.1s; margin-top: 4px;
    }
    .btn-submit:hover { background: var(--green-mid); }
    .btn-submit:active { transform: scale(0.98); }
    .link-login { text-align: center; font-size: 0.88rem; color: var(--gray-500); margin-top: 20px; }
    .link-login a { color: var(--green-field); font-weight: 600; text-decoration: none; }
    .link-login a:hover { text-decoration: underline; }
    @media (max-width: 768px) {
      body { grid-template-columns: 1fr; }
      .panel-left { display: none; }
      .panel-right { padding: 40px 24px; }
      .form-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div class="panel-left">
    <div class="brand">
      <div class="brand-icon">&#9917;</div>
      <span class="brand-name">LapanganKu</span>
    </div>
    <div class="field-art">
      <svg viewBox="0 0 500 700" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="40" y="60" width="420" height="580" stroke="#74c69d" stroke-width="3"/>
        <line x1="40" y1="350" x2="460" y2="350" stroke="#74c69d" stroke-width="2"/>
        <circle cx="250" cy="350" r="70" stroke="#74c69d" stroke-width="2"/>
        <circle cx="250" cy="350" r="4" fill="#74c69d"/>
        <rect x="40" y="240" width="110" height="220" stroke="#74c69d" stroke-width="2"/>
        <rect x="350" y="240" width="110" height="220" stroke="#74c69d" stroke-width="2"/>
        <circle cx="140" cy="350" r="4" fill="#74c69d"/>
        <circle cx="360" cy="350" r="4" fill="#74c69d"/>
      </svg>
    </div>
    <div class="panel-left-content">
      <p class="tagline">Gabung dan<br><span>mulai main</span><br>hari ini.</p>
      <p class="tagline-sub">Daftar gratis, langsung bisa booking lapangan favoritmu.</p>
      <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-text">Buat akun dengan email aktif</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-text">Pilih lapangan dan jadwal yang tersedia</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-text">Konfirmasi booking dan langsung main</div></div>
      </div>
    </div>
  </div>

  <div class="panel-right">
    <div class="form-box">
      <h1 class="form-title">Buat akun baru &#127942;</h1>
      <p class="form-subtitle">Isi data di bawah untuk mendaftar sebagai pelanggan</p>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <?php
            $pesan = match($error) {
              'empty'    => 'Semua kolom wajib diisi.',
              'email'    => 'Format email tidak valid.',
              'exists'   => 'Email sudah terdaftar. Gunakan email lain atau langsung masuk.',
              'password' => 'Password minimal 6 karakter.',
              'confirm'  => 'Konfirmasi password tidak cocok.',
              'phone'    => 'Nomor HP hanya boleh berisi angka (10-15 digit).',
              default    => 'Terjadi kesalahan. Silakan coba lagi.'
            };
            echo htmlspecialchars($pesan);
          ?>
        </div>
      <?php endif; ?>

      <form action="register_process.php" method="POST">
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <div class="input-wrap">
            <span class="icon">&#128100;</span>
            <input type="text" id="nama" name="nama" placeholder="Nama lengkap kamu"
              value="<?php echo htmlspecialchars($old['nama']); ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrap">
            <span class="icon">&#9993;</span>
            <input type="email" id="email" name="email" placeholder="contoh@email.com"
              value="<?php echo htmlspecialchars($old['email']); ?>"
              class="<?php echo ($error === 'exists' || $error === 'email') ? 'error' : ''; ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label for="no_hp">Nomor HP</label>
          <div class="input-wrap">
            <span class="icon">&#128222;</span>
            <input type="tel" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx"
              value="<?php echo htmlspecialchars($old['no_hp']); ?>"
              class="<?php echo $error === 'phone' ? 'error' : ''; ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
              <span class="icon">&#128274;</span>
              <input type="password" id="password" name="password" placeholder="Min. 6 karakter"
                class="<?php echo ($error === 'password' || $error === 'confirm') ? 'error' : ''; ?>" required>
              <button type="button" class="toggle-pw" onclick="togglePw('password')" title="Tampilkan">&#128065;</button>
            </div>
          </div>
          <div class="form-group">
            <label for="confirm_password">Konfirmasi</label>
            <div class="input-wrap">
              <span class="icon">&#128274;</span>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password"
                class="<?php echo $error === 'confirm' ? 'error' : ''; ?>" required>
              <button type="button" class="toggle-pw" onclick="togglePw('confirm_password')" title="Tampilkan">&#128065;</button>
            </div>
          </div>
        </div>
        <p class="pw-hint">Password minimal 6 karakter. Gunakan kombinasi huruf dan angka.</p>

        <button type="submit" class="btn-submit" style="margin-top:16px">Buat Akun</button>
      </form>

      <p class="link-login">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </div>
  </div>

  <script>
    function togglePw(id) {
      const i = document.getElementById(id);
     i.type = i.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>