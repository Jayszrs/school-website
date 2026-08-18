<?php
require_once __DIR__ . '/../../../backend/config/database.php';
require_once __DIR__ . '/../../../backend/helpers/functions.php';
require_once __DIR__ . '/../../../backend/auth.php';
portal_require_auth(['admin', 'humas']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    portal_verify_csrf();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save_news') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $publishedAt = $_POST['published_at'] ?? date('Y-m-d');
            $image = '';
            $previousImage = null;
            if ($id) {
                $existingStmt = $pdo->prepare('SELECT image FROM news WHERE id = ?');
                $existingStmt->execute([$id]);
                $previousImage = $existingStmt->fetchColumn() ?: null;
                $image = $previousImage ?: '';
            }
            if ($title === '' || $excerpt === '' || $content === '') throw new RuntimeException('Judul, ringkasan, dan isi berita wajib diisi.');
            if (!empty($_FILES['image']['name'])) $image = portal_upload_image($_FILES['image'], 'berita');
            if ($image === '') throw new RuntimeException('Gambar berita wajib dipilih.');
            $slug = portal_slug($title);
            $check = $pdo->prepare('SELECT id FROM news WHERE slug = ? AND id <> ? LIMIT 1');
            $check->execute([$slug, $id]);
            if ($check->fetch()) $slug .= '-' . ($id ?: time());
            if ($id) {
                $stmt = $pdo->prepare('UPDATE news SET title=?, slug=?, image=?, excerpt=?, content=?, published_at=? WHERE id=?');
                $stmt->execute([$title, $slug, $image, $excerpt, $content, $publishedAt, $id]);
                if ($previousImage && $previousImage !== $image) portal_delete_uploaded_image($previousImage);
                portal_log($pdo, 'update_news', 'Memperbarui berita: ' . $title);
            } else {
                $stmt = $pdo->prepare('INSERT INTO news (title, slug, image, excerpt, content, published_at) VALUES (?,?,?,?,?,?)');
                $stmt->execute([$title, $slug, $image, $excerpt, $content, $publishedAt]);
                portal_log($pdo, 'create_news', 'Menerbitkan berita: ' . $title);
            }
            portal_flash('success', 'Berita berhasil disimpan dan tampil di website.');
        } elseif ($action === 'delete_news') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('SELECT title,image FROM news WHERE id=?'); $stmt->execute([$id]); $item = $stmt->fetch();
            $pdo->prepare('DELETE FROM news WHERE id=?')->execute([$id]);
            portal_delete_uploaded_image($item['image'] ?? null);
            portal_log($pdo, 'delete_news', 'Menghapus berita: ' . ($item['title'] ?? '#' . $id));
            portal_flash('success', 'Berita berhasil dihapus.');
        } elseif ($action === 'save_gallery') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($title === '') throw new RuntimeException('Judul galeri wajib diisi.');
            $image = portal_upload_image($_FILES['image'] ?? [], 'galeri');
            $stmt = $pdo->prepare('INSERT INTO gallery (title, image, description) VALUES (?,?,?)');
            $stmt->execute([$title, $image, $description ?: null]);
            portal_log($pdo, 'create_gallery', 'Menambahkan galeri: ' . $title);
            portal_flash('success', 'Foto galeri berhasil diunggah.');
        } elseif ($action === 'delete_gallery') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('SELECT title,image FROM gallery WHERE id=?'); $stmt->execute([$id]); $item = $stmt->fetch();
            $pdo->prepare('DELETE FROM gallery WHERE id=?')->execute([$id]);
            portal_delete_uploaded_image($item['image'] ?? null);
            portal_log($pdo, 'delete_gallery', 'Menghapus galeri: ' . ($item['title'] ?? '#' . $id));
            portal_flash('success', 'Foto galeri berhasil dihapus.');
        }
    } catch (Throwable $e) {
        portal_flash('danger', $e->getMessage());
    }
    header('Location: ' . SITE_URL . '/portal/content');
    exit;
}

$editNews = null;
if (isset($_GET['edit_news'])) {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id=?');
    $stmt->execute([(int)$_GET['edit_news']]);
    $editNews = $stmt->fetch() ?: null;
}
$showNewsForm = ($_GET['new'] ?? '') === 'news' || $editNews;
$showGalleryForm = ($_GET['new'] ?? '') === 'gallery';
$news = $pdo->query('SELECT * FROM news ORDER BY published_at DESC, id DESC')->fetchAll();
$gallery = $pdo->query('SELECT * FROM gallery ORDER BY created_at DESC, id DESC')->fetchAll();

$portalTitle = 'Konten Website';
$portalActive = 'content';
require __DIR__ . '/../../components/portal-header.php';
?>
<div class="portal-welcome">
    <div><h2>Kelola Konten Publik</h2><p>Berita dan foto yang disimpan langsung tampil di company profile.</p></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap"><a class="portal-action secondary" href="<?php echo SITE_URL; ?>/portal/content?new=gallery">+ Foto Galeri</a><a class="portal-action" href="<?php echo SITE_URL; ?>/portal/content?new=news">+ Berita</a></div>
</div>

<?php if ($showNewsForm): ?>
<section class="portal-panel" style="margin-bottom:22px">
    <div class="panel-head"><h3><?php echo $editNews ? 'Edit Berita' : 'Tulis Berita Baru'; ?></h3><a href="<?php echo SITE_URL; ?>/portal/content">Tutup</a></div>
    <form class="portal-form portal-form-grid" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>"><input type="hidden" name="action" value="save_news"><input type="hidden" name="id" value="<?php echo (int)($editNews['id'] ?? 0); ?>">
        <div class="field full"><label>Judul berita</label><input name="title" value="<?php echo esc($editNews['title'] ?? ''); ?>" required></div>
        <div class="field"><label>Tanggal publikasi</label><input type="date" name="published_at" value="<?php echo esc($editNews['published_at'] ?? date('Y-m-d')); ?>" required></div>
        <div class="field"><label>Gambar <?php echo $editNews ? '(kosongkan jika tetap)' : ''; ?></label><input type="file" name="image" accept="image/jpeg,image/png,image/webp" <?php echo $editNews ? '' : 'required'; ?>></div>
        <div class="field full"><label>Ringkasan</label><textarea name="excerpt" style="min-height:80px" required><?php echo esc($editNews['excerpt'] ?? ''); ?></textarea></div>
        <div class="field full"><label>Isi berita</label><textarea name="content" required><?php echo esc($editNews['content'] ?? ''); ?></textarea></div>
        <div class="form-actions field full"><a class="portal-action secondary" href="<?php echo SITE_URL; ?>/portal/content">Batal</a><button class="portal-action" type="submit">Simpan &amp; Terbitkan</button></div>
    </form>
</section>
<?php endif; ?>

<?php if ($showGalleryForm): ?>
<section class="portal-panel" style="margin-bottom:22px">
    <div class="panel-head"><h3>Unggah Foto Galeri</h3><a href="<?php echo SITE_URL; ?>/portal/content">Tutup</a></div>
    <form class="portal-form portal-form-grid" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>"><input type="hidden" name="action" value="save_gallery">
        <div class="field"><label>Judul foto</label><input name="title" required></div>
        <div class="field"><label>Gambar (maksimal 5 MB)</label><input type="file" name="image" accept="image/jpeg,image/png,image/webp" required></div>
        <div class="field full"><label>Keterangan</label><textarea name="description" style="min-height:80px"></textarea></div>
        <div class="form-actions field full"><a class="portal-action secondary" href="<?php echo SITE_URL; ?>/portal/content">Batal</a><button class="portal-action" type="submit">Unggah Foto</button></div>
    </form>
</section>
<?php endif; ?>

<section class="portal-panel" style="margin-bottom:22px">
    <div class="panel-head"><h3>Daftar Berita (<?php echo count($news); ?>)</h3></div>
    <div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Gambar</th><th>Judul</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($news as $item): ?><tr><td><img src="<?php echo esc($item['image']); ?>" alt=""></td><td><strong><?php echo esc($item['title']); ?></strong><br><small style="color:var(--portal-muted)"><?php echo esc(mb_strimwidth($item['excerpt'], 0, 75, '...')); ?></small></td><td><?php echo esc(date('d/m/Y', strtotime($item['published_at']))); ?></td><td><div class="table-actions"><a class="portal-action secondary small" href="<?php echo SITE_URL; ?>/portal/content?edit_news=<?php echo (int)$item['id']; ?>">Edit</a><form method="post" onsubmit="return confirm('Hapus berita ini?')"><input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>"><input type="hidden" name="action" value="delete_news"><input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>"><button class="portal-action danger small">Hapus</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table></div>
</section>

<section class="portal-panel">
    <div class="panel-head"><h3>Galeri Foto (<?php echo count($gallery); ?>)</h3></div>
    <div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Foto</th><th>Judul</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($gallery as $item): ?><tr><td><img src="<?php echo esc($item['image']); ?>" alt=""></td><td><strong><?php echo esc($item['title']); ?></strong></td><td><?php echo esc($item['description'] ?: '-'); ?></td><td><form method="post" onsubmit="return confirm('Hapus foto ini?')"><input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>"><input type="hidden" name="action" value="delete_gallery"><input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>"><button class="portal-action danger small">Hapus</button></form></td></tr><?php endforeach; ?>
    </tbody></table></div>
</section>
<?php require __DIR__ . '/../../components/portal-footer.php'; ?>
