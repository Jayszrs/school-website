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
$home_units = $pdo->query("SELECT * FROM site_content_items WHERE type='unit' AND is_active=1 ORDER BY sort_order,id LIMIT 3")->fetchAll();
$home_programs = $pdo->query("SELECT * FROM site_content_items WHERE type='program' AND is_active=1 ORDER BY sort_order,id LIMIT 5")->fetchAll();
$home_achievements = $pdo->query("SELECT * FROM site_content_items WHERE type='achievement' AND is_active=1 ORDER BY sort_order,id LIMIT 3")->fetchAll();
$home_profile = $pdo->query('SELECT * FROM site_profile WHERE id=1')->fetch();
$home_activities = $pdo->query("SELECT * FROM site_content_items WHERE type='activity' AND is_active=1 ORDER BY sort_order,id LIMIT 4")->fetchAll();

require_once __DIR__ . '/../components/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="container hero-inner">
        <span class="hero-eyebrow">Penerimaan Siswa Baru Tahun Ajaran 2026/2027</span>
        <h1>Lembaga Pendidikan <span>Islam Terpadu</span> <strong class="school-name">Thariq Bin Ziyad</strong></h1>
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
        <img src="<?php echo esc($home_profile['image'] ?: 'https://placehold.co/700x525/0f5132/ffffff?text=Tentang+Sekolah'); ?>" alt="Tentang Sekolah">
        <div class="about-text">
            <span class="section-eyebrow">Tentang Kami</span>
            <h2><?php echo esc($home_profile['history_title']); ?></h2>
            <p><?php echo esc(mb_strimwidth($home_profile['history_content'],0,390,'...')); ?></p>
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
            <?php foreach($home_units as $unit): ?><div class="card"><img src="<?php echo esc($unit['image'] ?: 'https://placehold.co/500x375/0f5132/ffffff?text='.urlencode($unit['subtitle'] ?: $unit['title'])); ?>" alt="<?php echo esc($unit['title']); ?>"><div class="card-body"><h3><?php echo esc($unit['title']); ?></h3><p><?php echo esc(mb_strimwidth($unit['description'],0,145,'...')); ?></p><a href="unit.php#<?php echo esc(strtolower($unit['subtitle'] ?: 'unit-'.$unit['id'])); ?>" class="btn btn-outline btn-sm">Lihat Detail</a></div></div><?php endforeach; ?>
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
            <?php foreach($home_programs as $program): ?><div class="program-card"><div class="program-icon"><?php echo esc($program['subtitle'] ?: mb_substr($program['title'],0,1)); ?></div><h3><?php echo esc($program['title']); ?></h3><p><?php echo esc(mb_strimwidth($program['description'],0,125,'...')); ?></p></div><?php endforeach; ?>
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
            <?php foreach($home_achievements as $achievement): ?><div class="card achieve-card"><span class="achieve-tag"><?php echo esc($achievement['badge'] ?: 'Prestasi'); ?></span><img src="<?php echo esc($achievement['image'] ?: 'https://placehold.co/500x375/d4af37/1f2937?text='.urlencode($achievement['title'])); ?>" alt="<?php echo esc($achievement['title']); ?>"><div class="card-body"><h3><?php echo esc($achievement['title']); ?></h3><div class="achieve-meta"><span><?php echo esc($achievement['subtitle']); ?></span><span><?php echo esc($achievement['year']); ?></span></div></div></div><?php endforeach; ?>
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
            <?php foreach($home_activities as $activity): ?><div class="card"><img src="<?php echo esc($activity['image'] ?: 'https://placehold.co/400x300/0f5132/ffffff?text='.urlencode($activity['title'])); ?>" alt="<?php echo esc($activity['title']); ?>"><div class="card-body"><h3><?php echo esc($activity['title']); ?></h3><p><?php echo esc(mb_strimwidth($activity['description'],0,100,'...')); ?></p></div></div><?php endforeach; ?>
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
