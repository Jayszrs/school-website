<?php
require_once 'includes/config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

$stmt = $pdo->prepare("SELECT * FROM news WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: berita.php');
    exit;
}

// Berita lain (untuk rekomendasi)
$stmt2 = $pdo->prepare("SELECT * FROM news WHERE id != ? ORDER BY published_at DESC LIMIT 3");
$stmt2->execute([$news['id']]);
$other_news = $stmt2->fetchAll();

$page_title = $news['title'];
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo esc($news['title']); ?></h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / <a href="berita.php">Berita</a> / <?php echo esc(mb_strimwidth($news['title'], 0, 40, '...')); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="news-date" style="margin-bottom:16px;"><?php echo tanggal_indo($news['published_at']); ?></div>
            <img src="<?php echo esc($news['image']); ?>" alt="<?php echo esc($news['title']); ?>" style="border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 28px; width:100%; aspect-ratio: 16/9; object-fit: cover;">
            <div style="color: var(--text); font-size: 1.02rem; line-height: 1.8;">
                <?php echo nl2br(esc($news['content'])); ?>
            </div>
            <div style="margin-top:36px;">
                <a href="berita.php" class="btn btn-outline">&larr; Kembali ke Berita</a>
            </div>
        </div>
    </div>
</section>

<?php if (count($other_news) > 0): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Baca Juga</span>
            <h2>Berita Lainnya</h2>
        </div>
        <div class="grid-3">
            <?php foreach ($other_news as $n): ?>
            <div class="card">
                <img src="<?php echo esc($n['image']); ?>" alt="<?php echo esc($n['title']); ?>" loading="lazy">
                <div class="card-body">
                    <div class="news-date"><?php echo tanggal_indo($n['published_at']); ?></div>
                    <h3><?php echo esc($n['title']); ?></h3>
                    <a href="detail-berita.php?slug=<?php echo esc($n['slug']); ?>" class="news-link">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
