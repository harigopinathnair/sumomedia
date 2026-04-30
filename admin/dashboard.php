<?php
require_once 'auth.php';

// Stats - Posts
$posts_pub   = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();
$posts_draft = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='draft'")->fetchColumn();

// Stats - CRM
$leads_total = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$leads_new   = $pdo->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn();
$pipe_val    = $pdo->query("SELECT SUM(value) FROM leads WHERE status IN ('new','engaged','proposal_sent')")->fetchColumn() ?: 0;

// Stats - Subscribers
$subs_total  = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();

// Stats - Chats
$chats_open  = $pdo->query("SELECT COUNT(*) FROM chat_sessions WHERE status='active'")->fetchColumn();

function fmt_inr($v) {
    if ($v >= 100000) return '₹' . number_format($v / 100000, 1) . 'L';
    if ($v >= 1000)   return '₹' . number_format($v / 1000, 1) . 'K';
    return '₹' . number_format($v, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<style>
.dash-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}
.dash-card {
  background: #fff;
  border: 1px solid var(--gray-border);
  border-radius: 12px;
  padding: 1.8rem;
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
  display: flex;
  flex-direction: column;
  position: relative;
  overflow: hidden;
}
.dash-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 4px;
  background: var(--navy);
  opacity: 0.8;
}
.dash-card.card-orange::before { background: var(--orange); }
.dash-card.card-purple::before { background: #8b5cf6; }
.dash-card.card-green::before  { background: #22c55e; }

.dash-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
  border-color: rgba(0,0,0,0.15);
}
.dash-card h3 {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--text-light);
  margin-bottom: 0.8rem;
}
.dash-card .dash-value {
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--text-dark);
  line-height: 1.1;
  margin-bottom: 0.5rem;
}
.dash-card .dash-sub {
  font-size: 0.88rem;
  color: var(--text-light);
}
.dash-card .dash-link {
  margin-top: 1.5rem;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--orange);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}
</style>
</head>
<body>

<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo"><img src="../dark_logo.png" alt="SumoMedia" style="max-width: 150px; height: auto;"></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="nav-item active">Dashboard</a>
      <a href="posts.php" class="nav-item">Posts</a>
      <a href="crm.php" class="nav-item">CRM</a>
      <a href="chats.php" class="nav-item">Live Chats</a>
      <a href="subscribers.php" class="nav-item">Subscribers</a>
      <a href="settings.php" class="nav-item">Settings</a>
      <a href="../index.php" class="nav-item" target="_blank">View Site</a>
    </nav>
    <div class="sidebar-footer">
      <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <!-- Main -->
  <main class="admin-main">
    <div class="page-header">
      <div>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h1>
        <p class="page-sub">Here's what's happening across SumoMedia today.</p>
      </div>
    </div>

    <div class="dash-grid">
      <!-- CRM -->
      <a href="crm.php" class="dash-card card-purple">
        <h3>Pipeline Value</h3>
        <div class="dash-value"><?= fmt_inr($pipe_val) ?></div>
        <div class="dash-sub"><?= $leads_total ?> total leads &middot; <?= $leads_new ?> new</div>
        <div class="dash-link">Manage Leads &rarr;</div>
      </a>

      <!-- Posts -->
      <a href="posts.php" class="dash-card card-orange">
        <h3>Content</h3>
        <div class="dash-value"><?= $posts_pub ?></div>
        <div class="dash-sub">Published articles &middot; <?= $posts_draft ?> drafts</div>
        <div class="dash-link">Edit Posts &rarr;</div>
      </a>

      <!-- Subscribers -->
      <a href="subscribers.php" class="dash-card card-green">
        <h3>Subscribers</h3>
        <div class="dash-value"><?= $subs_total ?></div>
        <div class="dash-sub">Total newsletter growth</div>
        <div class="dash-link">View List &rarr;</div>
      </a>

      <!-- Live Chat -->
      <a href="chats.php" class="dash-card">
        <h3>Live Support</h3>
        <div class="dash-value"><?= $chats_open ?></div>
        <div class="dash-sub">Active chat sessions</div>
        <div class="dash-link">Open Chats &rarr;</div>
      </a>
    </div>

  </main>

</div>

</body>
</html>
