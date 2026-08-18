<?php
require_once 'includes/config.php';
$page_title = 'Berita';

$stmt = $pdo->query("SELECT * FROM news ORDER BY published_at DESC");
$news_list = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Berita &amp; Informasi</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Berita</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (count($news_list) > 0): ?>
        <div class="grid-3">
            <?php foreach ($news_list as $news): ?>
            <div class="card">
                <img src="<?php echo esc($news['image']); ?>" alt="<?php echo esc($news['title']); ?>" loading="lazy">
                <div class="card-body">
                    <div class="news-date"><?php echo tanggal_indo($news['published_at']); ?></div>
                    <h3><?php echo esc($news['title']); ?></h3>
                    <p><?php echo esc(mb_strimwidth($news['excerpt'], 0, 110, '...')); ?></p>
                    <a href="detail-berita.php?slug=<?php echo esc($news['slug']); ?>" class="news-link">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center; color: var(--muted);">Belum ada berita.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
