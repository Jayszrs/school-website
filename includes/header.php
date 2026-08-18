<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? esc($page_title) . ' - ' . SITE_NAME : SITE_NAME; ?></title>
<meta name="description" content="<?php echo esc(SITE_TAGLINE); ?>">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="<?php echo SITE_URL; ?>/index.php" class="brand">
            <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Logo <?php echo esc(SITE_NAME); ?>" class="brand-logo" onerror="this.style.display='none'">
            <span class="brand-name"><?php echo esc(SITE_NAME); ?></span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav" id="mainNav">
            <ul>
                <?php foreach ($nav_menu as $file => $label): ?>
                <li>
                    <a href="<?php echo SITE_URL . '/' . $file; ?>" class="<?php echo ($current_page === $file) ? 'active' : ''; ?>">
                        <?php echo esc($label); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?php echo SITE_URL; ?>/form-spmb.php" class="btn btn-gold nav-cta">DAFTAR SPMB</a>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    toggle.addEventListener('click', function () {
        var isOpen = nav.classList.toggle('open');
        toggle.classList.toggle('active');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
});
</script>
