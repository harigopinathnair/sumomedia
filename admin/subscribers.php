<?php
require_once 'auth.php';

// Auto-create table if missing
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

// Handle CSV export
if (isset($_GET['export'])) {
    $subs = $pdo->query("SELECT * FROM subscribers ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, ['ID', 'Email', 'Status', 'Source', 'Page URL', 'UTM Source', 'UTM Medium', 'UTM Campaign', 'UTM Content', 'UTM Term', 'Date']);
    foreach ($subs as $s) {
        fputcsv($out, [
            $s['id'], $s['email'], $s['status'], $s['source'],
            $s['page_url'] ?? '', $s['utm_source'] ?? '', $s['utm_medium'] ?? '',
            $s['utm_campaign'] ?? '', $s['utm_content'] ?? '', $s['utm_term'] ?? '',
            date('d M Y H:i', strtotime($s['created_at'])),
        ]);
    }
    fclose($out);
    exit;
}

// Handle unsubscribe / reactivate / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid    = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($sid) {
        if ($action === 'unsubscribe') {
            $pdo->prepare("UPDATE subscribers SET status='unsubscribed' WHERE id=?")->execute([$sid]);
        } elseif ($action === 'reactivate') {
            $pdo->prepare("UPDATE subscribers SET status='active' WHERE id=?")->execute([$sid]);
        } elseif ($action === 'delete') {
            $pdo->prepare("DELETE FROM subscribers WHERE id=?")->execute([$sid]);
        }
    }
    header('Location: subscribers.php');
    exit;
}

// Stats
$total      = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
$active     = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();
$unsub      = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='unsubscribed'")->fetchColumn();

// Filter
$filter = $_GET['status'] ?? 'all';
if ($filter === 'active') {
    $subs = $pdo->query("SELECT * FROM subscribers WHERE status='active' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} elseif ($filter === 'unsubscribed') {
    $subs = $pdo->query("SELECT * FROM subscribers WHERE status='unsubscribed' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $subs = $pdo->query("SELECT * FROM subscribers ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscribers | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<style>
.sub-widgets { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem; }
.sub-widget  { background:#fff; border:1px solid var(--gray-border); border-radius:10px; padding:1.25rem 1.4rem; border-top:3px solid var(--gray-border); }
.sub-widget.w-total  { border-top-color: var(--navy); }
.sub-widget.w-active { border-top-color: #22c55e; }
.sub-widget.w-unsub  { border-top-color: #ef4444; }
.widget-label { font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:var(--text-light); margin-bottom:0.5rem; }
.widget-value { font-size:2rem; font-weight:700; font-family:var(--font-h); color:var(--text-dark); }

.filter-bar  { display:flex; align-items:center; gap:0.5rem; margin-bottom:1.2rem; }
.filter-btn  { padding:0.4rem 1rem; border:1px solid var(--gray-border); border-radius:20px; font-size:0.82rem; font-weight:600; color:var(--text-light); background:#fff; cursor:pointer; text-decoration:none; transition:all 0.15s; }
.filter-btn:hover, .filter-btn.active { border-color:var(--orange); color:var(--orange); background:#fff5f2; }

.sub-email  { font-weight:600; color:var(--text-dark); }
.sub-source { font-size:0.78rem; color:var(--text-light); margin-top:0.15rem; }
.status-active { color:#22c55e; font-weight:700; font-size:0.82rem; }
.status-unsub  { color:#ef4444; font-weight:700; font-size:0.82rem; }
.sub-actions form { display:inline; }
.sub-actions button { background:none; border:none; cursor:pointer; font-size:0.82rem; font-weight:600; padding:0.3rem 0.6rem; border-radius:4px; font-family:var(--font-b); transition:all 0.15s; }
.btn-unsub   { color:#b91c1c; border:1px solid #fca5a5 !important; border:1px solid; }
.btn-unsub:hover { background:#fee2e2; }
.btn-reactivate { color:#15803d; border:1px solid #86efac !important; border:1px solid; }
.btn-reactivate:hover { background:#f0fdf4; }
.btn-del     { color:#6b7280; border:1px solid var(--gray-border) !important; border:1px solid; }
.btn-del:hover { background:#f3f4f6; }

.export-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.2rem; background:#fff; border:1px solid var(--gray-border); border-radius:6px; font-size:0.88rem; font-weight:600; color:var(--text-dark); text-decoration:none; transition:all 0.15s; }
.export-btn:hover { border-color:var(--navy); background:var(--navy); color:#fff; }
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<div class="admin-layout">

  <aside class="sidebar">
    <div class="sidebar-logo"><img src="../logo.png" alt="SumoMedia" style="max-width: 150px; height: auto;"></div>
    <nav class="sidebar-nav">
      <a href="posts.php" class="nav-item">Posts</a>
      <a href="post-form.php" class="nav-item">New Post</a>
      <a href="crm.php" class="nav-item">CRM</a>
      <a href="subscribers.php" class="nav-item active">Subscribers</a>
      <a href="settings.php" class="nav-item">Settings</a>
      <a href="../index.php" class="nav-item" target="_blank">View Site</a>
    </nav>
    <div class="sidebar-footer">
      <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="admin-main">

    <div class="page-header">
      <div>
        <h1>Newsletter Subscribers</h1>
        <p class="page-sub"><?= $active ?> active &middot; <?= $unsub ?> unsubscribed</p>
      </div>
      <a href="subscribers.php?export=1" class="export-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export CSV
      </a>
    </div>

    <!-- Widgets -->
    <div class="sub-widgets">
      <div class="sub-widget w-total">
        <div class="widget-label">Total Subscribers</div>
        <div class="widget-value"><?= $total ?></div>
      </div>
      <div class="sub-widget w-active">
        <div class="widget-label">Active</div>
        <div class="widget-value"><?= $active ?></div>
      </div>
      <div class="sub-widget w-unsub">
        <div class="widget-label">Unsubscribed</div>
        <div class="widget-value"><?= $unsub ?></div>
      </div>
    </div>

    <!-- Filter -->
    <div class="filter-bar">
      <a href="subscribers.php"                    class="filter-btn <?= $filter === 'all'          ? 'active' : '' ?>">All (<?= $total ?>)</a>
      <a href="subscribers.php?status=active"      class="filter-btn <?= $filter === 'active'       ? 'active' : '' ?>">Active (<?= $active ?>)</a>
      <a href="subscribers.php?status=unsubscribed" class="filter-btn <?= $filter === 'unsubscribed' ? 'active' : '' ?>">Unsubscribed (<?= $unsub ?>)</a>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Email</th>
            <th>Source</th>
            <th>UTM</th>
            <th>Page URL</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($subs)): ?>
          <tr><td colspan="7" class="empty-row">No subscribers yet.</td></tr>
          <?php else: ?>
          <?php foreach ($subs as $s): ?>
          <?php
            $utm_parts = array_filter([
              $s['utm_source']   ?? '' ? 'src: '  . $s['utm_source']   : '',
              $s['utm_medium']   ?? '' ? 'med: '  . $s['utm_medium']   : '',
              $s['utm_campaign'] ?? '' ? 'cmp: '  . $s['utm_campaign'] : '',
              $s['utm_content']  ?? '' ? 'cnt: '  . $s['utm_content']  : '',
              $s['utm_term']     ?? '' ? 'term: ' . $s['utm_term']     : '',
            ]);
          ?>
          <tr>
            <td>
              <div class="sub-email"><?= htmlspecialchars($s['email']) ?></div>
            </td>
            <td><span style="font-size:0.82rem;color:var(--text-light);"><?= htmlspecialchars($s['source'] ?? '') ?></span></td>
            <td style="font-size:0.78rem;color:var(--text-light);max-width:160px;">
              <?= $utm_parts ? nl2br(htmlspecialchars(implode("\n", $utm_parts))) : '<span style="opacity:0.4;">—</span>' ?>
            </td>
            <td style="font-size:0.78rem;max-width:180px;word-break:break-all;">
              <?php if (!empty($s['page_url'])): ?>
                <a href="<?= htmlspecialchars($s['page_url']) ?>" target="_blank" title="<?= htmlspecialchars($s['page_url']) ?>" style="color:var(--navy);text-decoration:none;">
                  <?= htmlspecialchars(parse_url($s['page_url'], PHP_URL_PATH) ?: $s['page_url']) ?>
                </a>
              <?php else: ?>
                <span style="opacity:0.4;">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($s['status'] === 'active'): ?>
                <span class="status-active">● Active</span>
              <?php else: ?>
                <span class="status-unsub">● Unsubscribed</span>
              <?php endif; ?>
            </td>
            <td style="font-size:0.82rem;color:var(--text-light);"><?= date('d M Y', strtotime($s['created_at'])) ?></td>
            <td class="sub-actions">
              <?php if ($s['status'] === 'active'): ?>
              <form method="POST">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <input type="hidden" name="action" value="unsubscribe">
                <button class="btn-unsub" title="Unsubscribe">Unsubscribe</button>
              </form>
              <?php else: ?>
              <form method="POST">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <input type="hidden" name="action" value="reactivate">
                <button class="btn-reactivate" title="Reactivate">Reactivate</button>
              </form>
              <?php endif; ?>
              <form method="POST" onsubmit="return confirm('Delete this subscriber permanently?')">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button class="btn-del">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>

</body>
</html>
