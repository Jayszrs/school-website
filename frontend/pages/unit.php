<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Unit Sekolah';
require_once __DIR__ . '/../components/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Unit Sekolah</h1>
        <p class="breadcrumb"><a href="index.php">Beranda</a> / Unit Sekolah</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="unit-block" id="sd">
            <div class="unit-media"><img src="https://placehold.co/700x525/0f5132/ffffff?text=SD+Islam+Terpadu" alt="SD Islam Terpadu"></div>
            <div class="unit-text">
                <h2>SD Islam Terpadu</h2>
                <p>Jenjang pendidikan dasar yang menanamkan fondasi akademik, keimanan, dan akhlak sejak usia dini melalui pembelajaran tematik yang aktif dan menyenangkan.</p>
                <div class="unit-tags"><span>Tahfidz Juz 30</span><span>Calistung</span><span>Ekstrakurikuler</span><span>Full Day School</span></div>
                <a href="spmb.php" class="btn btn-primary">Daftar di Unit Ini</a>
            </div>
        </div>

        <div class="unit-block reverse" id="smp">
            <div class="unit-media"><img src="https://placehold.co/700x525/0f5132/ffffff?text=SMP+Islam+Terpadu" alt="SMP Islam Terpadu"></div>
            <div class="unit-text">
                <h2>SMP Islam Terpadu</h2>
                <p>Menguatkan kompetensi akademik dan kepemimpinan siswa melalui kurikulum terintegrasi, program tahfidz lanjutan, dan pembinaan organisasi siswa.</p>
                <div class="unit-tags"><span>Tahfidz Juz Pilihan</span><span>English Club</span><span>Klub Sains</span><span>Kepemimpinan</span></div>
                <a href="spmb.php" class="btn btn-primary">Daftar di Unit Ini</a>
            </div>
        </div>

        <div class="unit-block" id="sma">
            <div class="unit-media"><img src="https://placehold.co/700x525/0f5132/ffffff?text=SMA+Islam+Terpadu" alt="SMA Islam Terpadu"></div>
            <div class="unit-text">
                <h2>SMA Islam Terpadu</h2>
                <p>Mempersiapkan siswa menghadapi jenjang perguruan tinggi dan dunia kerja dengan penguatan akademik, minat bakat, dan pembinaan karakter Islami yang matang.</p>
                <div class="unit-tags"><span>Bimbingan PTN</span><span>Peminatan IPA/IPS</span><span>Leadership Camp</span><span>Karya Ilmiah</span></div>
                <a href="spmb.php" class="btn btn-primary">Daftar di Unit Ini</a>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
