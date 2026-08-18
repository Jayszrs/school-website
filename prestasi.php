<?php
require_once 'includes/config.php';
$page_title = 'Prestasi';
require_once 'includes/header.php';

$achievements = [
    ['title' => 'Juara 1 Olimpiade Matematika', 'level' => 'Tingkat Nasional', 'year' => '2026', 'tag' => 'Nasional', 'color' => 'd4af37'],
    ['title' => 'Juara 2 MTQ Pelajar', 'level' => 'Tingkat Provinsi', 'year' => '2025', 'tag' => 'Provinsi', 'color' => '0f5132'],
    ['title' => 'Juara 1 Lomba Sains', 'level' => 'Tingkat Kota', 'year' => '2025', 'tag' => 'Kota', 'color' => 'd4af37'],
    ['title' => 'Juara 1 Lomba Pidato Bahasa Inggris', 'level' => 'Tingkat Kota', 'year' => '2025', 'tag' => 'Kota', 'color' => '0f5132'],
    ['title' => 'Juara 3 Olimpiade Sains Nasional', 'level' => 'Tingkat Nasional', 'year' => '2024', 'tag' => 'Nasional', 'color' => 'd4af37'],
    ['title' => 'Juara 1 Futsal Antar Sekolah', 'level' => 'Tingkat Kota', 'year' => '2024', 'tag' => 'Kota', 'color' => '0f5132'],
];
?>

<section class="page-header">
    <div class="container">
        <h1>Prestasi</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Prestasi</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Prestasi Siswa</span>
            <h2>Pencapaian yang Membanggakan</h2>
        </div>
        <div class="grid-3">
            <?php foreach ($achievements as $a): ?>
            <div class="card achieve-card">
                <span class="achieve-tag"><?php echo esc($a['tag']); ?></span>
                <img src="https://placehold.co/500x375/<?php echo $a['color']; ?>/ffffff?text=<?php echo urlencode($a['title']); ?>" alt="<?php echo esc($a['title']); ?>" loading="lazy">
                <div class="card-body">
                    <h3><?php echo esc($a['title']); ?></h3>
                    <div class="achieve-meta"><span><?php echo esc($a['level']); ?></span><span><?php echo esc($a['year']); ?></span></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
