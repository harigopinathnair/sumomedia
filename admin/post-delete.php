<?php
require_once 'auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: posts.php');
    exit;
}

$stmt = $pdo->prepare("SELECT title FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Post not found.'];
    header('Location: posts.php');
    exit;
}

$del = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$del->execute([$id]);

$_SESSION['flash'] = ['type' => 'success', 'msg' => "Post \"{$post['title']}\" deleted."];
header('Location: posts.php');
exit;
