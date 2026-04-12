<?php
require_once 'auth.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Stats
$total_published = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();
$total_draft     = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='draft'")->fetchColumn();

// Posts list
$posts = $pdo->query("SELECT id, title, slug, category, status, created_at, updated_at FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

function status_badge(string $s): string {
    return $s === 'published'
        ? '<span class="badge badge-published">Published</span>'
        : '<span class="badge badge-draft">Draft</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Posts | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">HG<span>Admin</span></div>
    <nav class="sidebar-nav">
      <a href="posts.php" class="nav-item active">Posts</a>
      <a href="post-form.php" class="nav-item">New Post</a>
      <a href="crm.php" class="nav-item">CRM</a>
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
        <h1>Posts</h1>
        <p class="page-sub"><?= $total_published ?> published &middot; <?= $total_draft ?> draft</p>
      </div>
      <a href="post-form.php" class="btn-primary">+ New Post</a>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['msg']) ?></div>
    <?php endif; ?>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($posts)): ?>
          <tr><td colspan="5" class="empty-row">No posts yet. <a href="post-form.php">Create your first post.</a></td></tr>
          <?php else: ?>
          <?php foreach ($posts as $p): ?>
          <tr>
            <td>
              <a href="../single-post.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" class="post-title-link"><?= htmlspecialchars($p['title']) ?></a>
              <div class="post-slug">/<?= htmlspecialchars($p['slug']) ?></div>
            </td>
            <td><?= htmlspecialchars($p['category']) ?></td>
            <td><?= status_badge($p['status']) ?></td>
            <td><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
            <td class="actions">
              <a href="post-form.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Edit</a>
              <a href="post-delete.php?id=<?= $p['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete this post? This cannot be undone.')">Delete</a>
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
