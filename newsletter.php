<?php
ini_set('display_errors', '0');
ob_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function nl_json(bool $ok, string $msg = ''): void {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['ok' => $ok, 'error' => $msg]);
    exit;
}

require_once __DIR__ . '/includes/captcha.php';
if (!captcha_check()) {
    if ($is_ajax) nl_json(false, 'Incorrect anti-spam answer. Please try again.');
    header('Location: index.php#newsletter');
    exit;
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($is_ajax) nl_json(false, 'Please enter a valid email address.');
    header('Location: index.php#newsletter');
    exit;
}

$source       = substr(trim($_POST['source']       ?? 'newsletter_form'), 0, 100);
$page_url     = substr(trim($_POST['page_url']     ?? ''), 0, 500);
$utm_source   = substr(trim($_POST['utm_source']   ?? ''), 0, 100);
$utm_medium   = substr(trim($_POST['utm_medium']   ?? ''), 0, 100);
$utm_campaign = substr(trim($_POST['utm_campaign'] ?? ''), 0, 100);
$utm_content  = substr(trim($_POST['utm_content']  ?? ''), 0, 100);
$utm_term     = substr(trim($_POST['utm_term']     ?? ''), 0, 100);

try {
    require_once __DIR__ . '/db.php';

    // Auto-create subscribers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id           INT(11)      AUTO_INCREMENT PRIMARY KEY,
        email        VARCHAR(191) NOT NULL UNIQUE,
        status       VARCHAR(20)  NOT NULL DEFAULT 'active',
        source       VARCHAR(100) NOT NULL DEFAULT 'website',
        page_url     VARCHAR(500) DEFAULT NULL,
        utm_source   VARCHAR(100) DEFAULT NULL,
        utm_medium   VARCHAR(100) DEFAULT NULL,
        utm_campaign VARCHAR(100) DEFAULT NULL,
        utm_content  VARCHAR(100) DEFAULT NULL,
        utm_term     VARCHAR(100) DEFAULT NULL,
        created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Auto-migrate: add UTM columns if missing
    foreach ([
        'page_url'     => 'VARCHAR(500) DEFAULT NULL',
        'utm_source'   => 'VARCHAR(100) DEFAULT NULL',
        'utm_medium'   => 'VARCHAR(100) DEFAULT NULL',
        'utm_campaign' => 'VARCHAR(100) DEFAULT NULL',
        'utm_content'  => 'VARCHAR(100) DEFAULT NULL',
        'utm_term'     => 'VARCHAR(100) DEFAULT NULL',
    ] as $col => $def) {
        try { $pdo->exec("ALTER TABLE subscribers ADD COLUMN `$col` $def"); } catch (Exception $e) {}
    }

    // Insert, ignore duplicates
    $stmt = $pdo->prepare("INSERT IGNORE INTO subscribers
        (email, source, page_url, utm_source, utm_medium, utm_campaign, utm_content, utm_term)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$email, $source, $page_url ?: null, $utm_source ?: null,
                    $utm_medium ?: null, $utm_campaign ?: null,
                    $utm_content ?: null, $utm_term ?: null]);

} catch (Exception $e) {
    error_log('Newsletter error: ' . $e->getMessage());
}

if ($is_ajax) nl_json(true);
// Redirect back with success flag (non-AJAX fallback)
$redirect = $page_url ?: 'index.php';
header('Location: ' . $redirect . (strpos($redirect, '?') !== false ? '&' : '?') . 'subscribed=1#newsletter');
exit;
