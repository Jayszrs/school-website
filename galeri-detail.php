<?php
require_once 'includes/config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$photo = $stmt->fetch();

if (!$photo) {
    header('Location: galeri.php');
    exit;
}

$page_title = $photo['title'];
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo esc($photo['title']); ?></h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / <a href="galeri.php">Galeri</a> / <?php echo esc($photo['title']); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="detail-image-wrap">
            <img src="<?php echo esc($photo['image']); ?>" alt="<?php echo esc($photo['title']); ?>">
            <?php if (!empty($photo['description'])): ?>
            <p style="margin-top:20px; color: var(--muted); text-align:center;"><?php echo esc($photo['description']); ?></p>
            <?php endif; ?>
            <div style="text-align:center; margin-top:28px;">
                <a href="galeri.php" class="btn btn-outline">&larr; Kembali ke Galeri</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
