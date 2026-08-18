<?php
require_once 'includes/config.php';
$page_title = 'Form Pendaftaran SPMB';

$success = false;
$errors = [];
$old = ['student_name' => '', 'parent_name' => '', 'whatsapp' => '', 'email' => '', 'level' => '', 'previous_school' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['student_name']    = trim($_POST['student_name'] ?? '');
    $old['parent_name']     = trim($_POST['parent_name'] ?? '');
    $old['whatsapp']        = trim($_POST['whatsapp'] ?? '');
    $old['email']           = trim($_POST['email'] ?? '');
    $old['level']           = trim($_POST['level'] ?? '');
    $old['previous_school'] = trim($_POST['previous_school'] ?? '');

    // Validasi
    if ($old['student_name'] === '') $errors[] = 'Nama calon siswa wajib diisi.';
    if ($old['parent_name'] === '') $errors[] = 'Nama orang tua wajib diisi.';
    if ($old['whatsapp'] === '') $errors[] = 'Nomor WhatsApp wajib diisi.';
    if ($old['level'] === '') $errors[] = 'Jenjang wajib dipilih.';
    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO spmb_registrations (student_name, parent_name, whatsapp, email, level, previous_school) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $old['student_name'],
            $old['parent_name'],
            $old['whatsapp'],
            $old['email'] ?: null,
            $old['level'],
            $old['previous_school'] ?: null,
        ]);
        $success = true;
        $old = ['student_name' => '', 'parent_name' => '', 'whatsapp' => '', 'email' => '', 'level' => '', 'previous_school' => ''];
    }
}

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Form Pendaftaran SPMB</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / <a href="spmb.php">SPMB</a> / Form Pendaftaran</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="max-width: 640px; margin: 0 auto;">
            <div class="form-card">

                <?php if ($success): ?>
                    <div class="alert alert-success">Pendaftaran berhasil dikirim. Tim kami akan segera menghubungi Anda.</div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <?php foreach ($errors as $err) echo esc($err) . '<br>'; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="form-spmb.php">
                    <div class="form-group">
                        <label for="student_name">Nama Calon Siswa *</label>
                        <input type="text" id="student_name" name="student_name" class="form-control" value="<?php echo esc($old['student_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Nama Orang Tua *</label>
                        <input type="text" id="parent_name" name="parent_name" class="form-control" value="<?php echo esc($old['parent_name']); ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="whatsapp">Nomor WhatsApp *</label>
                            <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?php echo esc($old['whatsapp']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo esc($old['email']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="level">Jenjang *</label>
                        <select id="level" name="level" class="form-control" required>
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="SD" <?php echo $old['level'] === 'SD' ? 'selected' : ''; ?>>SD Islam Terpadu</option>
                            <option value="SMP" <?php echo $old['level'] === 'SMP' ? 'selected' : ''; ?>>SMP Islam Terpadu</option>
                            <option value="SMA" <?php echo $old['level'] === 'SMA' ? 'selected' : ''; ?>>SMA Islam Terpadu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="previous_school">Asal Sekolah</label>
                        <input type="text" id="previous_school" name="previous_school" class="form-control" value="<?php echo esc($old['previous_school']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Kirim Pendaftaran</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
