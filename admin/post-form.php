<?php
require_once 'auth.php';

// ── Auto-migrate: add SEO + featured_image columns if missing ──────────────
$existing_cols = $pdo->query("DESCRIBE posts")->fetchAll(PDO::FETCH_COLUMN);
$migrations = [
    'meta_title'       => "ALTER TABLE posts ADD COLUMN meta_title VARCHAR(255) NOT NULL DEFAULT ''",
    'canonical_url'    => "ALTER TABLE posts ADD COLUMN canonical_url VARCHAR(500) NOT NULL DEFAULT ''",
    'meta_description' => "ALTER TABLE posts ADD COLUMN meta_description TEXT",
    'schema_type'      => "ALTER TABLE posts ADD COLUMN schema_type VARCHAR(100) NOT NULL DEFAULT ''",
    'meta_keywords'    => "ALTER TABLE posts ADD COLUMN meta_keywords VARCHAR(500) NOT NULL DEFAULT ''",
    'featured_image'   => "ALTER TABLE posts ADD COLUMN featured_image VARCHAR(500) NOT NULL DEFAULT ''",
];
foreach ($migrations as $col => $sql) {
    if (!in_array($col, $existing_cols)) {
        $pdo->exec($sql);
    }
}

// ── Upload dir ─────────────────────────────────────────────────────────────
$upload_dir = __DIR__ . '/../uploads/';
$upload_url = '../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = $id > 0 ? 'edit' : 'new';
$post = [
    'title'            => '',
    'slug'             => '',
    'category'         => '',
    'excerpt'          => '',
    'content'          => '',
    'image_url'        => '',
    'status'           => 'draft',
    'meta_title'       => '',
    'canonical_url'    => '',
    'meta_description' => '',
    'schema_type'      => '',
    'meta_keywords'    => '',
    'featured_image'   => '',
];
$errors = [];

// Load existing post for edit mode
if ($mode === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Post not found.'];
        header('Location: posts.php');
        exit;
    }
    $post = array_merge($post, $row); // merge so new SEO keys always exist
}

// ── Handle form submission ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post['title']            = trim($_POST['title']            ?? '');
    $post['slug']             = trim($_POST['slug']             ?? '');
    $post['category']         = trim($_POST['category']         ?? '');
    $post['excerpt']          = trim($_POST['excerpt']          ?? '');
    $post['content']          = trim($_POST['content']          ?? '');
    $post['image_url']        = trim($_POST['image_url']        ?? '');
    $post['status']           = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'draft';
    $post['meta_title']       = trim($_POST['meta_title']       ?? '');
    $post['canonical_url']    = trim($_POST['canonical_url']    ?? '');
    $post['meta_description'] = trim($_POST['meta_description'] ?? '');
    $post['schema_type']      = trim($_POST['schema_type']      ?? '');
    $post['meta_keywords']    = trim($_POST['meta_keywords']    ?? '');
    $post['featured_image']   = trim($_POST['featured_image_existing'] ?? '');

    // Auto-generate slug
    if ($post['slug'] === '' && $post['title'] !== '') {
        $post['slug'] = make_slug($post['title']);
    } else {
        $post['slug'] = make_slug($post['slug']);
    }

    // Handle featured image upload
    if (!empty($_FILES['featured_image']['name'])) {
        $file      = $_FILES['featured_image'];
        $allowed   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime = mime_content_type($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $errors[] = 'Featured image must be JPEG, PNG, WebP, or GIF.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Featured image must be under 5 MB.';
        } else {
            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_', true) . '.' . strtolower($ext);
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                // Remove old uploaded image if it exists and differs
                $old = trim($_POST['featured_image_existing'] ?? '');
                if ($old && strpos($old, 'uploads/') !== false) {
                    $old_path = __DIR__ . '/../' . ltrim($old, './');
                    if (is_file($old_path)) {
                        @unlink($old_path);
                    }
                }
                $post['featured_image'] = 'uploads/' . $filename;
            } else {
                $errors[] = 'Failed to save the uploaded image. Check folder permissions.';
            }
        }
    }

    // Validation
    if ($post['title'] === '')    $errors[] = 'Title is required.';
    if ($post['slug'] === '')     $errors[] = 'Slug is required.';
    if ($post['category'] === '') $errors[] = 'Category is required.';
    if ($post['content'] === '')  $errors[] = 'Content is required.';

    // Slug uniqueness
    if (empty($errors)) {
        $slug_check = $pdo->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
        $slug_check->execute([$post['slug'], $id]);
        if ($slug_check->fetch()) {
            $errors[] = 'This slug is already in use. Choose a different one.';
        }
    }

    if (empty($errors)) {
        $fields = [
            $post['title'], $post['slug'], $post['category'], $post['excerpt'],
            $post['content'], $post['image_url'], $post['status'],
            $post['meta_title'], $post['canonical_url'], $post['meta_description'],
            $post['schema_type'], $post['meta_keywords'], $post['featured_image'],
        ];

        if ($mode === 'new') {
            $stmt = $pdo->prepare("INSERT INTO posts
                (title, slug, category, excerpt, content, image_url, status,
                 meta_title, canonical_url, meta_description, schema_type, meta_keywords, featured_image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute($fields);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Post created successfully.'];
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET
                title=?, slug=?, category=?, excerpt=?, content=?, image_url=?, status=?,
                meta_title=?, canonical_url=?, meta_description=?, schema_type=?, meta_keywords=?, featured_image=?
                WHERE id=?");
            $stmt->execute(array_merge($fields, [$id]));
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Post updated successfully.'];
        }
        header('Location: posts.php');
        exit;
    }
}

function make_slug(string $str): string {
    $str = mb_strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', $str);
    return trim($str, '-');
}

$page_title = $mode === 'edit' ? 'Edit Post' : 'New Post';
$featured_src = $post['featured_image'] ? ('../' . $post['featured_image']) : ($post['image_url'] ?: '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?> | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">HG<span>Admin</span></div>
    <nav class="sidebar-nav">
      <a href="posts.php" class="nav-item">Posts</a>
      <a href="post-form.php" class="nav-item <?= $mode === 'new' ? 'active' : '' ?>">New Post</a>
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
        <h1><?= $page_title ?></h1>
        <?php if ($mode === 'edit'): ?>
          <p class="page-sub"><a href="../single-post.php?slug=<?= urlencode($post['slug']) ?>" target="_blank">View live post &rarr;</a></p>
        <?php endif; ?>
      </div>
      <a href="posts.php" class="btn-secondary">&larr; All Posts</a>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
          <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="post-form">

      <div class="form-layout">

        <!-- Left: main fields -->
        <div class="form-main">

          <div class="field">
            <label for="title">Title <span class="req">*</span></label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" placeholder="Post title" required>
          </div>

          <div class="field">
            <label for="slug">Slug <span class="req">*</span> <span class="field-hint">(leave blank to auto-generate from title)</span></label>
            <div class="slug-wrap">
              <span class="slug-prefix">/</span>
              <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($post['slug']) ?>" placeholder="post-url-slug">
            </div>
          </div>

          <div class="field">
            <label for="excerpt">Excerpt <span class="field-hint">(shown on blog listing)</span></label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Short description of the post..."><?= htmlspecialchars($post['excerpt']) ?></textarea>
          </div>

          <div class="field">
            <label for="content">Content <span class="req">*</span> <span class="field-hint">(HTML supported)</span></label>
            <textarea id="content" name="content" rows="22" class="content-editor" placeholder="<p>Start writing your post...</p>"><?= htmlspecialchars($post['content']) ?></textarea>
          </div>

          <!-- SEO box -->
          <div class="meta-box">
            <h3>SEO &amp; Schema</h3>

            <div class="seo-grid">

              <div class="field">
                <label for="meta_title">Meta Title <span class="field-hint"><span id="mt-count">0</span>/60</span></label>
                <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($post['meta_title']) ?>" placeholder="Leave blank to use post title" maxlength="100">
                <div class="char-bar"><div class="char-fill" id="mt-bar"></div></div>
              </div>

              <div class="field">
                <label for="meta_keywords">Meta Keywords <span class="field-hint">(comma-separated)</span></label>
                <input type="text" id="meta_keywords" name="meta_keywords" value="<?= htmlspecialchars($post['meta_keywords']) ?>" placeholder="seo, growth, ppc">
              </div>

            </div>

            <div class="field">
              <label for="meta_description">Meta Description <span class="field-hint"><span id="md-count">0</span>/160</span></label>
              <textarea id="meta_description" name="meta_description" rows="3" placeholder="Brief description for search results..." maxlength="300"><?= htmlspecialchars($post['meta_description']) ?></textarea>
              <div class="char-bar"><div class="char-fill" id="md-bar"></div></div>
            </div>

            <div class="seo-grid">

              <div class="field">
                <label for="canonical_url">Canonical URL <span class="field-hint">(leave blank = self-canonical)</span></label>
                <input type="url" id="canonical_url" name="canonical_url" value="<?= htmlspecialchars($post['canonical_url']) ?>" placeholder="https://harigopinath.com/...">
              </div>

              <div class="field">
                <label for="schema_type">Schema Type</label>
                <select id="schema_type" name="schema_type">
                  <option value=""            <?= $post['schema_type'] === ''            ? 'selected' : '' ?>>— None —</option>
                  <option value="Article"     <?= $post['schema_type'] === 'Article'     ? 'selected' : '' ?>>Article</option>
                  <option value="BlogPosting" <?= $post['schema_type'] === 'BlogPosting' ? 'selected' : '' ?>>BlogPosting</option>
                  <option value="FAQPage"     <?= $post['schema_type'] === 'FAQPage'     ? 'selected' : '' ?>>FAQPage</option>
                  <option value="HowTo"       <?= $post['schema_type'] === 'HowTo'       ? 'selected' : '' ?>>HowTo</option>
                  <option value="WebPage"     <?= $post['schema_type'] === 'WebPage'     ? 'selected' : '' ?>>WebPage</option>
                </select>
              </div>

            </div>
          </div>

        </div>

        <!-- Right: meta sidebar -->
        <div class="form-sidebar">

          <!-- Publish box -->
          <div class="meta-box">
            <h3>Publish</h3>
            <div class="field">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="draft"     <?= $post['status'] === 'draft'     ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
              </select>
            </div>
            <button type="submit" class="btn-submit">
              <?= $mode === 'edit' ? 'Update Post' : 'Create Post' ?>
            </button>
            <?php if ($mode === 'edit'): ?>
              <a href="post-delete.php?id=<?= $id ?>" class="btn-danger" onclick="return confirm('Delete this post? This cannot be undone.')">Delete Post</a>
            <?php endif; ?>
          </div>

          <!-- Details box -->
          <div class="meta-box">
            <h3>Details</h3>
            <div class="field">
              <label for="category">Category <span class="req">*</span></label>
              <input type="text" id="category" name="category" value="<?= htmlspecialchars($post['category']) ?>" placeholder="e.g. SEO Strategy" list="category-suggestions" required>
              <datalist id="category-suggestions">
                <option value="SEO Strategy">
                <option value="PPC &amp; Paid Ads">
                <option value="Content Creation">
                <option value="CRO">
                <option value="Leadership">
                <option value="AI Search">
              </datalist>
            </div>
            <div class="field">
              <label for="image_url">Cover Image URL <span class="field-hint">(external)</span></label>
              <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($post['image_url']) ?>" placeholder="https://...">
            </div>
            <?php if ($post['image_url']): ?>
            <div class="image-preview" id="url-preview">
              <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="Cover preview" onerror="this.parentElement.style.display='none'">
            </div>
            <?php endif; ?>
          </div>

          <!-- Featured Image Upload box -->
          <div class="meta-box">
            <h3>Featured Image</h3>

            <?php if ($featured_src): ?>
            <div class="image-preview" id="feat-preview">
              <img src="<?= htmlspecialchars($featured_src) ?>" alt="Featured image preview" onerror="this.parentElement.style.display='none'">
              <?php if ($post['featured_image']): ?>
              <div class="img-meta"><?= htmlspecialchars(basename($post['featured_image'])) ?></div>
              <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="image-preview feat-empty" id="feat-preview" style="display:none;">
              <img src="" alt="Featured image preview">
            </div>
            <?php endif; ?>

            <div class="field mt-3">
              <label for="featured_image">Upload Image</label>
              <div class="upload-wrap" id="upload-drop">
                <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif" class="upload-input">
                <div class="upload-ui">
                  <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4-4m0 0l4 4m-4-4v9M20 16.58A5 5 0 0018 7h-1.26A8 8 0 104 15.25"/></svg>
                  <span class="upload-label">Click to upload or drag &amp; drop</span>
                  <span class="upload-hint">JPEG, PNG, WebP, GIF &mdash; max 5 MB</span>
                </div>
              </div>
            </div>

            <!-- Preserve existing uploaded file across re-renders -->
            <input type="hidden" name="featured_image_existing" value="<?= htmlspecialchars($post['featured_image']) ?>">
          </div>



        </div><!-- /form-sidebar -->
      </div><!-- /form-layout -->

    </form>
  </main>

</div>

<script>
// ── Slug auto-generate ─────────────────────────────────────────────────────
const titleInput = document.getElementById('title');
const slugInput  = document.getElementById('slug');
let slugManual   = slugInput.value !== '';

titleInput.addEventListener('input', () => {
  if (!slugManual) slugInput.value = makeSlug(titleInput.value);
});
slugInput.addEventListener('input', () => {
  slugManual = slugInput.value !== '';
});

function makeSlug(str) {
  return str.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

// ── Live URL image preview ─────────────────────────────────────────────────
const imgInput = document.getElementById('image_url');
imgInput.addEventListener('change', () => {
  let preview = document.getElementById('url-preview');
  if (!preview) {
    preview = document.createElement('div');
    preview.id = 'url-preview';
    preview.className = 'image-preview';
    imgInput.parentElement.appendChild(preview);
  }
  if (imgInput.value) {
    preview.innerHTML = `<img src="${imgInput.value}" alt="Cover preview" onerror="this.parentElement.style.display='none'" onload="this.parentElement.style.display=''">`;
    preview.style.display = '';
  } else {
    preview.innerHTML = '';
  }
});

// ── Featured image upload preview ──────────────────────────────────────────
const fileInput  = document.getElementById('featured_image');
const featPreview = document.getElementById('feat-preview');
const uploadDrop  = document.getElementById('upload-drop');

fileInput.addEventListener('change', () => showFile(fileInput.files[0]));

uploadDrop.addEventListener('dragover',  e => { e.preventDefault(); uploadDrop.classList.add('drag-over'); });
uploadDrop.addEventListener('dragleave', () => uploadDrop.classList.remove('drag-over'));
uploadDrop.addEventListener('drop', e => {
  e.preventDefault();
  uploadDrop.classList.remove('drag-over');
  const dt = e.dataTransfer;
  if (dt.files.length) {
    fileInput.files = dt.files;
    showFile(dt.files[0]);
  }
});

function showFile(file) {
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    featPreview.style.display = '';
    featPreview.innerHTML = `<img src="${e.target.result}" alt="Featured image preview"><div class="img-meta">${file.name} (${(file.size/1024).toFixed(0)} KB)</div>`;
  };
  reader.readAsDataURL(file);
  // Update drop area label
  uploadDrop.querySelector('.upload-label').textContent = file.name;
}

// ── Character counters for SEO fields ─────────────────────────────────────
function charCounter(inputId, countId, barId, limit) {
  const el  = document.getElementById(inputId);
  const cnt = document.getElementById(countId);
  const bar = document.getElementById(barId);
  function update() {
    const len  = el.value.length;
    const pct  = Math.min(len / limit * 100, 100);
    cnt.textContent = len;
    bar.style.width = pct + '%';
    bar.style.background = pct > 100 ? '#ef4444' : pct > 85 ? '#f59e0b' : '#22c55e';
  }
  el.addEventListener('input', update);
  update();
}

charCounter('meta_title',       'mt-count', 'mt-bar', 60);
charCounter('meta_description', 'md-count', 'md-bar', 160);
</script>

</body>
</html>
