<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <div class="brand">
                <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Logo" class="brand-logo brand-logo-footer" onerror="this.style.display='none'">
                <span class="brand-name"><?php echo esc(SITE_NAME); ?></span>
            </div>
            <p><?php echo esc(SITE_TAGLINE); ?>. Sekolah Islam modern yang berkomitmen mencetak generasi cerdas, berakhlak, dan berdaya saing global.</p>
            <div class="footer-social">
                <a href="<?php echo esc(SITE_INSTAGRAM); ?>" target="_blank" rel="noopener">Instagram</a>
                <a href="<?php echo esc(SITE_YOUTUBE); ?>" target="_blank" rel="noopener">YouTube</a>
                <a href="https://wa.me/<?php echo esc(SITE_WHATSAPP); ?>" target="_blank" rel="noopener">WhatsApp</a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Menu Cepat</h4>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/index.php">Beranda</a></li>
                <li><a href="<?php echo SITE_URL; ?>/tentang.php">Tentang Kami</a></li>
                <li><a href="<?php echo SITE_URL; ?>/berita.php">Berita</a></li>
                <li><a href="<?php echo SITE_URL; ?>/galeri.php">Galeri</a></li>
                <li><a href="<?php echo SITE_URL; ?>/kontak.php">Kontak</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Unit Sekolah</h4>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/unit.php#sd">SD Islam Terpadu</a></li>
                <li><a href="<?php echo SITE_URL; ?>/unit.php#smp">SMP Islam Terpadu</a></li>
                <li><a href="<?php echo SITE_URL; ?>/unit.php#sma">SMA Islam Terpadu</a></li>
                <li><a href="<?php echo SITE_URL; ?>/spmb.php">Penerimaan Siswa Baru</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Kontak</h4>
            <ul class="footer-contact">
                <li><?php echo esc(SITE_ADDRESS); ?></li>
                <li><?php echo esc(SITE_PHONE); ?></li>
                <li><?php echo esc(SITE_EMAIL); ?></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc(SITE_NAME); ?>. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<a href="https://wa.me/<?php echo esc(SITE_WHATSAPP); ?>" class="wa-float" target="_blank" rel="noopener" aria-label="Hubungi WhatsApp">WA</a>

</body>
</html>
