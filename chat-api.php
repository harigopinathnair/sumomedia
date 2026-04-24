<?php
ini_set('display_errors', '0');
ob_start();
register_shutdown_function(function() {
    $e = error_get_last();
    if ($e && ($e['type'] & (E_ERROR|E_PARSE|E_CORE_ERROR|E_COMPILE_ERROR|E_USER_ERROR))) {
        while (ob_get_level()) ob_end_clean();
        if (!headers_sent()) header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => '[PHP] ' . $e['message'] . ' (' . basename($e['file']) . ':' . $e['line'] . ')']);
    }
});
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/captcha.php';

// Auto-create tables
$pdo->exec("CREATE TABLE IF NOT EXISTS chat_sessions (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  token      VARCHAR(64)  NOT NULL UNIQUE,
  name       VARCHAR(255) NOT NULL,
  email      VARCHAR(255) NOT NULL,
  phone      VARCHAR(100) DEFAULT NULL,
  location   VARCHAR(255) DEFAULT NULL,
  page_url   VARCHAR(500) DEFAULT NULL,
  status     ENUM('active','closed') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS chat_messages (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  sender     ENUM('user','admin') NOT NULL,
  message    TEXT NOT NULL,
  sent_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

function out(array $data): void {
    ob_end_clean();
    echo json_encode($data);
    exit;
}

// === TELEGRAM CONFIGURATION ===
// Place your Chat ID below once obtained (e.g. '123456789')
define('TELEGRAM_BOT_TOKEN', '7721750928:AAHLm-Ma7DclGTxrUJ_Q2hYMGnYCtAhNajM');
define('TELEGRAM_CHAT_ID', '5683352272'); 

function telegram_notify($message) {
    if (TELEGRAM_CHAT_ID === 'REPLACE_WITH_YOUR_CHAT_ID' || empty(TELEGRAM_CHAT_ID)) return;
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // don't hold up the user interface
    curl_exec($ch);
    curl_close($ch);
}

function get_session(PDO $pdo, string $token) {
    $s = $pdo->prepare("SELECT * FROM chat_sessions WHERE token = ? LIMIT 1");
    $s->execute([trim($token)]);
    return $s->fetch(PDO::FETCH_ASSOC);
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ── Create a new chat session ────────────────────────────────
    case 'start':
        if (!captcha_check()) {
            out(['ok' => false, 'error' => 'Incorrect answer to the anti-spam question. Please try again.']);
        }

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $phone    = trim($_POST['phone']    ?? '');
        $location = trim($_POST['location'] ?? '');
        $page_url = trim($_POST['page_url'] ?? '');

        if (!$name || !$email) {
            out(['ok' => false, 'error' => 'Name and email are required.']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            out(['ok' => false, 'error' => 'Please enter a valid email.']);
        }

        $token = bin2hex(random_bytes(32));
        $pdo->prepare("INSERT INTO chat_sessions (token,name,email,phone,location,page_url) VALUES (?,?,?,?,?,?)")
            ->execute([$token, $name, $email, $phone ?: null, $location ?: null, $page_url ?: null]);
        $sid = (int)$pdo->lastInsertId();

        // Auto-greeting from admin
        $pdo->prepare("INSERT INTO chat_messages (session_id,sender,message) VALUES (?,?,?)")
            ->execute([$sid, 'admin', "Hi $name! Thanks for reaching out. I'll get back to you shortly."]);

        // Send Telegram Notification
        $tg_msg = "🟢 <b>New Chat Started [Session #$sid]</b>\n"
                . "<b>Name:</b> " . htmlspecialchars($name) . "\n"
                . "<b>Email:</b> " . htmlspecialchars($email) . "\n"
                . "<b>Phone:</b> " . htmlspecialchars($phone ?: 'N/A') . "\n"
                . "<b>Loc:</b> " . htmlspecialchars($location ?: 'N/A') . "\n"
                . "<b>Page:</b> " . htmlspecialchars($page_url ?: 'N/A') . "\n\n"
                . "Reply instantly from telegram using:\n<code>/r $sid your message here</code>";
        telegram_notify($tg_msg);

        out(['ok' => true, 'token' => $token, 'session_id' => $sid]);

    // ── User sends a message ─────────────────────────────────────
    case 'send':
        $token   = trim($_POST['token']   ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$token || $message === '') out(['ok' => false, 'error' => 'Missing data.']);

        $session = get_session($pdo, $token);
        if (!$session) out(['ok' => false, 'error' => 'Invalid session.']);
        if ($session['status'] === 'closed') out(['ok' => false, 'error' => 'This chat has been closed.']);

        $pdo->prepare("UPDATE chat_sessions SET updated_at=NOW() WHERE id=?")
            ->execute([$session['id']]);
        $pdo->prepare("INSERT INTO chat_messages (session_id,sender,message) VALUES (?,?,?)")
            ->execute([$session['id'], 'user', $message]);
        $mid = (int)$pdo->lastInsertId();

        $tg_msg = "💬 <b>[Session #" . $session['id'] . "] " . htmlspecialchars($session['name']) . "</b> says:\n"
                . htmlspecialchars($message) . "\n\n"
                . "<code>/r " . $session['id'] . " your message</code>";
        telegram_notify($tg_msg);

        out(['ok' => true, 'message_id' => $mid]);

    // ── Poll for new messages (user side) ───────────────────────
    case 'poll':
        $token   = trim($_GET['token']   ?? $_POST['token']   ?? '');
        $last_id = (int)($_GET['last_id'] ?? $_POST['last_id'] ?? 0);

        if (!$token) out(['ok' => false, 'error' => 'No token.']);

        $session = get_session($pdo, $token);
        if (!$session) out(['ok' => false, 'error' => 'Invalid session.']);

        $stmt = $pdo->prepare(
            "SELECT id, sender, message, sent_at FROM chat_messages
             WHERE session_id = ? AND id > ? ORDER BY id ASC"
        );
        $stmt->execute([$session['id'], $last_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        out(['ok' => true, 'messages' => $messages, 'status' => $session['status']]);

    default:
        out(['ok' => false, 'error' => 'Unknown action.']);
}
