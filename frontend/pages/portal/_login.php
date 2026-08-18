<?php
require_once __DIR__ . '/../../../backend/config/database.php';
require_once __DIR__ . '/../../../backend/helpers/functions.php';
require_once __DIR__ . '/../../../backend/auth.php';

portal_require_guest();

$portalRoles = [
    'admin' => ['label' => 'Admin', 'description' => 'Kelola seluruh portal, pengguna, konten, dan data SPMB.'],
    'humas' => ['label' => 'Humas', 'description' => 'Publikasikan berita dan dokumentasi kegiatan sekolah.'],
    'kasir' => ['label' => 'Kasir SPMB', 'description' => 'Kelola dan pantau pembayaran pendaftaran siswa baru.'],
];
$roleInfo = $portalRoles[$loginRole];
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    portal_verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (portal_attempt_login($pdo, $email, $password, $loginRole)) {
        header('Location: ' . portal_home_for_role($loginRole));
        exit;
    }
    $error = 'Email, kata sandi, atau portal role tidak sesuai.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login <?php echo esc($roleInfo['label']); ?> | Portal TBZ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=El+Messiri:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/frontend/assets/css/portal.css">
</head>
<body class="login-page">
<main class="login-shell">
    <section class="login-visual">
        <div class="login-visual-overlay"></div>
        <div class="login-visual-content">
            <div class="portal-brand">
                <span class="portal-brand-logo"><img src="<?php echo SITE_URL; ?>/frontend/assets/images/logo.png" alt="Logo TBZ"></span>
                <span>PortalTBZ</span>
            </div>
            <span class="version-pill">Internal</span>
            <div class="visual-copy">
                <span class="shield-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M12 3 5 6v5c0 4.6 2.9 8.3 7 10 4.1-1.7 7-5.4 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                </span>
                <p class="visual-kicker">Portal Internal Sekolah</p>
                <h1>Kelola Sekolah<br><span>Lebih Terpadu</span></h1>
                <p>Konten informasi dan administrasi SPMB dalam satu sistem yang aman, ringkas, dan terintegrasi.</p>
                <div class="visual-role-card">
                    <span><?php echo esc($roleInfo['label']); ?></span>
                    <small><?php echo esc($roleInfo['description']); ?></small>
                </div>
            </div>
            <small class="portal-copyright">&copy; 2026 LPIT Thariq Bin Ziyad. Portal administrasi internal.</small>
        </div>
    </section>

    <section class="login-form-side">
        <button class="theme-switch" type="button" id="themeSwitch" aria-label="Ganti mode warna">&#9789;&nbsp; Mode Gelap</button>
        <div class="login-box">
            <span class="mobile-brand">PortalTBZ</span>
            <span class="role-badge"><?php echo esc($roleInfo['label']); ?></span>
            <h2>Hai, selamat datang! <span aria-hidden="true">&#128075;</span></h2>
            <p class="login-subtitle">Masuk menggunakan akun <?php echo esc(strtolower($roleInfo['label'])); ?> Anda.</p>

            <?php if ($error): ?>
                <div class="portal-alert danger"><?php echo esc($error); ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="on">
                <input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>">
                <div class="portal-field">
                    <label for="email">EMAIL</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg>
                        <input id="email" name="email" type="email" value="<?php echo esc($email); ?>" placeholder="nama@tbz.sch.id" autocomplete="username" required autofocus>
                    </div>
                </div>
                <div class="portal-field">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                        <button class="password-toggle" type="button" id="passwordToggle" aria-label="Lihat kata sandi">
                            <svg viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        </button>
                    </div>
                </div>
                <label class="remember"><input type="checkbox" name="remember"> <span>Ingat perangkat ini</span></label>
                <button class="login-submit" type="submit">Masuk <span>&rarr;</span></button>
            </form>
            <p class="login-help">Kesulitan masuk? Hubungi Administrator sekolah.</p>
        </div>
    </section>
  </main>
  <script>
    const root = document.documentElement;
    const themeSwitch = document.getElementById('themeSwitch');
    const savedTheme = localStorage.getItem('tbz-theme');
    if (savedTheme === 'dark') root.dataset.theme = 'dark';
    function syncThemeLabel() {
      themeSwitch.innerHTML = root.dataset.theme === 'dark' ? '&#9728;&nbsp; Mode Terang' : '&#9789;&nbsp; Mode Gelap';
    }
    syncThemeLabel();
    themeSwitch.addEventListener('click', function () {
      root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
      localStorage.setItem('tbz-theme', root.dataset.theme);
      syncThemeLabel();
    });
    document.getElementById('passwordToggle').addEventListener('click', function () {
      const input = document.getElementById('password');
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  </script>
</body>
</html>

