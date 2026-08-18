-- ============================================================
-- Database: school_website
-- Import melalui phpMyAdmin (Import > Choose File > Go)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `school_website` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `school_website`;

-- ============================================================
-- Tabel: news (Berita)
-- ============================================================
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `image` VARCHAR(255) DEFAULT NULL,
    `excerpt` TEXT,
    `content` LONGTEXT,
    `published_at` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- Tabel: gallery (Galeri Foto)
-- ============================================================
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- Tabel: contacts (Pesan dari Form Kontak)
-- ============================================================
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `whatsapp` VARCHAR(30) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- Tabel: spmb_registrations (Pendaftaran SPMB)
-- ============================================================
CREATE TABLE IF NOT EXISTS `spmb_registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_number` VARCHAR(40) DEFAULT NULL,
    `student_name` VARCHAR(150) NOT NULL,
    `student_nik` VARCHAR(30) DEFAULT NULL,
    `gender` ENUM('L','P') DEFAULT NULL,
    `birth_place` VARCHAR(100) DEFAULT NULL,
    `birth_date` DATE DEFAULT NULL,
    `parent_name` VARCHAR(150) NOT NULL,
    `parent_nik` VARCHAR(30) DEFAULT NULL,
    `family_card_number` VARCHAR(30) DEFAULT NULL,
    `whatsapp` VARCHAR(30) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `level` VARCHAR(20) NOT NULL,
    `previous_school` VARCHAR(150) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `registration_status` ENUM('baru','verifikasi','lulus','cadangan','ditolak','daftar_ulang') NOT NULL DEFAULT 'baru',
    `document_status` ENUM('belum_lengkap','lengkap','terverifikasi') NOT NULL DEFAULT 'belum_lengkap',
    `payment_status` ENUM('belum_bayar','sebagian','lunas') NOT NULL DEFAULT 'belum_bayar',
    `payment_amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `payment_method` VARCHAR(50) DEFAULT NULL,
    `payment_date` DATE DEFAULT NULL,
    `payment_notes` TEXT DEFAULT NULL,
    `payment_updated_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel akun portal internal. Akun awal dibuat otomatis oleh backend/auth.php
-- dengan password ter-hash saat portal pertama kali dibuka.
CREATE TABLE IF NOT EXISTS `portal_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `username` VARCHAR(80) NOT NULL,
    `email` VARCHAR(190) DEFAULT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','humas','kasir') NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_portal_username` (`username`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `portal_activity_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_activity_user` (`user_id`),
    CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `portal_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `site_content_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(30) NOT NULL,
    `title` VARCHAR(180) NOT NULL,
    `subtitle` VARCHAR(180) DEFAULT NULL,
    `description` TEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `badge` VARCHAR(80) DEFAULT NULL,
    `year` VARCHAR(10) DEFAULT NULL,
    `extra` TEXT DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_content_type` (`type`,`is_active`,`sort_order`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `site_profile` (
    `id` TINYINT PRIMARY KEY,
    `history_title` VARCHAR(180) NOT NULL,
    `history_content` TEXT NOT NULL,
    `vision` TEXT NOT NULL,
    `mission` TEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `spmb_payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_id` INT NOT NULL,
    `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
    `payment_type` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(14,2) NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `payment_date` DATE NOT NULL,
    `reference_number` VARCHAR(100) DEFAULT NULL,
    `payer_name` VARCHAR(150) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `status` ENUM('verified','cancelled') NOT NULL DEFAULT 'verified',
    `recorded_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_payment_registration` (`registration_id`),
    CONSTRAINT `fk_payment_registration` FOREIGN KEY (`registration_id`) REFERENCES `spmb_registrations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_payment_recorder` FOREIGN KEY (`recorded_by`) REFERENCES `portal_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Konten awal CMS agar halaman publik langsung terisi setelah import.
INSERT INTO `site_profile` (`id`,`history_title`,`history_content`,`vision`,`mission`) VALUES
(1, 'Perjalanan LPIT Thariq Bin Ziyad', 'Didirikan dengan semangat mencetak generasi Qurani yang cerdas dan berakhlak mulia, LPIT Thariq Bin Ziyad berkembang menjadi lembaga pendidikan Islam terpadu terpercaya. Selama lebih dari dua dekade, kami konsisten memadukan kurikulum nasional, pendidikan Al-Quran, dan pembentukan karakter.', 'Menjadi lembaga pendidikan Islam terpadu terdepan yang melahirkan generasi cerdas, berakhlak mulia, dan berdaya saing global.', 'Menyelenggarakan pendidikan berbasis Al-Quran dan Sunnah, mengembangkan potensi akademik secara optimal, serta membangun karakter dan kepemimpinan sejak dini.');

INSERT INTO `site_content_items` (`type`,`title`,`subtitle`,`description`,`badge`,`year`,`extra`,`sort_order`) VALUES
('unit','SD Islam Terpadu','SD','Jenjang pendidikan dasar yang menanamkan fondasi akademik, keimanan, dan akhlak sejak usia dini.',NULL,NULL,'Tahfidz Juz 30\nCalistung\nEkstrakurikuler\nFull Day School',1),
('unit','SMP Islam Terpadu','SMP','Menguatkan kompetensi akademik dan kepemimpinan siswa melalui kurikulum terintegrasi.',NULL,NULL,'Tahfidz Juz Pilihan\nEnglish Club\nKlub Sains\nKepemimpinan',2),
('unit','SMA Islam Terpadu','SMA','Mempersiapkan siswa menghadapi perguruan tinggi dengan penguatan akademik dan karakter Islami.',NULL,NULL,'Bimbingan PTN\nPeminatan IPA/IPS\nLeadership Camp\nKarya Ilmiah',3),
('achievement','Juara 1 Olimpiade Matematika','Tingkat Nasional','Prestasi siswa dalam Olimpiade Matematika.','Nasional','2026',NULL,1),
('achievement','Juara 2 MTQ Pelajar','Tingkat Provinsi','Prestasi siswa dalam Musabaqah Tilawatil Quran.','Provinsi','2025',NULL,2),
('achievement','Juara 1 Lomba Sains','Tingkat Kota','Prestasi siswa dalam kompetisi sains.','Kota','2025',NULL,3),
('leadership','Nama Kepala Sekolah','Kepala Sekolah','Memimpin arah pendidikan dan pengembangan mutu sekolah.',NULL,NULL,NULL,1),
('leadership','Nama Wakil Kepala Sekolah','Wakil Kepala Bidang Kurikulum','Mengelola dan mengembangkan kurikulum akademik sekolah.',NULL,NULL,NULL,2),
('leadership','Nama Wakil Kepala Sekolah','Wakil Kepala Bidang Kesiswaan','Membina kegiatan dan pengembangan karakter siswa.',NULL,NULL,NULL,3),
('program','Tahfidz Al-Quran','Q','Program hafalan Al-Quran dengan target setiap jenjang.',NULL,NULL,NULL,1),
('program','English Program','E','Penguatan kemampuan bahasa Inggris aktif melalui kelas percakapan.',NULL,NULL,NULL,2),
('program','Character Building','C','Pembinaan akhlak dan karakter Islami dalam keseharian.',NULL,NULL,NULL,3),
('program','Digital Learning','D','Pemanfaatan teknologi dalam proses pembelajaran.',NULL,NULL,NULL,4),
('program','Leadership Program','L','Melatih kepemimpinan melalui organisasi dan proyek kolaboratif.',NULL,NULL,NULL,5),
('activity','Pesantren Ramadhan',NULL,'Kegiatan keagamaan selama bulan Ramadhan.',NULL,NULL,NULL,1),
('activity','Field Trip',NULL,'Kunjungan edukatif untuk memperluas wawasan siswa.',NULL,NULL,NULL,2),
('activity','Wisuda Tahfidz',NULL,'Prosesi kelulusan siswa yang menyelesaikan target hafalan.',NULL,NULL,NULL,3);

-- ============================================================
-- SAMPLE DATA: news
-- ============================================================
INSERT INTO `news` (`title`, `slug`, `image`, `excerpt`, `content`, `published_at`) VALUES
('Pesantren Ramadhan 1447 H Resmi Dibuka', 'pesantren-ramadhan-1447-h-resmi-dibuka',
 'https://placehold.co/800x500/0f5132/ffffff?text=Pesantren+Ramadhan',
 'Kegiatan Pesantren Ramadhan tahun ini diikuti oleh seluruh siswa SD, SMP, dan SMA dengan berbagai rangkaian acara keagamaan.',
 'Kegiatan Pesantren Ramadhan tahun ini diikuti oleh seluruh siswa SD, SMP, dan SMA dengan berbagai rangkaian acara keagamaan seperti tadarus bersama, kajian akhlak, buka puasa bersama, dan santunan anak yatim. Kegiatan ini bertujuan untuk memperkuat nilai-nilai spiritual siswa selama bulan suci Ramadhan sekaligus mempererat ukhuwah antar siswa dan guru.',
 '2026-03-10'),
('Siswa Raih Juara 1 Olimpiade Matematika Nasional', 'siswa-raih-juara-1-olimpiade-matematika-nasional',
 'https://placehold.co/800x500/0f5132/ffffff?text=Olimpiade+Matematika',
 'Prestasi membanggakan kembali diraih oleh siswa SMP kami dalam ajang Olimpiade Sains Nasional bidang Matematika.',
 'Prestasi membanggakan kembali diraih oleh siswa SMP kami dalam ajang Olimpiade Sains Nasional bidang Matematika. Setelah melalui seleksi ketat tingkat kota, provinsi, hingga nasional, siswa kami berhasil membawa pulang medali emas. Pencapaian ini merupakan hasil dari bimbingan intensif guru pembina serta kerja keras siswa selama berbulan-bulan.',
 '2026-02-20'),
('Wisuda Tahfidz Angkatan XII Berlangsung Khidmat', 'wisuda-tahfidz-angkatan-xii-berlangsung-khidmat',
 'https://placehold.co/800x500/0f5132/ffffff?text=Wisuda+Tahfidz',
 'Sebanyak 45 siswa mengikuti prosesi Wisuda Tahfidz Al-Quran angkatan XII yang dihadiri oleh orang tua dan wali murid.',
 'Sebanyak 45 siswa mengikuti prosesi Wisuda Tahfidz Al-Quran angkatan XII yang dihadiri oleh orang tua dan wali murid. Acara ini menjadi momen istimewa bagi para siswa yang telah menyelesaikan target hafalan juz yang ditentukan. Kepala sekolah berharap program tahfidz ini terus mencetak generasi penghafal Al-Quran yang berakhlak mulia.',
 '2026-01-15');

-- ============================================================
-- SAMPLE DATA: gallery
-- ============================================================
INSERT INTO `gallery` (`title`, `image`, `description`) VALUES
('Gedung Sekolah', 'https://placehold.co/600x450/0f5132/ffffff?text=Gedung+Sekolah', 'Tampak depan gedung sekolah'),
('Kegiatan Belajar Mengajar', 'https://placehold.co/600x450/0f5132/ffffff?text=Kegiatan+Belajar', 'Suasana kelas yang nyaman'),
('Lapangan Olahraga', 'https://placehold.co/600x450/0f5132/ffffff?text=Lapangan+Olahraga', 'Fasilitas olahraga siswa'),
('Perpustakaan', 'https://placehold.co/600x450/0f5132/ffffff?text=Perpustakaan', 'Ruang baca dan koleksi buku'),
('Laboratorium Komputer', 'https://placehold.co/600x450/0f5132/ffffff?text=Lab+Komputer', 'Fasilitas digital learning'),
('Masjid Sekolah', 'https://placehold.co/600x450/0f5132/ffffff?text=Masjid+Sekolah', 'Pusat kegiatan keagamaan siswa');

-- Selesai
