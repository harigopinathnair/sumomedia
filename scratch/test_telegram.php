<?php
// === TELEGRAM CONFIGURATION ===
define('TELEGRAM_BOT_TOKEN', '7721750928:AAHLm-Ma7DclGTxrUJ_Q2hYMGnYCtAhNajM');
define('TELEGRAM_CHAT_ID', '5683352272'); 

function telegram_notify($message) {
    if (TELEGRAM_CHAT_ID === 'REPLACE_WITH_YOUR_CHAT_ID' || empty(TELEGRAM_CHAT_ID)) {
        echo "Error: TELEGRAM_CHAT_ID not configured.\n";
        return;
    }
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    echo "HTTP Status: $httpCode\n";
    echo "Response: $response\n";
    if ($err) echo "CURL Error: $err\n";
}

$test_msg = "🚀 <b>SumoMedia System Test</b>\n"
          . "Telegram connection is ACTIVE.\n"
          . "Time: " . date('Y-m-d H:i:s') . "\n"
          . "Environment: " . ($_SERVER['HTTP_HOST'] ?? 'CLI');

telegram_notify($test_msg);
