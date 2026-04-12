<?php
session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: posts.php');
    exit;
}

require_once __DIR__ . '/../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username']  = $user['username'];
            header('Location: posts.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | HariGopinath.com</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">HariGopinath<span>.com</span></div>
  <h2>Admin Panel</h2>
  <p class="login-sub">Sign in to manage posts</p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="field">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username" required autofocus>
    </div>
    <div class="field">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="btn-submit">Sign In &rarr;</button>
  </form>

  <a href="../index.php" class="back-link">&larr; Back to website</a>
</div>

</body>
</html>
