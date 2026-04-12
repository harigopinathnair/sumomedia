<?php
// Shared session guard — include at top of every protected admin page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// DB connection (one level up from admin/)
require_once __DIR__ . '/../db.php';
