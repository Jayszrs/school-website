<?php
/**
 * Autentikasi dan otorisasi portal internal.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('tbz_portal_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/school-website',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

function portal_bootstrap_database(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS portal_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','humas','kasir') NOT NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        last_login_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS portal_activity_logs (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(100) NOT NULL,
        description VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_activity_user (user_id),
        CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES portal_users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $columns = [
        'payment_status' => "ALTER TABLE spmb_registrations ADD COLUMN payment_status ENUM('belum_bayar','sebagian','lunas') NOT NULL DEFAULT 'belum_bayar' AFTER previous_school",
        'payment_amount' => "ALTER TABLE spmb_registrations ADD COLUMN payment_amount DECIMAL(14,2) NOT NULL DEFAULT 0 AFTER payment_status",
        'payment_method' => "ALTER TABLE spmb_registrations ADD COLUMN payment_method VARCHAR(50) NULL AFTER payment_amount",
        'payment_date' => "ALTER TABLE spmb_registrations ADD COLUMN payment_date DATE NULL AFTER payment_method",
        'payment_notes' => "ALTER TABLE spmb_registrations ADD COLUMN payment_notes TEXT NULL AFTER payment_date",
        'payment_updated_by' => "ALTER TABLE spmb_registrations ADD COLUMN payment_updated_by INT NULL AFTER payment_notes",
    ];

    $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'spmb_registrations' AND COLUMN_NAME = ?");
    foreach ($columns as $name => $sql) {
        $check->execute([DB_NAME, $name]);
        if ((int)$check->fetchColumn() === 0) {
            $pdo->exec($sql);
        }
    }

    $defaults = [
        ['Administrator', 'admin@tbz.sch.id', 'AdminTBZ#2026', 'admin'],
        ['Tim Humas', 'humas@tbz.sch.id', 'HumasTBZ#2026', 'humas'],
        ['Kasir SPMB', 'kasir@tbz.sch.id', 'KasirTBZ#2026', 'kasir'],
    ];
    if ((int)$pdo->query('SELECT COUNT(*) FROM portal_users')->fetchColumn() === 0) {
        $insert = $pdo->prepare('INSERT INTO portal_users (name, email, password, role) VALUES (?, ?, ?, ?)');
        foreach ($defaults as [$name, $email, $password, $role]) {
            $insert->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
        }
    }
}

portal_bootstrap_database($pdo);

function portal_user(): ?array
{
    return $_SESSION['portal_user'] ?? null;
}

function portal_logged_in(): bool
{
    return portal_user() !== null;
}

function portal_home_for_role(string $role): string
{
    return SITE_URL . '/portal/dashboard';
}

function portal_login_url(?string $role = null): string
{
    return SITE_URL . '/portal/' . ($role ?: 'admin');
}

function portal_require_guest(): void
{
    if (portal_logged_in()) {
        header('Location: ' . portal_home_for_role(portal_user()['role']));
        exit;
    }
}

function portal_require_auth(array $roles = []): void
{
    if (!portal_logged_in()) {
        header('Location: ' . portal_login_url());
        exit;
    }
    if ($roles && !in_array(portal_user()['role'], $roles, true)) {
        http_response_code(403);
        require __DIR__ . '/../frontend/pages/portal/forbidden.php';
        exit;
    }
}

function portal_attempt_login(PDO $pdo, string $email, string $password, string $expectedRole): bool
{
    $stmt = $pdo->prepare('SELECT * FROM portal_users WHERE email = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);
    $user = $stmt->fetch();
    if (!$user || $user['role'] !== $expectedRole || !password_verify($password, $user['password'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['portal_user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
    $pdo->prepare('UPDATE portal_users SET last_login_at = NOW() WHERE id = ?')->execute([$user['id']]);
    portal_log($pdo, 'login', 'Masuk ke portal sebagai ' . ucfirst($user['role']));
    return true;
}

function portal_log(PDO $pdo, string $action, string $description): void
{
    $user = portal_user();
    $stmt = $pdo->prepare('INSERT INTO portal_activity_logs (user_id, action, description) VALUES (?, ?, ?)');
    $stmt->execute([$user['id'] ?? null, $action, mb_strimwidth($description, 0, 250, '...')]);
}

function portal_csrf_token(): string
{
    if (empty($_SESSION['portal_csrf'])) {
        $_SESSION['portal_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['portal_csrf'];
}

function portal_verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['portal_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Sesi formulir tidak valid. Muat ulang halaman dan coba lagi.');
    }
}

function portal_flash(string $type, string $message): void
{
    $_SESSION['portal_flash'] = ['type' => $type, 'message' => $message];
}

function portal_get_flash(): ?array
{
    $flash = $_SESSION['portal_flash'] ?? null;
    unset($_SESSION['portal_flash']);
    return $flash;
}

function portal_slug(string $title): string
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    return trim($slug, '-') ?: 'konten-' . time();
}

function portal_upload_image(array $file, string $prefix = 'content'): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Pilih gambar yang akan diunggah.');
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Gambar gagal diunggah.');
    }
    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Ukuran gambar maksimal 5 MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, atau WebP.');
    }
    $directory = __DIR__ . '/../frontend/assets/uploads';
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException('Folder upload tidak dapat dibuat.');
    }
    $filename = $prefix . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(5)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
        throw new RuntimeException('Gagal menyimpan gambar.');
    }
    return SITE_URL . '/frontend/assets/uploads/' . $filename;
}

function portal_delete_uploaded_image(?string $url): void
{
    $uploadUrl = SITE_URL . '/frontend/assets/uploads/';
    if (!$url || strpos($url, $uploadUrl) !== 0) return;
    $filename = basename((string)parse_url($url, PHP_URL_PATH));
    if ($filename === '' || $filename === '.' || $filename === '..') return;
    $directory = realpath(__DIR__ . '/../frontend/assets/uploads');
    if (!$directory) return;
    $target = $directory . DIRECTORY_SEPARATOR . $filename;
    if (is_file($target)) unlink($target);
}
