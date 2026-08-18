<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
$page_title = 'Beranda';

// Ambil 3 berita terbaru
$stmt = $pdo->query("SELECT * FROM news ORDER BY published_at DESC LIMIT 3");
$latest_news = $stmt->fetchAll();

// Ambil 6 foto galeri
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6");
$gallery_photos = $stmt->fetchAll();

require_once __DIR__ . '/../components/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="container hero-inner">
        <span class="hero-eyebrow">Penerimaan Siswa Baru Tahun Ajaran 2026/2027</span>
        <h1>Lembaga Pendidikan <span>Islam Terpadu</span> Thariq Bin Ziyad</h1>
        <p>Membentuk generasi Qur'ani, cerdas, dan berkarakter dengan paduan kurikulum akademik modern dan nilai-nilai keislaman untuk menghadapi masa depan.</p>
        <div class="hero-actions">
            <a href="tentang.php" class="btn btn-outline-light">Tentang Kami</a>
            <a href="spmb.php" class="btn btn-primary">Daftar SPMB</a>
        </div>
    </div>
</section>

<!-- RUNNING TEXT -->
<div style="background: var(--primary-dark); color: #fff; padding: 10px 0; overflow: hidden; white-space: nowrap;">
    <div class="container">
        <marquee behavior="scroll" direction="left" scrollamount="6" style="font-size: 0.9rem; font-weight: 600;">
            ΓÇó Selamat datang di website resmi LPIT Thariq Bin Ziyad ΓÇó Pendaftaran Murid Baru Tahun Ajaran 2026/2027 Telah Dibuka! ΓÇó Membentuk Generasi Qur'ani, Akademik, Bahasa, Menuju Trendsetter Sekolah Islam Unggulan ΓÇó
        </marquee>
    </div>
</div>

<!-- TENTANG SEKOLAH -->
<section class="section">
    <div class="container about-grid">
        <img src="https://placehold.co/700x525/0f5132/ffffff?text=Tentang+Sekolah" alt="Tentang Sekolah">
        <div class="about-text">
            <span class="section-eyebrow">Tentang Kami</span>
            <h2>Mendidik dengan Hati, Membimbing dengan Ilmu</h2>
            <p><?php echo esc(SITE_NAME); ?> hadir sebagai lembaga pendidikan Islam terpadu yang mengintegrasikan kurikulum nasional, pendidikan Al-Qur'an, serta pengembangan karakter dalam satu sistem pembelajaran yang komprehensif.</p>
            <p>Dengan dukungan tenaga pendidik profesional dan fasilitas modern, kami berkomitmen menghadirkan lingkungan belajar yang nyaman, aman, dan menyenangkan bagi setiap siswa.</p>
            <a href="tentang.php" class="btn btn-primary">Selengkapnya</a>
        </div>
    </div>
</section>

<!-- UNIT SEKOLAH -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Unit Pendidikan</span>
            <h2>Jenjang Pendidikan Kami</h2>
            <p>Menyediakan jenjang pendidikan berkelanjutan dari usia dini hingga menengah atas.</p>
        </div>
        <div class="grid-3">
            <div class="card">
                <img src="https://placehold.co/500x375/0f5132/ffffff?text=SD" alt="SD Islam Terpadu">
                <div class="card-body">
                    <h3>SD Islam Terpadu</h3>
                    <p>Membangun fondasi akademik dan akhlak sejak dini melalui pembelajaran tematik yang menyenangkan.</p>
                    <a href="unit.php#sd" class="btn btn-outline btn-sm">Lihat Detail</a>
                </div>
            </div>
            <div class="card">
                <img src="https://placehold.co/500x375/0f5132/ffffff?text=SMP" alt="SMP Islam Terpadu">
                <div class="card-body">
                    <h3>SMP Islam Terpadu</h3>
                    <p>Mengembangkan potensi akademik dan kepemimpinan siswa dengan kurikulum terintegrasi.</p>
                    <a href="unit.php#smp" class="btn btn-outline btn-sm">Lihat Detail</a>
                </div>
            </div>
            <div class="card">
                <img src="https://placehold.co/500x375/0f5132/ffffff?text=SMA" alt="SMA Islam Terpadu">
                <div class="card-body">
                    <h3>SMA Islam Terpadu</h3>
                    <p>Mempersiapkan siswa menuju jenjang perguruan tinggi dengan bekal akademik dan karakter kuat.</p>
                    <a href="unit.php#sma" class="btn btn-outline btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PROGRAM UNGGULAN -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Program Unggulan</span>
            <h2>Program Pendidikan Terpadu</h2>
            <p>Rangkaian program yang dirancang untuk mengembangkan potensi siswa secara menyeluruh.</p>
        </div>
        <div class="grid-4">
            <div class="program-card">
                <div class="program-icon">Q</div>
                <h3>Tahfidz Al-Qur'an</h3>
                <p>Program hafalan Al-Qur'an terstruktur sesuai target jenjang.</p>
            </div>
            <div class="program-card">
                <div class="program-icon">E</div>
                <h3>English Program</h3>
                <p>Penguatan kemampuan bahasa Inggris aktif sejak dini.</p>
            </div>
            <div class="program-card">
                <div class="program-icon">C</div>
                <h3>Character Building</h3>
                <p>Pembentukan akhlak dan karakter Islami dalam keseharian.</p>
            </div>
            <div class="program-card">
                <div class="program-icon">D</div>
                <h3>Digital Learning</h3>
                <p>Pembelajaran berbasis teknologi untuk kesiapan era digital.</p>
            </div>
            <div class="program-card">
                <div class="program-icon">L</div>
                <h3>Leadership Program</h3>
                <p>Melatih jiwa kepemimpinan melalui organisasi dan proyek siswa.</p>
            </div>
        </div>
    </div>
</section>

<!-- KENAPA MEMILIH SEKOLAH KAMI -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Keunggulan</span>
            <h2>Kenapa Memilih Sekolah Kami</h2>
        </div>
        <div class="why-grid">
            <div class="why-item"><div class="why-icon">1</div><div><h3>Pendidikan Islami</h3><p>Kurikulum terintegrasi nilai-nilai Al-Qur'an dan Sunnah.</p></div></div>
            <div class="why-item"><div class="why-icon">2</div><div><h3>Guru Profesional</h3><p>Tenaga pendidik berpengalaman dan bersertifikasi.</p></div></div>
            <div class="why-item"><div class="why-icon">3</div><div><h3>Kurikulum Berkualitas</h3><p>Perpaduan kurikulum nasional dan pengembangan karakter.</p></div></div>
            <div class="why-item"><div class="why-icon">4</div><div><h3>Lingkungan Nyaman</h3><p>Suasana belajar yang aman, asri, dan mendukung.</p></div></div>
            <div class="why-item"><div class="why-icon">5</div><div><h3>Fasilitas Lengkap</h3><p>Sarana pembelajaran modern dan lengkap.</p></div></div>
            <div class="why-item"><div class="why-icon">6</div><div><h3>Pengembangan Karakter</h3><p>Program pembinaan akhlak dan kepemimpinan berkelanjutan.</p></div></div>
        </div>
    </div>
</section>

<!-- STATISTIK -->
<section class="section text-center" style="background: var(--primary-dark); color: white;">
    <div class="container grid-4" id="statsSection">
        <div><h2 style="color:var(--accent); font-size:3rem; margin-bottom:10px;"><span class="counter" data-target="1000">0</span>+</h2><p>Siswa</p></div>
        <div><h2 style="color:var(--accent); font-size:3rem; margin-bottom:10px;"><span class="counter" data-target="100">0</span>+</h2><p>Guru</p></div>
        <div><h2 style="color:var(--accent); font-size:3rem; margin-bottom:10px;"><span class="counter" data-target="20">0</span>+</h2><p>Program</p></div>
        <div><h2 style="color:var(--accent); font-size:3rem; margin-bottom:10px;"><span class="counter" data-target="50">0</span>+</h2><p>Prestasi</p></div>
    </div>
</section>

<!-- PRESTASI -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Prestasi</span>
            <h2>Prestasi Membanggakan</h2>
            <p>Sebagian pencapaian siswa-siswi kami di berbagai ajang kompetisi.</p>
        </div>
        <div class="grid-3">
            <div class="card achieve-card">
                <span class="achieve-tag">Nasional</span>
                <img src="https://placehold.co/500x375/d4af37/1f2937?text=Olimpiade+Matematika" alt="Juara Olimpiade Matematika">
                <div class="card-body">
                    <h3>Juara 1 Olimpiade Matematika</h3>
                    <div class="achieve-meta"><span>Tingkat Nasional</span><span>2026</span></div>
                </div>
            </div>
            <div class="card achieve-card">
                <span class="achieve-tag">Provinsi</span>
                <img src="https://placehold.co/500x375/d4af37/1f2937?text=MTQ" alt="Juara MTQ">
                <div class="card-body">
                    <h3>Juara 2 MTQ Pelajar</h3>
                    <div class="achieve-meta"><span>Tingkat Provinsi</span><span>2025</span></div>
                </div>
            </div>
            <div class="card achieve-card">
                <span class="achieve-tag">Kota</span>
                <img src="https://placehold.co/500x375/d4af37/1f2937?text=Sains" alt="Juara Sains">
                <div class="card-body">
                    <h3>Juara 1 Lomba Sains</h3>
                    <div class="achieve-meta"><span>Tingkat Kota</span><span>2025</span></div>
                </div>
            </div>
        </div>
        <div style="text-align:center; margin-top:36px;">
            <a href="prestasi.php" class="btn btn-outline">Lihat Semua Prestasi</a>
        </div>
    </div>
</section>

<!-- KEGIATAN SEKOLAH -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Kegiatan</span>
            <h2>Kegiatan Sekolah Terbaru</h2>
        </div>
        <div class="grid-4">
            <div class="card">
                <img src="https://placehold.co/400x300/0f5132/ffffff?text=Pesantren+Ramadhan" alt="Pesantren Ramadhan">
                <div class="card-body"><h3>Pesantren Ramadhan</h3></div>
            </div>
            <div class="card">
                <img src="https://placehold.co/400x300/0f5132/ffffff?text=Field+Trip" alt="Field Trip">
                <div class="card-body"><h3>Field Trip</h3></div>
            </div>
            <div class="card">
                <img src="https://placehold.co/400x300/0f5132/ffffff?text=Wisuda+Tahfidz" alt="Wisuda Tahfidz">
                <div class="card-body"><h3>Wisuda Tahfidz</h3></div>
            </div>
            <div class="card">
                <img src="https://placehold.co/400x300/0f5132/ffffff?text=Class+Meeting" alt="Class Meeting">
                <div class="card-body"><h3>Class Meeting</h3></div>
            </div>
        </div>
        <div style="text-align:center; margin-top:36px;">
            <a href="kegiatan.php" class="btn btn-outline">Lihat Semua Kegiatan</a>
        </div>
    </div>
</section>

<!-- BERITA TERBARU -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Berita</span>
            <h2>Berita &amp; Informasi Terbaru</h2>
        </div>
        <div class="grid-3">
            <?php foreach ($latest_news as $news): ?>
            <div class="card">
                <img src="<?php echo esc($news['image']); ?>" alt="<?php echo esc($news['title']); ?>" loading="lazy">
                <div class="card-body">
                    <div class="news-date"><?php echo tanggal_indo($news['published_at']); ?></div>
                    <h3><?php echo esc($news['title']); ?></h3>
                    <p><?php echo esc(mb_strimwidth($news['excerpt'], 0, 100, '...')); ?></p>
                    <a href="detail-berita.php?slug=<?php echo esc($news['slug']); ?>" class="news-link">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- GALERI -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow">Galeri</span>
            <h2>Momen di Sekolah Kami</h2>
        </div>
        <div class="gallery-grid">
            <?php foreach ($gallery_photos as $photo): ?>
            <a href="galeri-detail.php?id=<?php echo (int)$photo['id']; ?>" class="gallery-item">
                <img src="<?php echo esc($photo['image']); ?>" alt="<?php echo esc($photo['title']); ?>" loading="lazy">
                <span class="gallery-caption"><?php echo esc($photo['title']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:36px;">
            <a href="galeri.php" class="btn btn-outline">Lihat Semua Galeri</a>
        </div>
    </div>
</section>

<!-- CTA SPMB -->
<section class="section">
    <div class="container">
        <div class="cta-spmb">
            <h2>Penerimaan Murid Baru Telah Dibuka</h2>
            <p>Bergabunglah bersama keluarga besar <?php echo esc(SITE_NAME); ?> dan persiapkan masa depan terbaik untuk putra-putri Anda.</p>
            <div class="cta-actions">
                <a href="form-spmb.php" class="btn btn-gold">DAFTAR SEKARANG</a>
                <a href="https://wa.me/<?php echo esc(SITE_WHATSAPP); ?>" target="_blank" rel="noopener" class="btn btn-outline-light">HUBUNGI WHATSAPP</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

<!-- Script Parallax Hero -->
<script>
document.addEventListener("scroll", function() {
    const hero = document.querySelector('.hero');
    if (hero) {
        let scrollPos = window.pageYOffset;
        hero.style.backgroundPositionY = (scrollPos * 0.4) + "px";
    }
});

// Counter Animation
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    const animateCounters = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
                observer.unobserve(counter);
            }
        });
    };

    const counterObserver = new IntersectionObserver(animateCounters, {
        threshold: 0.5
    });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
});
</script>
