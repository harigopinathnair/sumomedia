<?php require_once 'db.php'; require_once 'includes/tracking.php'; $nav_prefix = 'index.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Free Marketing Tools | SumoMedia.in</title>
<meta name="description" content="Free marketing tools for performance marketers, ROAS calculator, MER tracker, and more.">
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css?v=3">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<style>
.tools-hero {
  background: var(--navy);
  padding: 4rem 0 3rem;
  border-bottom: 1px solid rgba(255,255,255,0.07);
}
.tools-hero-tag {
  display: inline-block;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--orange);
  border: 1px solid rgba(255,122,89,0.35);
  padding: 4px 14px;
  border-radius: 3px;
  margin-bottom: 1.2rem;
}
.tools-hero h1 {
  font-size: clamp(2rem, 4vw, 3rem);
  color: #fff;
  font-weight: 800;
  letter-spacing: -0.02em;
  margin-bottom: 0.75rem;
}
.tools-hero p {
  font-size: 1.05rem;
  color: rgba(255,255,255,0.55);
  max-width: 520px;
}

/* ── Tools Grid ─────────────────────────────────────────────────────────── */
.tools-index { padding: 4rem 0 6rem; background: var(--gray-bg); }
.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}
.tool-card {
  background: var(--card);
  border: 1px solid var(--gray-border);
  border-radius: 16px;
  padding: 2rem 2rem 1.6rem;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  gap: 0;
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
  position: relative;
  overflow: hidden;
}
.tool-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--orange), #ff9a6c);
  opacity: 0;
  transition: opacity 0.2s;
}
.tool-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.09);
  border-color: rgba(255,122,89,0.25);
}
.tool-card:hover::before { opacity: 1; }

.tool-card-icon {
  width: 48px;
  height: 48px;
  background: rgba(255,122,89,0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.2rem;
  color: var(--orange);
}
.tool-card-tag {
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: var(--orange);
  margin-bottom: 0.5rem;
}
.tool-card h3 {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 0.6rem;
  letter-spacing: -0.01em;
}
.tool-card p {
  font-size: 0.88rem;
  color: var(--text-light);
  line-height: 1.6;
  margin: 0 0 1.5rem;
  flex: 1;
}
.tool-card-cta {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--orange);
}
.tool-card-cta svg { transition: transform 0.2s; }
.tool-card:hover .tool-card-cta svg { transform: translateX(3px); }

@media(max-width:640px) {
  .tools-grid { grid-template-columns: 1fr; }
}
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<?php require 'includes/nav.php'; ?>

<!-- HERO -->
<section class="tools-hero">
  <div class="container">
    <div class="tools-hero-tag">Free Tools</div>
    <h1>Marketing Tools</h1>
    <p>Practical calculators and frameworks for performance marketers who care about real numbers.</p>
  </div>
</section>

<!-- TOOLS GRID -->
<section class="tools-index">
  <div class="container">
    <div class="tools-grid">

      <a href="roas-calculator.php" class="tool-card">
        <div class="tool-card-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
            <path d="M7 8h10M7 12h6"/>
          </svg>
        </div>
        <div class="tool-card-tag">Calculator</div>
        <h3>ROAS &amp; MER Calculator</h3>
        <p>Track channel-level ROAS, your overall Marketing Efficiency Ratio, and break-even ROAS across Google, Meta, and every other ad channel, in real time.</p>
        <span class="tool-card-cta">
          Open Calculator
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
      </a>

    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>

</body>
</html>
