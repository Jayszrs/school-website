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
    `student_name` VARCHAR(150) NOT NULL,
    `parent_name` VARCHAR(150) NOT NULL,
    `whatsapp` VARCHAR(30) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `level` VARCHAR(20) NOT NULL,
    `previous_school` VARCHAR(150) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

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
