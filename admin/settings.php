<?php
require_once 'auth.php';

// ── Auto-create settings table ─────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS settings (
    id         INT(11)      AUTO_INCREMENT PRIMARY KEY,
    `key`      VARCHAR(100) NOT NULL UNIQUE,
    value      TEXT,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// ── Upload dir ─────────────────────────────────────────────────────────────
$upload_dir = __DIR__ . '/../uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// ── Helper: get setting ────────────────────────────────────────────────────
function get_setting(PDO $pdo, string $key, string $default = ''): string {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = ? LIMIT 1");
    $stmt->execute([$key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (string)$row['value'] : $default;
}

function save_setting(PDO $pdo, string $key, string $value): void {
    $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)")
        ->execute([$key, $value]);
}

$flash  = '';
$errors = [];

// ── Handle form submission ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author_name     = trim($_POST['author_name']     ?? '');
    $author_title    = trim($_POST['author_title']    ?? '');
    $author_bio      = trim($_POST['author_bio']      ?? '');
    $author_email    = trim($_POST['author_email']    ?? '');
    $author_linkedin = trim($_POST['author_linkedin'] ?? '');
    $author_telegram = trim($_POST['author_telegram'] ?? '');
    $author_photo    = get_setting($pdo, 'author_photo');

    // Handle photo upload
    if (!empty($_FILES['author_photo']['name'])) {
        $file    = $_FILES['author_photo'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime    = mime_content_type($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $errors[] = 'Photo must be JPEG, PNG, WebP, or GIF.';
        } elseif ($file['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Photo must be under 3 MB.';
        } else {
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = 'author-photo.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                $author_photo = 'uploads/' . $filename;
            } else {
                $errors[] = 'Failed to save photo. Check folder permissions.';
            }
        }
    }

    $custom_code_head   = $_POST['custom_code_head'] ?? '';
    $custom_code_body   = $_POST['custom_code_body'] ?? '';
    $custom_code_footer = $_POST['custom_code_footer'] ?? '';

    if (empty($errors)) {
        save_setting($pdo, 'author_name',     $author_name);
        save_setting($pdo, 'author_title',    $author_title);
        save_setting($pdo, 'author_bio',      $author_bio);
        save_setting($pdo, 'author_photo',    $author_photo);
        save_setting($pdo, 'author_email',    $author_email);
        save_setting($pdo, 'author_linkedin', $author_linkedin);
        save_setting($pdo, 'author_telegram', $author_telegram);
        
        save_setting($pdo, 'custom_code_head',   $custom_code_head);
        save_setting($pdo, 'custom_code_body',   $custom_code_body);
        save_setting($pdo, 'custom_code_footer', $custom_code_footer);
        
        $flash = 'Settings saved.';
    }
}

// ── Load current values ────────────────────────────────────────────────────
$author_name     = get_setting($pdo, 'author_name',     'Hari Gopinath');
$author_title    = get_setting($pdo, 'author_title',    'Growth Strategist & SEO Engineer');
$author_bio      = get_setting($pdo, 'author_bio',      '');
$author_photo    = get_setting($pdo, 'author_photo',    '');
$author_email    = get_setting($pdo, 'author_email',    'contact@sumomedia.in');
$author_linkedin = get_setting($pdo, 'author_linkedin', '');
$author_telegram = get_setting($pdo, 'author_telegram', '');

$custom_code_head   = get_setting($pdo, 'custom_code_head', '');
$custom_code_body   = get_setting($pdo, 'custom_code_body', '');
$custom_code_footer = get_setting($pdo, 'custom_code_footer', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<style>
.settings-grid { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start; margin-top: 1.5rem; }
.author-preview { background: #fff; border: 1px solid var(--gray-border); border-radius: 12px; padding: 2rem; text-align: center; }
.author-preview img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--orange); margin-bottom: 1rem; display: block; margin-left: auto; margin-right: auto; }
.author-preview .preview-avatar { width: 100px; height: 100px; border-radius: 50%; background: var(--navy); color: #fff; font-size: 2rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-family: var(--font-h); }
.author-preview h4 { font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.3rem; }
.author-preview p  { font-size: 0.85rem; color: var(--text-light); }
.author-preview .preview-bio { font-size: 0.88rem; color: var(--text-dark); line-height: 1.7; margin-top: 0.8rem; text-align: left; }
@media (max-width: 900px) { .settings-grid { grid-template-columns: 1fr; } }
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<div class="admin-layout">

  <aside class="sidebar">
    <div class="sidebar-logo"><img src="../logo.png" alt="SumoMedia" style="max-width: 150px; height: auto;"></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="nav-item">Dashboard</a>
      <a href="posts.php" class="nav-item">Posts</a>
      <a href="post-form.php" class="nav-item">New Post</a>
      <a href="crm.php" class="nav-item">CRM</a>
      <a href="chats.php" class="nav-item">Live Chats</a>
      <a href="subscribers.php" class="nav-item">Subscribers</a>
      <a href="settings.php" class="nav-item active">Settings</a>
      <a href="../index.php" class="nav-item" target="_blank">View Site</a>
    </nav>
    <div class="sidebar-footer">
      <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="admin-main">
    <div class="page-header">
      <h1>Settings</h1>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
          <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="settings-grid">

        <!-- Left: fields -->
        <div>
          <div class="meta-box">
            <h3>Author Profile</h3>

            <div class="field">
              <label for="author_name">Author Name</label>
              <input type="text" id="author_name" name="author_name" value="<?= htmlspecialchars($author_name) ?>" placeholder="Hari Gopinath">
            </div>

            <div class="field">
              <label for="author_title">Title / Role</label>
              <input type="text" id="author_title" name="author_title" value="<?= htmlspecialchars($author_title) ?>" placeholder="Growth Strategist & SEO Engineer">
            </div>

            <div class="field">
              <label for="author_bio">Bio <span class="field-hint">(shown on every blog post)</span></label>
              <textarea id="author_bio" name="author_bio" rows="6" placeholder="Write a short bio about yourself..."><?= htmlspecialchars($author_bio) ?></textarea>
            </div>

            <div class="field">
              <label for="author_photo">Author Photo</label>
              <div class="upload-wrap" id="upload-drop">
                <input type="file" id="author_photo" name="author_photo" accept="image/jpeg,image/png,image/webp,image/gif" class="upload-input">
                <div class="upload-ui">
                  <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4-4m0 0l4 4m-4-4v9M20 16.58A5 5 0 0018 7h-1.26A8 8 0 104 15.25"/></svg>
                  <span class="upload-label" id="upload-label">Click to upload or drag &amp; drop</span>
                  <span class="upload-hint">JPEG, PNG, WebP — max 3 MB</span>
                </div>
              </div>
            </div>

            <div style="border-top:1px solid var(--gray-border);margin:1.5rem 0 1.2rem;"></div>
            <h4 style="font-size:0.9rem;font-weight:700;color:var(--text-dark);margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.5px;">Contact &amp; Social Links</h4>

            <div class="field">
              <label for="author_email">Email Address</label>
              <input type="email" id="author_email" name="author_email" value="<?= htmlspecialchars($author_email) ?>" placeholder="contact@yourdomain.com">
            </div>

            <div class="field">
              <label for="author_linkedin">LinkedIn URL</label>
              <input type="url" id="author_linkedin" name="author_linkedin" value="<?= htmlspecialchars($author_linkedin) ?>" placeholder="https://linkedin.com/in/yourprofile">
            </div>

            <div class="field">
              <label for="author_telegram">Telegram Link <span class="field-hint">(t.me/username or deep link)</span></label>
              <input type="url" id="author_telegram" name="author_telegram" value="<?= htmlspecialchars($author_telegram) ?>" placeholder="https://t.me/yourusername">
            </div>

            <div style="border-top:1px solid var(--gray-border);margin:2.5rem 0 1.2rem;"></div>
            <h4 style="font-size:0.9rem;font-weight:700;color:var(--text-dark);margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.5px;">Custom Code Injection</h4>
            <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1.5rem;">Add external scripts, analytics tracking, or custom styles here.</p>

            <div class="field">
              <label for="custom_code_head">Head Code <span class="field-hint">(Inside &lt;head&gt;)</span></label>
              <textarea id="custom_code_head" name="custom_code_head" rows="4" placeholder="<script>...</script> หรือ <link rel='stylesheet' ...>" style="font-family: monospace; font-size: 0.85rem; line-height: 1.5;"><?= htmlspecialchars($custom_code_head) ?></textarea>
            </div>

            <div class="field">
              <label for="custom_code_body">Body Top Code <span class="field-hint">(Right after &lt;body&gt;)</span></label>
              <textarea id="custom_code_body" name="custom_code_body" rows="4" placeholder="<!-- Google Tag Manager (noscript) -->" style="font-family: monospace; font-size: 0.85rem; line-height: 1.5;"><?= htmlspecialchars($custom_code_body) ?></textarea>
            </div>

            <div class="field">
              <label for="custom_code_footer">Footer Code <span class="field-hint">(Right before &lt;/body&gt;)</span></label>
              <textarea id="custom_code_footer" name="custom_code_footer" rows="4" placeholder="<script>...</script>" style="font-family: monospace; font-size: 0.85rem; line-height: 1.5;"><?= htmlspecialchars($custom_code_footer) ?></textarea>
            </div>

            <button type="submit" class="btn-submit" style="max-width:200px;">Save Settings</button>
          </div>
        </div>

        <!-- Right: live preview -->
        <div>
          <div class="meta-box">
            <h3>Preview</h3>
            <div class="author-preview">
              <?php if ($author_photo): ?>
              <img src="../<?= htmlspecialchars($author_photo) ?>" alt="Author photo" id="preview-img">
              <?php else: ?>
              <div class="preview-avatar" id="preview-avatar">HG</div>
              <img src="" alt="" id="preview-img" style="display:none;">
              <?php endif; ?>
              <h4 id="preview-name"><?= htmlspecialchars($author_name) ?></h4>
              <p id="preview-title"><?= htmlspecialchars($author_title) ?></p>
              <?php if ($author_bio): ?>
              <div class="preview-bio" id="preview-bio"><?= nl2br(htmlspecialchars($author_bio)) ?></div>
              <?php else: ?>
              <div class="preview-bio" id="preview-bio" style="color:var(--text-light);font-style:italic;">Your bio will appear here...</div>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>
    </form>

  </main>
</div>

<script>
// Live preview updates
document.getElementById('author_name').addEventListener('input', e => {
  document.getElementById('preview-name').textContent = e.target.value || 'Author Name';
});
document.getElementById('author_title').addEventListener('input', e => {
  document.getElementById('preview-title').textContent = e.target.value || 'Title / Role';
});
document.getElementById('author_bio').addEventListener('input', e => {
  document.getElementById('preview-bio').textContent = e.target.value || 'Your bio will appear here...';
});

// Photo preview
const photoInput = document.getElementById('author_photo');
photoInput.addEventListener('change', () => {
  const file = photoInput.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img    = document.getElementById('preview-img');
    const avatar = document.getElementById('preview-avatar');
    img.src = e.target.result;
    img.style.display = 'block';
    if (avatar) avatar.style.display = 'none';
    document.getElementById('upload-label').textContent = file.name;
  };
  reader.readAsDataURL(file);
});
</script>

</body>
</html>
