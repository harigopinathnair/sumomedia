<?php
require_once 'db.php';
require_once 'includes/tracking.php';
require_once 'includes/captcha.php';

// ── Author settings ────────────────────────────────────────────────────────
function sp_get_setting(PDO $pdo, string $key, string $default = ''): string {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = ? LIMIT 1");
    $stmt->execute([$key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (string)$row['value'] : $default;
}
// Only load if settings table exists
$author_name  = 'Hari Gopinath';
$author_title = 'Growth Strategist & SEO Engineer';
$author_bio   = '';
$author_photo = '';
try {
    $author_name     = sp_get_setting($pdo, 'author_name',     'Hari Gopinath');
    $author_title    = sp_get_setting($pdo, 'author_title',    'Growth Strategist & SEO Engineer');
    $author_bio      = sp_get_setting($pdo, 'author_bio',      '');
    $author_photo    = sp_get_setting($pdo, 'author_photo',    '');
    $author_email    = sp_get_setting($pdo, 'author_email',    'contact@sumomedia.in');
    $author_linkedin = sp_get_setting($pdo, 'author_linkedin', '');
    $author_telegram = sp_get_setting($pdo, 'author_telegram', '');
} catch (Exception $e) { /* settings table may not exist yet */ }

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
<?php $_proto2 = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'; ?>
<base href="<?= htmlspecialchars($_proto2 . '://' . $_SERVER['HTTP_HOST'] . BASE_PATH . '/') ?>">
<title>Post Not Found | SumoMedia.in</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css?v=4">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<?= $custom_code_head ?? '' ?>
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
<?php
$_proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_base    = $_proto . '://' . $_SERVER['HTTP_HOST'] . BASE_PATH . '/';
?>
<base href="<?= htmlspecialchars($_base) ?>">
<title><?= htmlspecialchars($post['title']) ?> | SumoMedia.in</title>
<meta name="description" content="<?= htmlspecialchars($post['excerpt']) ?>">
<link rel="canonical" href="https://sumomedia.in/blog/<?= htmlspecialchars($post['slug']) ?>">
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css?v=4">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<?= $custom_code_head ?? '' ?>
</head>
<body>

<?php require 'includes/nav.php'; ?>

<!-- POST HERO / FEATURED IMAGE -->
<section class="post-featured-image" style="background-image: linear-gradient(rgba(31, 46, 80, 0.5), rgba(31, 46, 80, 0.85)), url('blog-bg.jpg'); background-size: cover; background-position: center; padding: 3rem 0 2.5rem; text-align: center; border-bottom: 1px solid var(--gray-border);">
  <div class="container">
    <a href="blog.php?cat=<?= urlencode($post['category']) ?>" class="tag text-orange mb-3" style="background:rgba(255,122,89,0.1); border-color:var(--orange); display:inline-block;"><?= htmlspecialchars($post['category']) ?></a>
    <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.15; margin-bottom: 1.5rem; letter-spacing: -0.02em; max-width: 800px; margin-left: auto; margin-right: auto; color: #fff;"><?= htmlspecialchars($post['title']) ?></h1>
    <div class="post-meta" style="justify-content: center; color: rgba(255,255,255,0.85); margin-top: 2rem;">
      <div style="width:40px; height:40px; background:var(--orange); border-radius:50%; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:1rem; flex-shrink:0;">HG</div>
      <div style="text-align: left;">
        <strong style="color: #fff;">Hari Gopinath</strong><br>
        <span style="font-size:0.85rem;"><?= fmt_date($post['created_at']) ?> &middot; <?= $read_time ?> min read</span>
      </div>
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
        <a href="<?= BASE_PATH ?>/blog/<?= urlencode($prev_post['slug']) ?>" class="post-nav-link">
          <span class="post-nav-label">&larr; Previous</span>
          <span class="post-nav-title"><?= htmlspecialchars($prev_post['title']) ?></span>
        </a>
      <?php endif; ?>
    </div>
    <div style="text-align:right;">
      <?php if ($next_post): ?>
        <a href="<?= BASE_PATH ?>/blog/<?= urlencode($next_post['slug']) ?>" class="post-nav-link">
          <span class="post-nav-label">Next &rarr;</span>
          <span class="post-nav-title"><?= htmlspecialchars($next_post['title']) ?></span>
        </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</article>

<!-- AUTHOR BIO -->
<?php if ($author_bio || $author_name): ?>
<section class="author-bio-section">
  <div class="container">
    <div class="author-bio-card">
      <div class="author-bio-accent"></div>
      <div class="author-bio-inner">
        <div class="author-bio-photo-wrap">
          <?php if ($author_photo): ?>
            <img src="<?= htmlspecialchars($author_photo) ?>" alt="<?= htmlspecialchars($author_name) ?>" class="author-bio-photo">
          <?php else: ?>
            <div class="author-bio-avatar">HG</div>
          <?php endif; ?>
          <div class="author-bio-photo-ring"></div>
        </div>
        <div class="author-bio-body">
          <span class="author-bio-label">Written by</span>
          <h3 class="author-bio-name"><?= htmlspecialchars($author_name) ?></h3>
          <?php if ($author_title): ?>
            <p class="author-bio-title"><?= htmlspecialchars($author_title) ?></p>
          <?php endif; ?>
          <?php if ($author_bio): ?>
            <div class="author-bio-divider"></div>
            <p class="author-bio-text"><?= nl2br(htmlspecialchars($author_bio)) ?></p>
          <?php endif; ?>

          <div class="author-bio-ctas">
            <?php if ($author_linkedin): ?>
            <a href="<?= htmlspecialchars($author_linkedin) ?>" target="_blank" rel="noopener" class="author-cta author-cta--linkedin">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              Connect on LinkedIn
            </a>
            <?php endif; ?>

            <?php if ($author_email): ?>
            <a href="mailto:<?= htmlspecialchars($author_email) ?>" class="author-cta author-cta--email">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              Email Me
            </a>
            <?php endif; ?>

            <?php if ($author_telegram): ?>
            <a href="<?= htmlspecialchars($author_telegram) ?>" target="_blank" rel="noopener" class="author-cta author-cta--telegram">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
              Chat on Telegram
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- RELATED POSTS -->
<?php if (!empty($related)): ?>
<section style="padding: 4rem 0 6rem; background: var(--gray-bg);">
  <div class="container">
    <h3 style="font-size:1.8rem; margin-bottom:2.5rem; color:var(--text-dark);">More in <?= htmlspecialchars($post['category']) ?></h3>
    <div class="blog-grid" style="grid-template-columns: repeat(<?= min(count($related), 3) ?>, 1fr);">
      <?php foreach ($related as $r): ?>
      <a href="<?= BASE_PATH ?>/blog/<?= urlencode($r['slug']) ?>" class="blog-card">
        <div class="blog-image" style="background-image: linear-gradient(rgba(31, 46, 80, 0.4), rgba(31, 46, 80, 0.8)), url('blog-bg.jpg'); display: flex; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
          <h3 style="color: #fff; font-size: 1.25rem; margin: 0; font-family: var(--font-display); line-height: 1.3; text-shadow: 0 2px 4px rgba(0,0,0,0.5); font-weight: 700;"><?= htmlspecialchars($r['title']) ?></h3>
        </div>
        <div class="blog-content">
          <span class="blog-tag"><?= htmlspecialchars($r['category']) ?></span>
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
<section class="newsletter-section section-gray" style="padding-top: 4rem; padding-bottom: 4rem;">
  <div class="container grid-2 align-center">
    <div class="nl-text">
      <div class="tag text-orange">Pulse Intelligence</div>
      <h2 style="font-size: 2.5rem;">Join 1,000+ Growth Marketers.</h2>
      <p>Deep-dive growth strategies delivered to your inbox every fortnight. No fluff. Just data.</p>
    </div>
    <div class="nl-form">
      <form class="nl-form-post" id="form-nl-post" method="POST" action="newsletter.php">
        <div class="form-inline-msg" id="msg-nl-post"></div>
        <div class="d-flex" style="margin-bottom:0.6rem;">
          <input type="email" name="email" placeholder="Enter your work email" required class="nl-input" style="margin-bottom:0;">
          <input type="hidden" name="source"       value="post-footer">
          <input type="hidden" name="page_url"     class="nl-page_url"     value="">
          <input type="hidden" name="utm_source"   class="nl-utm_source"   value="">
          <input type="hidden" name="utm_medium"   class="nl-utm_medium"   value="">
          <input type="hidden" name="utm_campaign" class="nl-utm_campaign" value="">
          <input type="hidden" name="utm_content"  class="nl-utm_content"  value="">
          <input type="hidden" name="utm_term"     class="nl-utm_term"     value="">
          <button type="submit" class="btn btn-orange">Subscribe Free</button>
        </div>
        <?= captcha_html(true) ?>
      </form>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>

<script src="particles.js"></script>
<script>
(function () {
  const params  = new URLSearchParams(window.location.search);
  const utmKeys = ['source', 'medium', 'campaign', 'content', 'term'];
  utmKeys.forEach(k => { const v = params.get('utm_' + k); if (v) sessionStorage.setItem('utm_' + k, v); });
  if (params.toString()) sessionStorage.setItem('utm_url', window.location.href);

  document.querySelectorAll('[class*="nl-page_url"]').forEach(el    => el.value = sessionStorage.getItem('utm_url')      || window.location.href);
  document.querySelectorAll('[class*="nl-utm_source"]').forEach(el   => el.value = sessionStorage.getItem('utm_source')   || '');
  document.querySelectorAll('[class*="nl-utm_medium"]').forEach(el   => el.value = sessionStorage.getItem('utm_medium')   || '');
  document.querySelectorAll('[class*="nl-utm_campaign"]').forEach(el => el.value = sessionStorage.getItem('utm_campaign') || '');
  document.querySelectorAll('[class*="nl-utm_content"]').forEach(el  => el.value = sessionStorage.getItem('utm_content')  || '');
  document.querySelectorAll('[class*="nl-utm_term"]').forEach(el     => el.value = sessionStorage.getItem('utm_term')     || '');
})();
</script>

<script>
(function () {
  function showMsg(el, type, html) {
    el.className = 'form-inline-msg is-' + type;
    el.innerHTML = html;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  const form = document.getElementById('form-nl-post');
  const msg  = document.getElementById('msg-nl-post');
  if (form && msg) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      msg.style.display = 'none';
      const btn = form.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.disabled = true; btn.innerHTML = 'Subscribing…';

      try {
        const res  = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        if (data.ok) {
          showMsg(msg, 'success', "You're in! Expect sharp growth insights every fortnight.");
          form.reset();
        } else {
          showMsg(msg, 'error', data.error || 'Something went wrong. Please try again.');
        }
      } catch {
        showMsg(msg, 'error', 'Network error. Please try again.');
      }
      btn.disabled = false; btn.innerHTML = orig;
    });
  }
})();
</script>
</body>
</html>
