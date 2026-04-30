<?php
require_once __DIR__ . '/../config.php';

/**
 * Send a notification to Telegram
 */
function telegram_notify($message) {
    if (!defined('TELEGRAM_CHAT_ID') || !defined('TELEGRAM_BOT_TOKEN')) return;
    if (empty(TELEGRAM_CHAT_ID) || TELEGRAM_CHAT_ID === 'REPLACE_WITH_YOUR_CHAT_ID') return;

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
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    // Disable SSL verification on localhost to avoid common cert issues
    if (($_SERVER['HTTP_HOST'] ?? '') === 'localhost' || ($_SERVER['SERVER_NAME'] ?? '') === 'localhost') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    
    curl_exec($ch);
    curl_close($ch);
}
