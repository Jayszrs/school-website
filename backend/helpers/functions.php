<?php
/**
 * Pengaturan Umum Website dan Fungsi Pembantu (Helpers)
 */

// ==== PENGATURAN UMUM WEBSITE ====
define('SITE_NAME', 'LPIT Thariq Bin Ziyad');
define('SITE_TAGLINE', 'Lembaga Pendidikan Islam Terpadu — Membentuk Generasi Qurani, Cerdas, dan Berkarakter');
define('SITE_URL', 'http://localhost/school-website');
define('SITE_PHONE', '(021) 1234-5678');
define('SITE_WHATSAPP', '6281234567890');
define('SITE_EMAIL', 'info@thariqbinziyad.sch.id');
define('SITE_ADDRESS', 'Jl. Pendidikan Raya No. 45, Cikarang, Jawa Barat');
define('SITE_INSTAGRAM', 'https://instagram.com/lpitthariqbinziyad');
define('SITE_YOUTUBE', 'https://youtube.com/@lpitthariqbinziyad');

// Helper untuk output aman (mencegah XSS)
function esc($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper untuk format tanggal Indonesia
function tanggal_indo($tanggal) {
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $timestamp = strtotime($tanggal);
    return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

// Menu navigasi dengan dropdown support
$nav_menu = [
    'index.php'    => 'Home',
    'profil'       => [
        'label' => 'Profil',
        'children' => [
            'tentang.php'  => 'Tentang Kami',
            'unit.php'     => 'Unit Sekolah',
            'program.php'  => 'Program',
            'prestasi.php' => 'Prestasi',
        ]
    ],
    'kontak.php'   => 'Lokasi Sekolah',
    '#alquran'     => 'Al Quran',
    'berita.php'   => 'News',
    'brosur'       => [
        'label' => 'Brosur',
        'children' => [
            'galeri.php'    => 'Galeri',
            'kegiatan.php'  => 'Kegiatan',
        ]
    ],
    'spmb.php'     => 'SPMB',
    '#karir'       => 'Karir',
];
