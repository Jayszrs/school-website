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
        username VARCHAR(80) NULL,
        email VARCHAR(190) NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','humas','kasir') NOT NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        last_login_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $userColumnCheck = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'portal_users' AND COLUMN_NAME = ?");
    $userColumnCheck->execute([DB_NAME, 'username']);
    if ((int)$userColumnCheck->fetchColumn() === 0) {
        $pdo->exec("ALTER TABLE portal_users ADD COLUMN username VARCHAR(80) NULL AFTER name");
    }
    $emailNullable = $pdo->prepare("SELECT IS_NULLABLE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME='portal_users' AND COLUMN_NAME='email'");
    $emailNullable->execute([DB_NAME]);
    if ($emailNullable->fetchColumn() === 'NO') {
        $pdo->exec("ALTER TABLE portal_users MODIFY email VARCHAR(190) NULL");
    }
    $pdo->exec("UPDATE portal_users SET email=NULL WHERE (username='admin' AND email='admin@tbz.sch.id') OR (username='humas' AND email='humas@tbz.sch.id') OR (username='kasir' AND email='kasir@tbz.sch.id')");
    $pdo->exec("UPDATE portal_users SET username = role WHERE username IS NULL OR username = ''");
    $usernameIndex = $pdo->prepare("SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'portal_users' AND INDEX_NAME = 'uq_portal_username'");
    $usernameIndex->execute([DB_NAME]);
    if ((int)$usernameIndex->fetchColumn() === 0) {
        $pdo->exec("ALTER TABLE portal_users ADD UNIQUE INDEX uq_portal_username (username)");
    }

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
        'registration_number' => "ALTER TABLE spmb_registrations ADD COLUMN registration_number VARCHAR(40) NULL AFTER id",
        'student_nik' => "ALTER TABLE spmb_registrations ADD COLUMN student_nik VARCHAR(30) NULL AFTER student_name",
        'gender' => "ALTER TABLE spmb_registrations ADD COLUMN gender ENUM('L','P') NULL AFTER student_nik",
        'birth_place' => "ALTER TABLE spmb_registrations ADD COLUMN birth_place VARCHAR(100) NULL AFTER gender",
        'birth_date' => "ALTER TABLE spmb_registrations ADD COLUMN birth_date DATE NULL AFTER birth_place",
        'address' => "ALTER TABLE spmb_registrations ADD COLUMN address TEXT NULL AFTER previous_school",
        'parent_nik' => "ALTER TABLE spmb_registrations ADD COLUMN parent_nik VARCHAR(30) NULL AFTER parent_name",
        'family_card_number' => "ALTER TABLE spmb_registrations ADD COLUMN family_card_number VARCHAR(30) NULL AFTER parent_nik",
        'registration_status' => "ALTER TABLE spmb_registrations ADD COLUMN registration_status ENUM('baru','verifikasi','lulus','cadangan','ditolak','daftar_ulang') NOT NULL DEFAULT 'baru' AFTER address",
        'document_status' => "ALTER TABLE spmb_registrations ADD COLUMN document_status ENUM('belum_lengkap','lengkap','terverifikasi') NOT NULL DEFAULT 'belum_lengkap' AFTER registration_status",
    ];

    $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'spmb_registrations' AND COLUMN_NAME = ?");
    foreach ($columns as $name => $sql) {
        $check->execute([DB_NAME, $name]);
        if ((int)$check->fetchColumn() === 0) {
            $pdo->exec($sql);
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS site_content_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(30) NOT NULL,
        title VARCHAR(180) NOT NULL,
        subtitle VARCHAR(180) NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NULL,
        badge VARCHAR(80) NULL,
        year VARCHAR(10) NULL,
        extra TEXT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_content_type (type, is_active, sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS site_profile (
        id TINYINT PRIMARY KEY,
        history_title VARCHAR(180) NOT NULL,
        history_content TEXT NOT NULL,
        vision TEXT NOT NULL,
        mission TEXT NOT NULL,
        image VARCHAR(255) NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS spmb_payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        registration_id INT NOT NULL,
        receipt_number VARCHAR(50) NOT NULL UNIQUE,
        payment_type VARCHAR(50) NOT NULL,
        amount DECIMAL(14,2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        payment_date DATE NOT NULL,
        reference_number VARCHAR(100) NULL,
        payer_name VARCHAR(150) NULL,
        notes TEXT NULL,
        status ENUM('verified','cancelled') NOT NULL DEFAULT 'verified',
        recorded_by INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_payment_registration (registration_id),
        CONSTRAINT fk_payment_registration FOREIGN KEY (registration_id) REFERENCES spmb_registrations(id) ON DELETE CASCADE,
        CONSTRAINT fk_payment_recorder FOREIGN KEY (recorded_by) REFERENCES portal_users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    portal_seed_site_content($pdo);

    $defaults = [
        ['Administrator', 'admin', 'AdminTBZ#2026', 'admin'],
        ['Tim Humas', 'humas', 'HumasTBZ#2026', 'humas'],
        ['Kasir SPMB', 'kasir', 'KasirTBZ#2026', 'kasir'],
    ];
    if ((int)$pdo->query('SELECT COUNT(*) FROM portal_users')->fetchColumn() === 0) {
        $insert = $pdo->prepare('INSERT INTO portal_users (name, username, password, role) VALUES (?, ?, ?, ?)');
        foreach ($defaults as [$name, $username, $password, $role]) {
            $insert->execute([$name, $username, password_hash($password, PASSWORD_DEFAULT), $role]);
        }
    }
}

function portal_seed_site_content(PDO $pdo): void
{
    $seeds = [
        'unit' => [
            ['SD Islam Terpadu', 'SD', 'Jenjang pendidikan dasar yang menanamkan fondasi akademik, keimanan, dan akhlak sejak usia dini melalui pembelajaran tematik yang aktif dan menyenangkan.', 'Tahfidz Juz 30\nCalistung\nEkstrakurikuler\nFull Day School'],
            ['SMP Islam Terpadu', 'SMP', 'Menguatkan kompetensi akademik dan kepemimpinan siswa melalui kurikulum terintegrasi, program tahfidz lanjutan, dan pembinaan organisasi siswa.', 'Tahfidz Juz Pilihan\nEnglish Club\nKlub Sains\nKepemimpinan'],
            ['SMA Islam Terpadu', 'SMA', 'Mempersiapkan siswa menghadapi perguruan tinggi dan dunia kerja dengan penguatan akademik, minat bakat, dan karakter Islami.', 'Bimbingan PTN\nPeminatan IPA/IPS\nLeadership Camp\nKarya Ilmiah'],
        ],
        'achievement' => [
            ['Juara 1 Olimpiade Matematika', 'Tingkat Nasional', 'Prestasi siswa dalam Olimpiade Matematika.', 'Nasional|2026'],
            ['Juara 2 MTQ Pelajar', 'Tingkat Provinsi', 'Prestasi siswa dalam Musabaqah Tilawatil Quran.', 'Provinsi|2025'],
            ['Juara 1 Lomba Sains', 'Tingkat Kota', 'Prestasi siswa dalam kompetisi sains.', 'Kota|2025'],
        ],
        'leadership' => [
            ['Nama Kepala Sekolah', 'Kepala Sekolah', 'Memimpin arah pendidikan dan pengembangan mutu sekolah secara keseluruhan.', ''],
            ['Nama Wakil Kepala Sekolah', 'Wakil Kepala Bidang Kurikulum', 'Mengelola dan mengembangkan kurikulum akademik sekolah.', ''],
            ['Nama Wakil Kepala Sekolah', 'Wakil Kepala Bidang Kesiswaan', 'Membina kegiatan dan pengembangan karakter siswa.', ''],
        ],
        'program' => [
            ["Tahfidz Al-Qur'an", 'Q', "Program hafalan Al-Qur'an dengan target setiap jenjang dan bimbingan guru tahfidz.", ''],
            ['English Program', 'E', 'Penguatan kemampuan bahasa Inggris aktif melalui kelas percakapan dan klub bahasa.', ''],
            ['Character Building', 'C', 'Pembinaan akhlak dan karakter Islami dalam kegiatan belajar sehari-hari.', ''],
            ['Digital Learning', 'D', 'Pemanfaatan teknologi untuk mempersiapkan siswa menghadapi era digital.', ''],
            ['Leadership Program', 'L', 'Melatih kepemimpinan melalui organisasi, proyek kolaboratif, dan kegiatan sosial.', ''],
        ],
        'activity' => [
            ['Pesantren Ramadhan', '', 'Rangkaian kegiatan keagamaan selama bulan Ramadhan untuk seluruh siswa.', ''],
            ['Field Trip', '', 'Kunjungan edukatif untuk memperluas wawasan siswa.', ''],
            ['Wisuda Tahfidz', '', "Prosesi kelulusan siswa yang menyelesaikan target hafalan Al-Qur'an.", ''],
        ],
    ];
    $count = $pdo->prepare('SELECT COUNT(*) FROM site_content_items WHERE type=?');
    $insert = $pdo->prepare('INSERT INTO site_content_items (type,title,subtitle,description,image,badge,year,extra,sort_order) VALUES (?,?,?,?,?,?,?,?,?)');
    foreach ($seeds as $type => $items) {
        $count->execute([$type]);
        if ((int)$count->fetchColumn() > 0) continue;
        foreach ($items as $index => $item) {
            [$title, $subtitle, $description, $extra] = $item;
            $badge = null; $year = null;
            if ($type === 'achievement' && strpos($extra, '|') !== false) {
                [$badge, $year] = explode('|', $extra, 2); $extra = '';
            }
            $insert->execute([$type, $title, $subtitle ?: null, $description, null, $badge, $year, $extra ?: null, $index + 1]);
        }
    }
    $profile = $pdo->prepare('INSERT IGNORE INTO site_profile (id,history_title,history_content,vision,mission) VALUES (1,?,?,?,?)');
    $profile->execute([
        'Perjalanan ' . SITE_NAME,
        'Didirikan dengan semangat mencetak generasi Qurani yang cerdas dan berakhlak mulia, LPIT Thariq Bin Ziyad berkembang menjadi lembaga pendidikan Islam terpadu terpercaya. Selama lebih dari dua dekade, kami konsisten memadukan kurikulum nasional, pendidikan Al-Quran, dan pembentukan karakter.',
        'Menjadi lembaga pendidikan Islam terpadu terdepan yang melahirkan generasi cerdas, berakhlak mulia, dan berdaya saing global.',
        'Menyelenggarakan pendidikan berbasis Al-Quran dan Sunnah, mengembangkan potensi akademik secara optimal, serta membangun karakter dan kepemimpinan sejak dini.'
    ]);
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
    return SITE_URL . '/portal/admin';
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

function portal_attempt_login(PDO $pdo, string $username, string $password): bool
{
    $stmt = $pdo->prepare('SELECT * FROM portal_users WHERE username = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([strtolower(trim($username))]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['portal_user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'username' => $user['username'],
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
