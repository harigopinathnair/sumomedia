<?php
require_once 'auth.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);

if (!$id) { echo json_encode(['ok' => false, 'error' => 'Invalid ID']); exit; }

$valid_statuses = ['new', 'engaged', 'proposal_sent', 'won', 'lost'];

if ($action === 'move') {
    $status = $_POST['status'] ?? '';
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid status']); exit;
    }
    $pdo->prepare("UPDATE leads SET status=? WHERE id=?")->execute([$status, $id]);
    echo json_encode(['ok' => true]);

} elseif ($action === 'save') {
    $value   = max(0, (float)($_POST['value']   ?? 0));
    $comment = trim($_POST['comment'] ?? '');
    $pdo->prepare("UPDATE leads SET value=?, comment=? WHERE id=?")->execute([$value, $comment, $id]);
    echo json_encode(['ok' => true]);

} elseif ($action === 'delete') {
    $pdo->prepare("DELETE FROM leads WHERE id=?")->execute([$id]);
    echo json_encode(['ok' => true]);

} else {
    echo json_encode(['ok' => false, 'error' => 'Unknown action']);
}
