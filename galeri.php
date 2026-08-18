<?php
require_once 'includes/config.php';
$page_title = 'Galeri';

$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$photos = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Galeri Sekolah</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Galeri</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (count($photos) > 0): ?>
        <div class="gallery-grid">
            <?php foreach ($photos as $photo): ?>
            <a href="galeri-detail.php?id=<?php echo (int)$photo['id']; ?>" class="gallery-item">
                <img src="<?php echo esc($photo['image']); ?>" alt="<?php echo esc($photo['title']); ?>" loading="lazy">
                <span class="gallery-caption"><?php echo esc($photo['title']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center; color: var(--muted);">Belum ada foto galeri.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
