<?php
require_once '../config/session.php';

if (isLoggedIn()) { redirectByRole(); }

$error   = $_GET['error']   ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — LapanganKu</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green-dark:  #1a3a2a;
      --green-mid:   #2d6a4f;
      --green-field: #40916c;
      --green-light: #74c69d;
      --accent:      #f9c74f;
      --white:       #ffffff;
      --gray-100:    #f4f6f4;
      --gray-300:    #c8d5cc;
      --gray-500:    #6b7f74;
      --gray-700:    #374940;
      --red:         #e63946;
      --font:        'Plus Jakarta Sans', sans-serif;
      --radius:      12px;
    }
    body {
      font-family: var(--font);
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
      background: var(--green-dark);
    }
    .panel-left {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 48px;
      overflow: hidden;
    }
    .field-art { position: absolute; inset: 0; opacity: 0.18; }
    .field-art svg { width: 100%; height: 100%; }
    .brand {
      position: absolute; top: 48px; left: 48px; z-index: 1;
      display: flex; align-items: center; gap: 10px;
    }
    .brand-icon {
      width: 40px; height: 40px; background: var(--accent);
      border-radius: 8px; display: grid; place-items: center; font-size: 20px;
    }
    .brand-name { font-size: 1.2rem; font-weight: 700; color: var(--white); }
    .panel-left-content { position: relative; z-index: 1; }
    .tagline { font-size: 2.4rem; font-weight: 700; color: var(--white); line-height: 1.2; letter-spacing: -0.5px; margin-bottom: 16px; }
    .tagline span { color: var(--accent); }
    .tagline-sub { font-size: 0.95rem; color: var(--green-light); line-height: 1.6; max-width: 320px; }
    .panel-right {
      background: var(--white);
      display: flex; align-items: center; justify-content: center;
      padding: 48px 40px;
    }
    .form-box { width: 100%; max-width: 400px; }
    .form-title { font-size: 1.6rem; font-weight: 700; color: var(--green-dark); margin-bottom: 6px; letter-spacing: -0.3px; }
    .form-subtitle { font-size: 0.9rem; color: var(--gray-500); margin-bottom: 32px; }
    .alert { padding: 12px 16px; border-radius: var(--radius); font-size: 0.88rem; margin-bottom: 20px; font-weight: 500; }
    .alert-error   { background: #fdecea; color: var(--red); border: 1px solid #f5c2c2; }
    .alert-success { background: #e8f5ee; color: var(--green-field); border: 1px solid #b2dfc7; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--gray-700); margin-bottom: 7px; }
    .input-wrap { position: relative; }
    .input-wrap .icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: var(--gray-300); font-size: 1rem; pointer-events: none;
    }
    .form-group input {
      width: 100%; padding: 12px 14px 12px 40px;
      border: 1.5px solid var(--gray-300); border-radius: var(--radius);
      font-family: var(--font); font-size: 0.95rem; color: var(--gray-700);
      background: var(--gray-100); transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .form-group input:focus {
      border-color: var(--green-field);
      box-shadow: 0 0 0 3px rgba(64,145,108,0.15);
      background: var(--white);
    }
    .toggle-pw {
      position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
      background: none; border: none; color: var(--gray-500); cursor: pointer; font-size: 1rem; padding: 0;
    }
    .btn-submit {
      width: 100%; padding: 13px; background: var(--green-field); color: var(--white);
      font-family: var(--font); font-size: 0.95rem; font-weight: 600;
      border: none; border-radius: var(--radius); cursor: pointer;
      transition: background 0.2s, transform 0.1s; margin-top: 8px;
    }
    .btn-submit:hover  { background: var(--green-mid); }
    .btn-submit:active { transform: scale(0.98); }
    .divider {
      text-align: center; font-size: 0.82rem; color: var(--gray-300);
      margin: 24px 0; position: relative;
    }
    .divider::before, .divider::after {
      content: ''; position: absolute; top: 50%; width: 42%; height: 1px; background: var(--gray-300);
    }
    .divider::before { left: 0; } .divider::after { right: 0; }
    .link-register { text-align: center; font-size: 0.88rem; color: var(--gray-500); }
    .link-register a { color: var(--green-field); font-weight: 600; text-decoration: none; }
    .link-register a:hover { text-decoration: underline; }
    .demo-box {
      margin-top: 28px; padding: 14px 16px; background: var(--gray-100);
      border-radius: var(--radius); border-left: 3px solid var(--accent);
    }
    .demo-box p { font-size: 0.78rem; color: var(--gray-500); margin-bottom: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .demo-box table { width: 100%; border-collapse: collapse; }
    .demo-box td { font-size: 0.82rem; color: var(--gray-700); padding: 3px 0; }
    .demo-box td:first-child { color: var(--gray-500); width: 80px; }
    @media (max-width: 768px) {
      body { grid-template-columns: 1fr; }
      .panel-left { display: none; }
      .panel-right { padding: 40px 24px; }
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
        <rect x="40" y="295" width="50" height="110" stroke="#74c69d" stroke-width="2"/>
        <rect x="350" y="240" width="110" height="220" stroke="#74c69d" stroke-width="2"/>
        <rect x="410" y="295" width="50" height="110" stroke="#74c69d" stroke-width="2"/>
        <circle cx="140" cy="350" r="4" fill="#74c69d"/>
        <circle cx="360" cy="350" r="4" fill="#74c69d"/>
      </svg>
    </div>
    <div class="panel-left-content">
      <p class="tagline">Booking lapangan<br><span>lebih mudah,</span><br>main lebih sering.</p>
      <p class="tagline-sub">Cek jadwal, pilih slot, konfirmasi — selesai dalam hitungan menit.</p>
    </div>
  </div>

  <div class="panel-right">
    <div class="form-box">
      <h1 class="form-title">Selamat datang &#128075;</h1>
      <p class="form-subtitle">Masuk untuk melanjutkan ke LapanganKu</p>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <?php
            $pesan = match($error) {
              'invalid'  => 'Email atau password salah. Periksa kembali.',
              'empty'    => 'Email dan password wajib diisi.',
              default    => 'Terjadi kesalahan. Silakan coba lagi.'
            };
            echo htmlspecialchars($pesan);
          ?>
        </div>
      <?php endif; ?>

      <?php if ($success === 'register'): ?>
        <div class="alert alert-success">Registrasi berhasil! Silakan masuk dengan akun barumu.</div>
      <?php endif; ?>

      <form action="login_process.php" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrap">
            <span class="icon">&#9993;</span>
            <input type="email" id="email" name="email" placeholder="contoh@email.com"
              value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>"
              autocomplete="email" required>
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="icon">&#128274;</span>
            <input type="password" id="password" name="password" placeholder="Masukkan password"
              autocomplete="current-password" required>
            <button type="button" class="toggle-pw" onclick="togglePw()" title="Tampilkan password">&#128065;</button>
          </div>
        </div>
        <button type="submit" class="btn-submit">Masuk</button>
      </form>

      <div class="divider">atau</div>
      <p class="link-register">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>

      <div class="demo-box">
        <p>&#128273; Akun Testing</p>
        <table>
          <tr><td>Admin</td><td>admin@lapanganku.com / admin123</td></tr>
          <tr><td>Pelanggan</td><td>razul@mail.com / razul123</td></tr>
        </table>
      </div>
    </div>
  </div>

  <script>
    function togglePw() {
      const i = document.getElementById('password');
      i.type = i.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>