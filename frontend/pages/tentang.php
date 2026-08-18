<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Tentang Kami';
require_once __DIR__ . '/../components/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Tentang Kami</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Tentang Kami</p>
    </div>
</section>

<section class="section">
    <div class="container about-grid">
        <img src="https://placehold.co/700x525/0f5132/ffffff?text=Sejarah+Sekolah" alt="Sejarah Sekolah">
        <div class="about-text">
            <span class="section-eyebrow">Sejarah Kami</span>
            <h2>Perjalanan <?php echo esc(SITE_NAME); ?></h2>
            <p>Didirikan dengan semangat mencetak generasi Qurani yang cerdas dan berakhlak mulia, <?php echo esc(SITE_NAME); ?> telah berkembang menjadi salah satu lembaga pendidikan Islam terpadu terpercaya di wilayahnya.</p>
            <p>Selama lebih dari dua dekade, kami konsisten menghadirkan sistem pendidikan yang memadukan kurikulum nasional, pendidikan Al-Qur'an, serta pembentukan karakter dalam satu ekosistem belajar yang menyeluruh.</p>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container grid-2">
        <div class="card">
            <div class="card-body">
                <h3>Visi</h3>
                <p>Menjadi lembaga pendidikan Islam terpadu terdepan yang melahirkan generasi cerdas, berakhlak mulia, dan berdaya saing global.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Misi</h3>
                <p>Menyelenggarakan pendidikan berbasis Al-Qur'an dan Sunnah, mengembangkan potensi akademik siswa secara optimal, serta membangun karakter dan kepemimpinan sejak dini.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Struktur</span>
            <h2>Kepemimpinan Sekolah</h2>
            <p>Dipimpin oleh tenaga pendidik profesional dan berpengalaman.</p>
        </div>
        <div class="grid-3">
            <div class="card">
                <img src="https://placehold.co/400x400/0f5132/ffffff?text=Kepala+Sekolah" alt="Kepala Sekolah">
                <div class="card-body">
                    <h3>Kepala Sekolah</h3>
                    <p>Memimpin arah pendidikan dan pengembangan mutu sekolah secara keseluruhan.</p>
                </div>
            </div>
            <div class="card">
                <img src="https://placehold.co/400x400/0f5132/ffffff?text=Wakil+Kurikulum" alt="Wakil Kepala Sekolah Bidang Kurikulum">
                <div class="card-body">
                    <h3>Wakil Kepala Bidang Kurikulum</h3>
                    <p>Mengelola dan mengembangkan kurikulum akademik sekolah.</p>
                </div>
            </div>
            <div class="card">
                <img src="https://placehold.co/400x400/0f5132/ffffff?text=Wakil+Kesiswaan" alt="Wakil Kepala Sekolah Bidang Kesiswaan">
                <div class="card-body">
                    <h3>Wakil Kepala Bidang Kesiswaan</h3>
                    <p>Membina kegiatan dan pengembangan karakter siswa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
