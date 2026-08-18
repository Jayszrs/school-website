<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Prestasi';
$achievements = $pdo->query("SELECT * FROM site_content_items WHERE type='achievement' AND is_active=1 ORDER BY sort_order,id")->fetchAll();
require_once __DIR__ . '/../components/header.php';
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
                <span class="achieve-tag"><?php echo esc($a['badge'] ?: 'Prestasi'); ?></span>
                <img src="<?php echo esc($a['image'] ?: 'https://placehold.co/500x375/0f5132/ffffff?text='.urlencode($a['title'])); ?>" alt="<?php echo esc($a['title']); ?>" loading="lazy">
                <div class="card-body">
                    <h3><?php echo esc($a['title']); ?></h3>
                    <p><?php echo esc($a['description']); ?></p>
                    <div class="achieve-meta"><span><?php echo esc($a['subtitle']); ?></span><span><?php echo esc($a['year']); ?></span></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
