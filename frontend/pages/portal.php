<?php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/auth.php';
header('Location: ' . (portal_logged_in() ? SITE_URL . '/portal/dashboard' : SITE_URL . '/portal/admin'));
exit;

