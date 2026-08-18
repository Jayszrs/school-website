<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Kegiatan Sekolah';
$activities = $pdo->query("SELECT * FROM site_content_items WHERE type='activity' AND is_active=1 ORDER BY sort_order,id")->fetchAll();
require_once __DIR__ . '/../components/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Kegiatan Sekolah</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Kegiatan</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Kegiatan</span>
            <h2>Kegiatan Rutin &amp; Tahunan</h2>
        </div>
        <div class="grid-3">
            <?php foreach ($activities as $a): ?>
            <div class="card">
                <img src="<?php echo esc($a['image'] ?: 'https://placehold.co/500x375/0f5132/ffffff?text='.urlencode($a['title'])); ?>" alt="<?php echo esc($a['title']); ?>" loading="lazy">
                <div class="card-body">
                    <?php if($a['subtitle']): ?><div class="news-date"><?php echo esc($a['subtitle']); ?></div><?php endif; ?>
                    <h3><?php echo esc($a['title']); ?></h3>
                    <p><?php echo nl2br(esc($a['description'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
