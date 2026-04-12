<?php
require_once 'db.php';

// Pagination
$per_page    = 6;
$page        = max(1, (int)($_GET['page'] ?? 1));
$offset      = ($page - 1) * $per_page;

// Category filter
$category    = trim($_GET['cat'] ?? '');

// Build query
if ($category !== '') {
    $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE status = 'published' AND category = ?");
    $total_stmt->execute([$category]);
    $posts_stmt = $pdo->prepare("SELECT id, title, slug, category, excerpt, image_url, created_at FROM posts WHERE status = 'published' AND category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $posts_stmt->bindValue(1, $category,  PDO::PARAM_STR);
    $posts_stmt->bindValue(2, $per_page,  PDO::PARAM_INT);
    $posts_stmt->bindValue(3, $offset,    PDO::PARAM_INT);
    $posts_stmt->execute();
} else {
    $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE status = 'published'");
    $total_stmt->execute();
    $posts_stmt = $pdo->prepare("SELECT id, title, slug, category, excerpt, image_url, created_at FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $posts_stmt->bindValue(1, $per_page, PDO::PARAM_INT);
    $posts_stmt->bindValue(2, $offset,   PDO::PARAM_INT);
    $posts_stmt->execute();
}

$total_posts = (int)$total_stmt->fetchColumn();
$posts       = $posts_stmt->fetchAll(PDO::FETCH_ASSOC);
$total_pages = (int)ceil($total_posts / $per_page);

// All categories for filter bar
$cats_stmt = $pdo->query("SELECT DISTINCT category FROM posts WHERE status = 'published' ORDER BY category ASC");
$categories = $cats_stmt->fetchAll(PDO::FETCH_COLUMN);

function fmt_date(string $date): string {
    return date('M j, Y', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog | HariGopinath.com</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css">
</head>
<body>

<?php require 'includes/nav.php'; ?>

<!-- BLOG HERO -->
<section class="blog-hero position-relative">
  <div class="container">
    <div class="tag text-orange" style="background:rgba(255,122,89,0.1); border-color:var(--orange);">The Growth Signal</div>
    <h1>Growth Marketing Blog</h1>
    <p>Actionable performance marketing strategies, SEO breakdowns, and real revenue frameworks for ambitious brands.</p>
  </div>
</section>

<!-- POSTS -->
<section style="padding: 4rem 0 8rem; background: var(--gray-bg);">
  <div class="container">

    <?php if (!empty($categories)): ?>
    <!-- Category Filter -->
    <div class="cat-filter">
      <a href="blog.php" class="cat-pill<?= $category === '' ? ' active' : '' ?>">All</a>
      <?php foreach ($categories as $c): ?>
        <a href="blog.php?cat=<?= urlencode($c) ?>" class="cat-pill<?= $category === $c ? ' active' : '' ?>"><?= htmlspecialchars($c) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
      <div style="text-align:center; padding: 4rem 0; color: var(--text-light);">
        <p style="font-size:1.2rem;">No posts found<?= $category ? ' in "' . htmlspecialchars($category) . '"' : '' ?>.</p>
        <a href="blog.php" class="btn btn-primary" style="margin-top:1.5rem;">View All Posts</a>
      </div>
    <?php else: ?>
    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
      <a href="single-post.php?slug=<?= urlencode($post['slug']) ?>" class="blog-card">
        <div class="blog-image" style="background-image: url('<?= htmlspecialchars($post['image_url'] ?: '') ?>');"></div>
        <div class="blog-content">
          <span class="blog-tag"><?= htmlspecialchars($post['category']) ?></span>
          <h2 class="blog-title"><?= htmlspecialchars($post['title']) ?></h2>
          <p class="blog-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
          <div class="blog-meta">
            <span><?= fmt_date($post['created_at']) ?></span>
            <span class="read-more">Read Article &rarr;</span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="blog.php?page=<?= $page - 1 ?><?= $category ? '&cat=' . urlencode($category) : '' ?>" class="page-btn">&larr; Prev</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="blog.php?page=<?= $i ?><?= $category ? '&cat=' . urlencode($category) : '' ?>" class="page-btn<?= $i === $page ? ' active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <a href="blog.php?page=<?= $page + 1 ?><?= $category ? '&cat=' . urlencode($category) : '' ?>" class="page-btn">Next &rarr;</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section section-navy" style="padding-top: 4rem; padding-bottom: 4rem;">
  <div class="container grid-2 align-center">
    <div class="nl-text">
      <div class="tag text-orange">Pulse Intelligence</div>
      <h2 style="font-size: 2.5rem; color: #fff;">Join 1,000+ Growth Marketers.</h2>
      <p class="opacity-70" style="color: #fff;">Deep-dive growth strategies and market attribution insights delivered to your inbox every fortnight. No fluff. Just data.</p>
    </div>
    <div class="nl-form">
      <form class="d-flex">
        <input type="email" placeholder="Enter your work email" required class="nl-input">
        <button type="submit" class="btn btn-orange">Subscribe Free</button>
      </form>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>

<script src="particles.js"></script>
</body>
</html>
