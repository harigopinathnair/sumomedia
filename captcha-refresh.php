<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
require_once __DIR__ . '/includes/captcha.php';
$c = captcha_generate();
echo json_encode(['question' => $c['question'], 'token' => $c['token']]);
