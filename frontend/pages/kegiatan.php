<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Kegiatan Sekolah';
require_once __DIR__ . '/../components/header.php';

$activities = [
    ['title' => 'Pesantren Ramadhan', 'desc' => 'Rangkaian kegiatan keagamaan selama bulan Ramadhan untuk seluruh siswa.'],
    ['title' => 'Field Trip', 'desc' => 'Kunjungan edukatif ke berbagai tempat untuk memperluas wawasan siswa.'],
    ['title' => 'Wisuda Tahfidz', 'desc' => 'Prosesi kelulusan siswa yang telah menyelesaikan target hafalan Al-Qur\'an.'],
    ['title' => 'Class Meeting', 'desc' => 'Ajang kompetisi antar kelas setelah pelaksanaan ujian semester.'],
    ['title' => 'Manasik Haji', 'desc' => 'Simulasi praktik ibadah haji sebagai bagian dari pendidikan agama.'],
    ['title' => 'Lomba Kreativitas Ramadhan', 'desc' => 'Kompetisi kreativitas siswa bertema Islami menyambut bulan suci.'],
];
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
                <img src="https://placehold.co/500x375/0f5132/ffffff?text=<?php echo urlencode($a['title']); ?>" alt="<?php echo esc($a['title']); ?>" loading="lazy">
                <div class="card-body">
                    <h3><?php echo esc($a['title']); ?></h3>
                    <p><?php echo esc($a['desc']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
