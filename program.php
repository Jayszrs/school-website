<?php
require_once 'includes/config.php';
$page_title = 'Program Unggulan';
require_once 'includes/header.php';

$programs = [
    ['icon' => 'Q', 'title' => "Tahfidz Al-Qur'an", 'desc' => "Program hafalan Al-Qur'an dengan target dan metode yang disesuaikan setiap jenjang, dibimbing oleh guru tahfidz bersertifikat."],
    ['icon' => 'E', 'title' => 'English Program', 'desc' => 'Penguatan kemampuan berbahasa Inggris aktif melalui kelas percakapan, English day, dan klub bahasa.'],
    ['icon' => 'C', 'title' => 'Character Building', 'desc' => 'Pembinaan akhlak dan karakter Islami yang terintegrasi dalam kegiatan belajar sehari-hari.'],
    ['icon' => 'D', 'title' => 'Digital Learning', 'desc' => 'Pemanfaatan teknologi dalam proses pembelajaran untuk mempersiapkan siswa menghadapi era digital.'],
    ['icon' => 'L', 'title' => 'Leadership Program', 'desc' => 'Melatih jiwa kepemimpinan siswa melalui organisasi, proyek kolaboratif, dan kegiatan sosial.'],
];
?>

<section class="page-header">
    <div class="container">
        <h1>Program Unggulan</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Program</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Program Pendidikan</span>
            <h2>Program yang Kami Tawarkan</h2>
            <p>Dirancang untuk mengembangkan potensi siswa secara akademik, spiritual, dan sosial.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($programs as $p): ?>
            <div class="card">
                <div class="card-body">
                    <div class="program-icon" style="margin-bottom:16px;"><?php echo esc($p['icon']); ?></div>
                    <h3><?php echo esc($p['title']); ?></h3>
                    <p><?php echo esc($p['desc']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
