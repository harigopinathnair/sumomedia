<?php
// $nav_prefix, set to '' for homepage, 'index.php' for sub-pages (blog, single-post, etc.)
// defaults to 'index.php' so it works safely on all pages
$nav_prefix ??= 'index.php';
?>
<?= $custom_code_body ?? '' ?>
<!-- NAV -->
<nav class="main-nav" id="mainNav">
  <div class="container nav-content">
    <a href="index.php" class="logo-img"><img src="uploads/hari-dark-logo.png" alt="SumoMedia" width="200" style="height:auto;"></a>
    <div class="nav-links">
      <a href="<?= $nav_prefix ?>#services">Services</a>
      <a href="<?= $nav_prefix ?>#about">About</a>
      <a href="<?= $nav_prefix ?>#pricing">Packages</a>
      <a href="blog.php" <?= basename($_SERVER['PHP_SELF']) === 'blog.php' ? 'class="nav-link-strong" style="color:var(--orange);"' : '' ?>>Blog</a>
      <a href="tools.php" <?= basename($_SERVER['PHP_SELF']) === 'tools.php' ? 'style="color:var(--orange);"' : '' ?>>Tools</a>
    </div>
    <div class="nav-actions">
      <a href="<?= $nav_prefix ?>#contact" class="nav-link-strong">Call Now</a>
      <a href="<?= $nav_prefix ?>#contact" class="btn btn-primary">Get in Touch</a>
    </div>
    <button class="nav-burger" id="navBurger" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>

  <!-- Mobile menu -->
  <div class="nav-mobile-menu" id="navMobileMenu">
    <a href="<?= $nav_prefix ?>#services" class="nav-mobile-link">Services</a>
    <a href="<?= $nav_prefix ?>#about"    class="nav-mobile-link">About</a>
    <a href="<?= $nav_prefix ?>#pricing"  class="nav-mobile-link">Packages</a>
    <a href="blog.php"  class="nav-mobile-link">Blog</a>
    <a href="tools.php" class="nav-mobile-link">Tools</a>
    <div class="nav-mobile-actions">
      <a href="<?= $nav_prefix ?>#contact" class="nav-link-strong">Call Now</a>
      <a href="<?= $nav_prefix ?>#contact" class="btn btn-primary" style="text-align:center;">Get in Touch</a>
    </div>
  </div>
</nav>

<script>
(function () {
  var burger = document.getElementById('navBurger');
  var menu   = document.getElementById('navMobileMenu');
  if (!burger || !menu) return;

  burger.addEventListener('click', function () {
    var open = menu.classList.toggle('open');
    burger.classList.toggle('open', open);
    burger.setAttribute('aria-expanded', open);
    document.body.classList.toggle('nav-open', open);
  });

  // Close on link click
  menu.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      menu.classList.remove('open');
      burger.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('nav-open');
    });
  });
})();
</script>