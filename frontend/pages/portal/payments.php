<?php
require_once __DIR__ . '/../../../backend/config/database.php';
require_once __DIR__ . '/../../../backend/helpers/functions.php';
require_once __DIR__ . '/../../../backend/auth.php';
portal_require_auth(['admin', 'kasir']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    portal_verify_csrf();
    try {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['payment_status'] ?? 'belum_bayar';
        $allowed = ['belum_bayar', 'sebagian', 'lunas'];
        if (!$id || !in_array($status, $allowed, true)) throw new RuntimeException('Data pembayaran tidak valid.');
        $amount = max(0, (float)($_POST['payment_amount'] ?? 0));
        $method = trim($_POST['payment_method'] ?? '');
        $date = trim($_POST['payment_date'] ?? '');
        $notes = trim($_POST['payment_notes'] ?? '');
        if ($status !== 'belum_bayar' && $amount <= 0) throw new RuntimeException('Nominal pembayaran harus lebih dari nol.');
        $stmt = $pdo->prepare('UPDATE spmb_registrations SET payment_status=?, payment_amount=?, payment_method=?, payment_date=?, payment_notes=?, payment_updated_by=? WHERE id=?');
        $stmt->execute([$status, $amount, $method ?: null, $date ?: null, $notes ?: null, portal_user()['id'], $id]);
        $nameStmt = $pdo->prepare('SELECT student_name FROM spmb_registrations WHERE id=?'); $nameStmt->execute([$id]);
        portal_log($pdo, 'update_payment', 'Memperbarui pembayaran SPMB ' . ($nameStmt->fetchColumn() ?: '#' . $id) . ' menjadi ' . str_replace('_', ' ', $status));
        portal_flash('success', 'Status pembayaran berhasil diperbarui.');
    } catch (Throwable $e) {
        portal_flash('danger', $e->getMessage());
    }
    header('Location: ' . SITE_URL . '/portal/payments');
    exit;
}

$search = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(student_name LIKE ? OR parent_name LIKE ? OR whatsapp LIKE ?)';
    $like = '%' . $search . '%';
    array_push($params, $like, $like, $like);
}
if (in_array($statusFilter, ['belum_bayar', 'sebagian', 'lunas'], true)) {
    $where[] = 'payment_status = ?';
    $params[] = $statusFilter;
}
$sql = 'SELECT * FROM spmb_registrations' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params); $registrations = $stmt->fetchAll();
$summary = $pdo->query("SELECT COUNT(*) total, SUM(payment_status='lunas') paid, SUM(payment_status='sebagian') partial, COALESCE(SUM(payment_amount),0) amount FROM spmb_registrations")->fetch();
$editPayment = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM spmb_registrations WHERE id=?'); $stmt->execute([(int)$_GET['edit']]); $editPayment = $stmt->fetch() ?: null;
}
function portal_rupiah($amount): string { return 'Rp ' . number_format((float)$amount, 0, ',', '.'); }

$portalTitle = 'Pembayaran SPMB';
$portalActive = 'payments';
require __DIR__ . '/../../components/portal-header.php';
?>
<div class="portal-welcome"><div><h2>Kasir SPMB</h2><p>Catat pembayaran dan pantau status administrasi calon siswa.</p></div></div>
<div class="stat-grid">
    <div class="stat-card"><small>Total pendaftar</small><strong><?php echo (int)$summary['total']; ?></strong><span>Semua jenjang</span></div>
    <div class="stat-card"><small>Pembayaran lunas</small><strong><?php echo (int)$summary['paid']; ?></strong><span>Terverifikasi</span></div>
    <div class="stat-card"><small>Bayar sebagian</small><strong><?php echo (int)$summary['partial']; ?></strong><span>Perlu pelunasan</span></div>
    <div class="stat-card"><small>Total diterima</small><strong style="font-size:1.18rem"><?php echo portal_rupiah($summary['amount']); ?></strong><span>Akumulasi tercatat</span></div>
</div>

<?php if ($editPayment): ?>
<section class="portal-panel" style="margin-bottom:22px">
    <div class="panel-head"><h3>Update Pembayaran — <?php echo esc($editPayment['student_name']); ?></h3><a href="<?php echo SITE_URL; ?>/portal/payments">Tutup</a></div>
    <form class="portal-form portal-form-grid" method="post">
        <input type="hidden" name="_token" value="<?php echo esc(portal_csrf_token()); ?>"><input type="hidden" name="id" value="<?php echo (int)$editPayment['id']; ?>">
        <div class="field"><label>Status pembayaran</label><select name="payment_status" required><option value="belum_bayar" <?php echo $editPayment['payment_status']==='belum_bayar'?'selected':''; ?>>Belum Bayar</option><option value="sebagian" <?php echo $editPayment['payment_status']==='sebagian'?'selected':''; ?>>Bayar Sebagian</option><option value="lunas" <?php echo $editPayment['payment_status']==='lunas'?'selected':''; ?>>Lunas</option></select></div>
        <div class="field"><label>Nominal diterima (Rp)</label><input type="number" min="0" step="1000" name="payment_amount" value="<?php echo esc($editPayment['payment_amount']); ?>"></div>
        <div class="field"><label>Metode</label><select name="payment_method"><option value="">-- Pilih --</option><?php foreach (['Transfer Bank','Tunai','QRIS','Virtual Account'] as $method): ?><option <?php echo $editPayment['payment_method']===$method?'selected':''; ?>><?php echo esc($method); ?></option><?php endforeach; ?></select></div>
        <div class="field"><label>Tanggal pembayaran</label><input type="date" name="payment_date" value="<?php echo esc($editPayment['payment_date'] ?: date('Y-m-d')); ?>"></div>
        <div class="field full"><label>Catatan</label><textarea name="payment_notes" style="min-height:80px"><?php echo esc($editPayment['payment_notes']); ?></textarea></div>
        <div class="form-actions field full"><a class="portal-action secondary" href="<?php echo SITE_URL; ?>/portal/payments">Batal</a><button class="portal-action" type="submit">Simpan Pembayaran</button></div>
    </form>
</section>
<?php endif; ?>

<section class="portal-panel">
    <div class="panel-head"><h3>Data Pendaftar</h3></div>
    <form class="filters" method="get"><input name="q" value="<?php echo esc($search); ?>" placeholder="Cari siswa/orang tua/WhatsApp"><select name="status"><option value="">Semua status</option><option value="belum_bayar" <?php echo $statusFilter==='belum_bayar'?'selected':''; ?>>Belum bayar</option><option value="sebagian" <?php echo $statusFilter==='sebagian'?'selected':''; ?>>Sebagian</option><option value="lunas" <?php echo $statusFilter==='lunas'?'selected':''; ?>>Lunas</option></select><button class="portal-action small">Filter</button><a class="portal-action secondary small" href="<?php echo SITE_URL; ?>/portal/payments">Reset</a></form>
    <div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Calon Siswa</th><th>Jenjang</th><th>Orang Tua / WA</th><th>Status</th><th>Nominal</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
    <?php if (!$registrations): ?><tr><td colspan="7" class="empty-state">Belum ada data pendaftar yang cocok.</td></tr><?php endif; ?>
    <?php foreach ($registrations as $item): ?><tr><td><strong><?php echo esc($item['student_name']); ?></strong><br><small style="color:var(--portal-muted)">Daftar <?php echo esc(date('d/m/Y', strtotime($item['created_at']))); ?></small></td><td><?php echo esc($item['level']); ?></td><td><?php echo esc($item['parent_name']); ?><br><small><?php echo esc($item['whatsapp']); ?></small></td><td><span class="status <?php echo esc($item['payment_status']); ?>"><?php echo esc(ucwords(str_replace('_',' ',$item['payment_status']))); ?></span></td><td class="amount"><?php echo portal_rupiah($item['payment_amount']); ?></td><td><?php echo $item['payment_date'] ? esc(date('d/m/Y', strtotime($item['payment_date']))) : '-'; ?></td><td><a class="portal-action secondary small" href="<?php echo SITE_URL; ?>/portal/payments?edit=<?php echo (int)$item['id']; ?>">Update</a></td></tr><?php endforeach; ?>
    </tbody></table></div>
</section>
<?php require __DIR__ . '/../../components/portal-footer.php'; ?>

