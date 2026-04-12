<?php
require_once 'db.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
    header('Location: blog.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post Not Found | HariGopinath.com</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css">
</head>
<body>
<?php require 'includes/nav.php'; ?>
<section style="padding:8rem 0; text-align:center;">
  <div class="container">
    <h1 style="font-size:5rem; color:var(--gray-border);">404</h1>
    <h2 style="margin-bottom:1rem;">Post not found</h2>
    <p style="color:var(--text-light); margin-bottom:2rem;">The article you are looking for doesn't exist or has been removed.</p>
    <a href="blog.php" class="btn btn-primary">Back to Blog</a>
  </div>
</section>
</body>
</html>
    <?php
    exit;
}

// Fetch prev/next posts for navigation
$prev_stmt = $pdo->prepare("SELECT title, slug FROM posts WHERE status = 'published' AND created_at < ? ORDER BY created_at DESC LIMIT 1");
$prev_stmt->execute([$post['created_at']]);
$prev_post = $prev_stmt->fetch(PDO::FETCH_ASSOC);

$next_stmt = $pdo->prepare("SELECT title, slug FROM posts WHERE status = 'published' AND created_at > ? ORDER BY created_at ASC LIMIT 1");
$next_stmt->execute([$post['created_at']]);
$next_post = $next_stmt->fetch(PDO::FETCH_ASSOC);

// Related posts (same category, exclude current)
$related_stmt = $pdo->prepare("SELECT title, slug, category, excerpt, image_url, created_at FROM posts WHERE status = 'published' AND category = ? AND slug != ? ORDER BY created_at DESC LIMIT 3");
$related_stmt->execute([$post['category'], $post['slug']]);
$related = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

function fmt_date(string $date): string {
    return date('M j, Y', strtotime($date));
}

// Estimate read time (~200 words/min)
$word_count = str_word_count(strip_tags($post['content']));
$read_time  = max(1, (int)round($word_count / 200));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($post['title']) ?> | HariGopinath.com</title>
<meta name="description" content="<?= htmlspecialchars($post['excerpt']) ?>">
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css">
</head>
<body>

<?php require 'includes/nav.php'; ?>

<!-- POST HERO -->
<section class="post-hero">
  <div class="container post-hero-split">
    <div style="text-align: left;">
      <a href="blog.php?cat=<?= urlencode($post['category']) ?>" class="tag text-orange mb-3" style="background:rgba(255,122,89,0.1); border-color:var(--orange);"><?= htmlspecialchars($post['category']) ?></a>
      <h1 style="font-size: clamp(2rem, 4vw, 3.2rem); line-height: 1.15; margin-bottom: 2rem; color: #fff; letter-spacing: -0.02em;"><?= htmlspecialchars($post['title']) ?></h1>
      <div class="post-meta">
        <div style="width:40px; height:40px; background:var(--orange); border-radius:50%; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:1rem; flex-shrink:0;">HG</div>
        <div>
          <strong style="color:#fff;">Hari Gopinath</strong><br>
          <span style="font-size:0.85rem; opacity:0.8;"><?= fmt_date($post['created_at']) ?> &middot; <?= $read_time ?> min read</span>
        </div>
      </div>
    </div>
    <div class="hero-form-box" style="padding: 2rem; text-align: left;">
      <h3 style="font-size: 1.35rem; margin-bottom: 0.5rem;">Never Miss An Update</h3>
      <p style="margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.5;">Join 1,000+ growth marketers receiving raw strategies.</p>
      <form class="audit-form">
        <input type="text" placeholder="First Name" required style="margin-bottom: 10px;">
        <input type="email" placeholder="Work Email" required style="margin-bottom: 15px;">
        <button type="submit" class="btn btn-orange" style="width: 100%;">Subscribe Free</button>
      </form>
    </div>
  </div>
</section>

<!-- ARTICLE BODY -->
<article class="article-container">
  <div class="article-content">
    <?= $post['content'] ?>
  </div>

  <!-- Post Navigation -->
  <?php if ($prev_post || $next_post): ?>
  <div class="post-nav">
    <div>
      <?php if ($prev_post): ?>
        <a href="single-post.php?slug=<?= urlencode($prev_post['slug']) ?>" class="post-nav-link">
          <span class="post-nav-label">&larr; Previous</span>
          <span class="post-nav-title"><?= htmlspecialchars($prev_post['title']) ?></span>
        </a>
      <?php endif; ?>
    </div>
    <div style="text-align:right;">
      <?php if ($next_post): ?>
        <a href="single-post.php?slug=<?= urlencode($next_post['slug']) ?>" class="post-nav-link">
          <span class="post-nav-label">Next &rarr;</span>
          <span class="post-nav-title"><?= htmlspecialchars($next_post['title']) ?></span>
        </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</article>

<!-- RELATED POSTS -->
<?php if (!empty($related)): ?>
<section style="padding: 4rem 0 6rem; background: var(--gray-bg);">
  <div class="container">
    <h3 style="font-size:1.8rem; margin-bottom:2.5rem; color:var(--text-dark);">More in <?= htmlspecialchars($post['category']) ?></h3>
    <div class="blog-grid" style="grid-template-columns: repeat(<?= min(count($related), 3) ?>, 1fr);">
      <?php foreach ($related as $r): ?>
      <a href="single-post.php?slug=<?= urlencode($r['slug']) ?>" class="blog-card">
        <div class="blog-image" style="background-image: url('<?= htmlspecialchars($r['image_url'] ?: '') ?>');"></div>
        <div class="blog-content">
          <span class="blog-tag"><?= htmlspecialchars($r['category']) ?></span>
          <h2 class="blog-title"><?= htmlspecialchars($r['title']) ?></h2>
          <p class="blog-excerpt"><?= htmlspecialchars($r['excerpt']) ?></p>
          <div class="blog-meta">
            <span><?= fmt_date($r['created_at']) ?></span>
            <span class="read-more">Read Article &rarr;</span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center; margin-top:3rem;">
      <a href="blog.php" class="btn btn-outline">View All Articles &rarr;</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- NEWSLETTER -->
<section class="newsletter-section section-navy" style="padding-top: 4rem; padding-bottom: 4rem;">
  <div class="container grid-2 align-center">
    <div class="nl-text">
      <div class="tag text-orange">Pulse Intelligence</div>
      <h2 style="font-size: 2.5rem; color: #fff;">Join 1,000+ Growth Marketers.</h2>
      <p class="opacity-70" style="color: #fff;">Deep-dive growth strategies delivered to your inbox every fortnight. No fluff. Just data.</p>
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
