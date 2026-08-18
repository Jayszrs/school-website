<?php
require_once 'includes/config.php';
$page_title = 'Kontak';

$success = false;
$errors = [];
$old = ['name' => '', 'email' => '', 'whatsapp' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']     = trim($_POST['name'] ?? '');
    $old['email']    = trim($_POST['email'] ?? '');
    $old['whatsapp'] = trim($_POST['whatsapp'] ?? '');
    $old['message']  = trim($_POST['message'] ?? '');

    if ($old['name'] === '') $errors[] = 'Nama wajib diisi.';
    if ($old['email'] === '') {
        $errors[] = 'Email wajib diisi.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if ($old['whatsapp'] === '') $errors[] = 'Nomor WhatsApp wajib diisi.';
    if ($old['message'] === '') $errors[] = 'Pesan wajib diisi.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, whatsapp, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$old['name'], $old['email'], $old['whatsapp'], $old['message']]);
        $success = true;
        $old = ['name' => '', 'email' => '', 'whatsapp' => '', 'message' => ''];
    }
}

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Hubungi Kami</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Kontak</p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div>
            <span class="section-eyebrow">Informasi Kontak</span>
            <h2 style="margin-bottom:24px;">Kami Siap Membantu Anda</h2>

            <div class="contact-info-item">
                <div class="contact-info-icon">A</div>
                <div><h4>Alamat</h4><p><?php echo esc(SITE_ADDRESS); ?></p></div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon">T</div>
                <div><h4>Telepon</h4><p><?php echo esc(SITE_PHONE); ?></p></div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon">W</div>
                <div><h4>WhatsApp</h4><p><a href="https://wa.me/<?php echo esc(SITE_WHATSAPP); ?>" target="_blank" rel="noopener">+<?php echo esc(SITE_WHATSAPP); ?></a></p></div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon">E</div>
                <div><h4>Email</h4><p><?php echo esc(SITE_EMAIL); ?></p></div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon">S</div>
                <div><h4>Media Sosial</h4><p><a href="<?php echo esc(SITE_INSTAGRAM); ?>" target="_blank" rel="noopener">Instagram</a> &middot; <a href="<?php echo esc(SITE_YOUTUBE); ?>" target="_blank" rel="noopener">YouTube</a></p></div>
            </div>

            <div class="map-wrap">
                <iframe src="https://maps.google.com/maps?q=Cikarang%20Jawa%20Barat&t=&z=14&ie=UTF8&iwloc=&output=embed" loading="lazy" title="Lokasi Sekolah"></iframe>
            </div>
        </div>

        <div class="form-card">
            <h3 style="margin-bottom:20px;">Kirim Pesan</h3>

            <?php if ($success): ?>
                <div class="alert alert-success">Pesan berhasil dikirim.</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $err) echo esc($err) . '<br>'; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="kontak.php">
                <div class="form-group">
                    <label for="name">Nama *</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo esc($old['name']); ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo esc($old['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">Nomor WhatsApp *</label>
                        <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?php echo esc($old['whatsapp']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="message">Pesan *</label>
                    <textarea id="message" name="message" class="form-control" required><?php echo esc($old['message']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Kirim Pesan</button>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
