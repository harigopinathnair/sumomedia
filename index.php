<?php require_once 'db.php'; require_once 'includes/tracking.php'; require_once 'includes/captcha.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SumoMedia.in | Performance Digital Marketing & SEO</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css?v=3">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<?= $custom_code_head ?? '' ?>
</head>
<body>

<?php $nav_prefix = ''; require 'includes/nav.php'; ?>


<!-- CERTIFICATIONS STRIP -->
<div class="certs-strip">
  <div class="container hero-certs">
    <span class="hero-certs-label">Certified Partners &amp; Platforms</span>
    <div class="hero-certs-list">
      <div class="hero-cert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FF7A59" stroke="#FF7A59" stroke-width="1.5" stroke-linejoin="round"/></svg>
        Google Certified
      </div>
      <div class="hero-cert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FF7A59" stroke="#FF7A59" stroke-width="1.5" stroke-linejoin="round"/></svg>
        Microsoft Certified
      </div>
      <div class="hero-cert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FF7A59" stroke="#FF7A59" stroke-width="1.5" stroke-linejoin="round"/></svg>
        HubSpot Certified
      </div>
      <div class="hero-cert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FF7A59" stroke="#FF7A59" stroke-width="1.5" stroke-linejoin="round"/></svg>
        Apple Certified
      </div>
    </div>
  </div>
</div>

<!-- HERO -->
<section class="hero">

  <div class="container hero-split">
    <div class="hero-text">
      <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 1.5rem;">
        <div class="tag" style="border-color: var(--text-dark); color: var(--text-dark); background: transparent; margin-bottom: 0;">Performance SEO &amp; Growth</div>
        <div class="tag" style="background: var(--orange); border-color: var(--orange); color: #fff; margin-bottom: 0;">2x Published Author &amp; Mentor</div>
      </div>
      <h1 style="margin-top: 0;">RESULTS.<br>NOT<br>REPORTS.</h1>
      <p class="lead">I turn ad spend and search intent into measurable revenue. No vanity metrics. No fluff. Just campaigns engineered to grow your bottom line.</p>
      <div class="hero-stats">
        <div class="stat"><span class="stat-num">$30M+</span><span class="stat-label">Revenue Driven</span></div>
        <div class="stat"><span class="stat-num">50+</span><span class="stat-label">Brands Scaled</span></div>
        <div class="stat"><span class="stat-num">5×</span><span class="stat-label">Avg. ROAS</span></div>
      </div>
    </div>
    <div class="hero-form-box">
      <h3>Get Your Free Growth Audit</h3>
      <p>I'll analyse your website and share a roadmap for growth!</p>
      <form class="audit-form utm-form" id="form-audit" method="POST" action="success.php">
        <div class="form-inline-msg" id="msg-audit"></div>
        <input type="hidden" name="form_type" value="audit">
        <input type="hidden" name="utm_url" class="utm-url">
        <input type="hidden" name="utm_source" class="utm-source">
        <input type="hidden" name="utm_medium" class="utm-medium">
        <input type="hidden" name="utm_campaign" class="utm-campaign">
        <input type="hidden" name="utm_content" class="utm-content">
        <input type="hidden" name="pricing_plan" class="pricing-plan">
        <div class="audit-form-grid">
          <input type="text" name="name" placeholder="Full Name" required>
          <input type="email" name="email" placeholder="Work Email" required>
          <input type="tel" name="phone" placeholder="Phone / WhatsApp">
          <select name="budget" required>
            <option value="" disabled selected>Monthly Budget</option>
            <option>Under $1,000</option>
            <option>$1,000 - $5,000</option>
            <option>$5,000 - $10,000</option>
            <option>$10,000+</option>
          </select>
        </div>
        <input type="url" name="website" placeholder="Your Website URL (e.g. https://sumomedia.in)">
        <input type="text" name="goal" placeholder="Primary Goal">
        <?= captcha_html() ?>
        <button type="submit" class="btn btn-primary btn-block">Send My Free Audit &rarr;</button>
        <div class="form-secure">🔒 100% confidential. No spam, ever.</div>
      </form>
    </div>
  </div>

</section>

<!-- TESTIMONIALS STRIP -->
<section class="testimonial-strip pt-4 pb-4 border-bottom">
  <div class="container grid-3 text-center">
    <div>
      <div class="stars">★★★★★</div>
      <h4 class="mt-2" style="font-size:1.1rem; color:var(--text-dark);">"Quadruple your SEO Traffic this year"</h4>
    </div>
    <div>
      <div class="stars">★★★★★</div>
      <h4 class="mt-2" style="font-size:1.1rem; color:var(--text-dark);">"Grow demos by atleast 10%"</h4>
    </div>
    <div>
      <div class="stars">★★★★★</div>
      <h4 class="mt-2" style="font-size:1.1rem; color:var(--text-dark);">"Optimize your SEO and PPC campaigns"</h4>
    </div>
  </div>
  <div class="container text-center mt-5 mb-4">
    <p class="font-bold" style="font-size: 1.25rem; font-weight: 600; color:var(--text-dark);">Build a sustainable SEO growth engine ready for the future of AI<br>
    <span class="text-orange" style="font-size:1.1rem;">100% client success rate. No long-term contracts.</span></p>
    <a href="#contact" class="btn btn-primary mt-4">Book a free strategy call</a>
  </div>
</section>

<!-- LOGOS -->
<section class="friends-logos pt-5 pb-5 section-gray" style="overflow: hidden;">
  <div class="container text-center">
    <p class="tag mb-4">A few Brands I worked with</p>
  </div>
  <div class="brands-ticker mt-4">
    <div class="brands-ticker-track">
      <span>SurveySparrow</span>
      <span>ILG</span>
      <span>FortesHoldings</span>
      <span>Swiss Watch Group</span>
      <span>MEED</span>
      <span>Candere</span>
      <span>DPS Sharjah</span>
      <span>DPS RAK</span>
      <span>Commerce Pundit</span>
      <span>Honeystone International</span>
      <span>Rithu</span>
      <span>ContentJumbo</span>
      <span>Satbir International</span>
      <!-- Duplicate for loop -->
      <span>SurveySparrow</span>
      <span>ILG</span>
      <span>FortesHoldings</span>
      <span>Swiss Watch Group</span>
      <span>MEED</span>
      <span>Candere</span>
      <span>DPS Sharjah</span>
      <span>DPS RAK</span>
      <span>Commerce Pundit</span>
      <span>Honeystone International</span>
      <span>Rithu</span>
      <span>ContentJumbo</span>
      <span>Satbir International</span>
    </div>
  </div>
  <div class="container text-center mt-5"></div>
</section>

<!-- PAIN POINTS -->
<section class="pain-points">
  <div class="container">
    <div class="pain-inner">

      <div class="pain-title">
        <span class="pain-eyebrow">The Opportunity</span>
        <h2 class="pain-headline">Turn Your Marketing Into A Predictable Revenue Engine.</h2>
        <p class="pain-sub">Most brands track impressions. The ones that scale track revenue. Let's fix the gap.</p>
        <a href="#contact" class="btn btn-primary pain-cta">Let's Fix This &rarr;</a>
      </div>

      <div class="pain-grid">
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>You're spending more on ads but your profit margins keep shrinking</p>
        </div>
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>SEO traffic is up, but qualified leads haven't moved</p>
        </div>
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>Monthly reports are full of impressions and clicks, not revenue</p>
        </div>
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>No one can tell you which channel is actually driving growth</p>
        </div>
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>PPC, SEO, and content teams operate in total silos</p>
        </div>
        <div class="pain-card">
          <div class="pain-x">✕</div>
          <p>You've outgrown your agency but don't know where to go next</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="what-we-do section-gray">
  <div class="container text-center">
    <div class="tag text-orange">What I do</div>
    <h2 style="font-size:2.8rem; margin-bottom: 1.5rem;">I help ambitious brands grow their SEO traffic</h2>
    <p class="max-w-700 mx-auto" style="font-size:1.1rem; margin-bottom:15px; padding-bottom:15px;">Most brands pour budget into ads, sponsorships, email, and social, yet very few build the one channel that compounds over time and costs nothing to maintain. In my experience, the brands that consistently produce high-quality, SEO-optimized content are the ones with the most resilient traffic, no matter what else they're spending on.</p>
    
    <!-- GSC Stats Cards -->
    <div class="gsc-stats mt-5">
      <div class="gsc-card gsc-card--active">
        <div class="gsc-label">Total Clicks</div>
        <div class="gsc-value">18.1K</div>
        <div class="gsc-sub">Last 6 months</div>
        <div class="gsc-delta gsc-delta--up">&#9650; +67.6% vs prior period</div>
      </div>
      <div class="gsc-card gsc-card--active">
        <div class="gsc-label">Total Impressions</div>
        <div class="gsc-value">243K</div>
        <div class="gsc-sub">Last 6 months</div>
        <div class="gsc-delta gsc-delta--up">&#9650; +70% vs prior period</div>
      </div>
      <div class="gsc-card">
        <div class="gsc-label">Average CTR</div>
        <div class="gsc-value">7.4%</div>
        <div class="gsc-sub">Last 6 months</div>
        <div class="gsc-delta gsc-delta--neutral">Consistent engagement</div>
      </div>
      <div class="gsc-card">
        <div class="gsc-label">Avg. Position</div>
        <div class="gsc-value">6.6</div>
        <div class="gsc-sub">Last 6 months</div>
        <div class="gsc-delta gsc-delta--up">&#9650; Up from 12.3 &mdash; nearly 2&times; better</div>
      </div>
    </div>

    <!-- Chart Image -->
    <div class="gsc-chart-wrap mt-4">
      <div class="gsc-chart-header">
        <span class="gsc-chart-title">Google Search Console &mdash; Clicks &amp; Impressions</span>
        <span class="gsc-chart-date">As of 10/04/2026</span>
      </div>
      <img src="uploads/casestudy-01-img.png" alt="Google Search Console, clicks & impressions growth chart" class="gsc-chart-img">
    </div>

    <!-- Insights -->
    <div class="gsc-insights mt-5">
      <div class="gsc-insight">
        <div class="gsc-insight-icon">&#128200;</div>
        <div>
          <h4>Traffic compounded 67% in 6 months</h4>
          <p>Clicks grew from 10.8K to 18.1K, without increasing ad spend. Pure organic compounding driven by intent-matched content and technical SEO.</p>
        </div>
      </div>
      <div class="gsc-insight">
        <div class="gsc-insight-icon">&#128269;</div>
        <div>
          <h4>Average position jumped from 12.3 → 6.6</h4>
          <p>Moving from page 2 to the top half of page 1 more than doubled click-through rates. Position 6.6 puts content directly in the buyer's line of sight.</p>
        </div>
      </div>
      <div class="gsc-insight">
        <div class="gsc-insight-icon">&#9889;</div>
        <div>
          <h4>243K impressions, brand visibility at scale</h4>
          <p>Impressions surged 70% in six months, meaning the brand now appears in search results for 70% more queries than before, building authority across the entire funnel.</p>
        </div>
      </div>
    </div>

    <p class="mt-5 mx-auto max-w-700 opacity-70" style="font-size:1rem;">Source: Google Search Console &mdash; real client data. Results achieved through semantic content strategy, technical SEO governance, and authority backlink sourcing.</p>

    <!-- Case Study Divider -->
    <div class="case-study-divider">
      <span>More Case Studies</span>
    </div>

    <!-- E-commerce Case Study -->
    <div class="case-study-block">
      <div class="case-study-header">
        <div class="case-study-label">Case Study</div>
        <h3 class="case-study-title">E-Commerce Brand &mdash; Organic Growth at Scale</h3>
        <p class="case-study-desc">A large-scale e-commerce brand with an established catalogue. We rebuilt their SEO architecture from the ground up, category pages, PDP optimisation, and a programmatic content layer that compounded traffic month over month.</p>
      </div>

      <!-- Stats Cards -->
      <div class="gsc-stats mt-4">
        <div class="gsc-card gsc-card--active">
          <div class="gsc-label">Total Clicks</div>
          <div class="gsc-value">12.7M</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; +210% vs prior period</div>
        </div>
        <div class="gsc-card gsc-card--active">
          <div class="gsc-label">Total Impressions</div>
          <div class="gsc-value">951M</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; +178% vs prior period</div>
        </div>
        <div class="gsc-card">
          <div class="gsc-label">Average CTR</div>
          <div class="gsc-value">1.3%</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; Up from 1.2%</div>
        </div>
        <div class="gsc-card">
          <div class="gsc-label">Avg. Position</div>
          <div class="gsc-value">7.7</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; Up from 10.5</div>
        </div>
      </div>

      <!-- Chart -->
      <div class="gsc-chart-wrap mt-4">
        <div class="gsc-chart-header">
          <span class="gsc-chart-title">Google Search Console &mdash; E-Commerce Brand</span>
          <span class="gsc-chart-date">As of 10/04/2026</span>
        </div>
        <img src="uploads/casestudy-02-img.png" alt="E-Commerce GSC growth chart" class="gsc-chart-img">
      </div>

      <!-- Insights -->
      <div class="gsc-insights mt-5">
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#128293;</div>
          <div>
            <h4>12.7M clicks, 3&times; growth in 6 months</h4>
            <p>Clicks surged from 4.09M to 12.7M in a single period, driven by category-level keyword clustering and product page schema that unlocked rich results.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#127760;</div>
          <div>
            <h4>951M impressions, top-of-funnel dominance</h4>
            <p>Nearly 1 billion search impressions in 6 months. The brand now appears for virtually every commercial intent query in their vertical, building brand recall before the click.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#128200;</div>
          <div>
            <h4>Position 10.5 → 7.7, page 1 consolidation</h4>
            <p>A 27% improvement in average position pushed hundreds of product and category pages from the bottom of page 1 into the top 5, where 75% of all clicks happen.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Case Study Divider -->
    <div class="case-study-divider">
      <span>More Case Studies</span>
    </div>

    <!-- Food & Beverage Case Study -->
    <div class="case-study-block">
      <div class="case-study-header">
        <div class="case-study-label">Case Study</div>
        <h3 class="case-study-title">Food &amp; Beverage Brand &mdash; Breaking into Page 1</h3>
        <p class="case-study-desc">A growing Food &amp; Beverage brand stuck on page 2 with no clear content strategy. We built a topic-cluster architecture around purchase-intent keywords, improved on-page signals, and doubled their organic visibility in one period.</p>
      </div>

      <!-- Stats Cards -->
      <div class="gsc-stats mt-4">
        <div class="gsc-card gsc-card--active">
          <div class="gsc-label">Total Clicks</div>
          <div class="gsc-value">1.23K</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; +81% vs prior period</div>
        </div>
        <div class="gsc-card gsc-card--active">
          <div class="gsc-label">Total Impressions</div>
          <div class="gsc-value">58.2K</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; +64% vs prior period</div>
        </div>
        <div class="gsc-card">
          <div class="gsc-label">Average CTR</div>
          <div class="gsc-value">2.1%</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; Up from 1.9%</div>
        </div>
        <div class="gsc-card gsc-card--orange">
          <div class="gsc-label">Avg. Position</div>
          <div class="gsc-value">10.2</div>
          <div class="gsc-sub">Last 6 months</div>
          <div class="gsc-delta gsc-delta--up">&#9650; Up from 14.5 &mdash; page 2 to page 1</div>
        </div>
      </div>

      <!-- Chart -->
      <div class="gsc-chart-wrap mt-4">
        <div class="gsc-chart-header">
          <span class="gsc-chart-title">Google Search Console &mdash; Food &amp; Beverage Brand</span>
          <span class="gsc-chart-date">As of 10/04/2026</span>
        </div>
        <img src="uploads/casestudy-03-img.png" alt="Food & Beverage GSC growth chart" class="gsc-chart-img">
      </div>

      <!-- Insights -->
      <div class="gsc-insights mt-5">
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#127857;</div>
          <div>
            <h4>Clicks up 81%, from 679 to 1.23K</h4>
            <p>By targeting high-intent recipe and product comparison keywords, organic clicks nearly doubled without any increase in ad spend, purely through content and on-page optimisation.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#128269;</div>
          <div>
            <h4>58.2K impressions, 64% more visibility</h4>
            <p>Impressions grew from 35.4K to 58.2K as new content covered a wider range of buyer queries across the funnel, from awareness-stage recipes to bottom-of-funnel product searches.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#127942;</div>
          <div>
            <h4>Position 14.5 → 10.2, crossed onto page 1</h4>
            <p>Average position improved by 30%, moving the brand from deep page 2 to the first page of Google. A single page boundary shift that transforms click-through rates overnight.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ABOUT -->
<section class="about-section" id="about">

  <div class="about-photo-col">
    <div class="about-photo-frame">
      <img src="uploads/hari-photo.png" alt="Hari Gopinath">
    </div>
  </div>

  <div class="about-content-col">
    <div class="about-header">
      <span class="about-eyebrow">Who am I?</span>
      <h2 class="about-name">Hari Gopinath</h2>
      <p class="about-lead">Director of Marketing at SumoMedia. Artist, writer, and a committed SEO strategist with over a decade in the craft.</p>
    </div>

    <div class="about-body">
      <p>I've led SEO and content strategy for major brands across India, the UAE, and the US, channeling those learnings into a repeatable, battle-tested framework that consistently scales organic revenue.</p>
      <p>I live at the intersection of content and performance marketing, with my finger on the pulse of AI-driven search shifts every single day.</p>
    </div>

    <div class="about-tags">
      <span class="about-tag">🇮🇳 India</span>
      <span class="about-tag">🇦🇪 UAE</span>
      <span class="about-tag">🇺🇸 US</span>
      <span class="about-tag">14+ Years</span>
      <span class="about-tag">2× Author</span>
      <span class="about-tag">Google Certified</span>
    </div>

    <div class="about-stats">
      <div class="as-stat">
        <span class="as-num">1,000+</span>
        <span class="as-label">Posts Published</span>
      </div>
      <div class="as-stat">
        <span class="as-num">3.6M+</span>
        <span class="as-label">Organic Clicks</span>
      </div>
      <div class="as-stat">
        <span class="as-num">$16M+</span>
        <span class="as-label">Traffic Value</span>
      </div>
      <div class="as-stat">
        <span class="as-num">50+</span>
        <span class="as-label">Brands Scaled</span>
      </div>
    </div>

  </div>

</section>

<!-- WHAT WE OFFER -->
<section class="offer-section section-gray" id="services">
  <div class="container text-center">
    <div class="tag">What I offer</div>
    <h2 style="font-size: 2.8rem; margin-bottom: 2rem;">I'll create a personalized content<br>operation for your brand</h2>
    
    <div class="grid-4 mt-5 text-left">
      <div class="feature-card">
        <h4>A custom content and keyword strategy</h4>
        <p>I give you a monthly content calendar tailored to your brand and target audience.</p>
      </div>
      <div class="feature-card">
        <h4>Backlink building</h4>
        <p>I want you to be an authority in your industry. That means building up your domain authority through mentions on other publications.</p>
      </div>
      <div class="feature-card">
        <h4>Content production</h4>
        <p>I create every piece of content for you so you can focus on growing other parts of your business.</p>
      </div>
      <div class="feature-card">
        <h4>Originally researched</h4>
        <p>All of my content is originally researched and goes through a grammar and plagiarism check before it is handed to you.</p>
      </div>
    </div>
  </div>
</section>

<!-- NOT YOUR TYPICAL AGENCY -->
<section class="why-section">
  <div class="container">

    <div class="why-header">
      <div>
        <span class="why-eyebrow">Why SumoMedia</span>
        <h2 class="why-headline">Not your typical<br>SEO agency.</h2>
      </div>
      <p class="why-subhead">Four things that make every engagement different from day one.</p>
    </div>

    <div class="why-list">

      <div class="why-item">
        <span class="why-num">01</span>
        <div class="why-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg>
        </div>
        <div class="why-body">
          <h3>No contracts</h3>
          <p>Say goodbye to monthly retainers. If you don't like my approach, you can easily part ways, no lock-ins, no penalties, no friction.</p>
        </div>
        <span class="why-tag-right">Flexibility</span>
      </div>

      <div class="why-item">
        <span class="why-num">02</span>
        <div class="why-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <div class="why-body">
          <h3>More than just content</h3>
          <p>Every package includes backlinks, mentions on authoritative websites that grow your domain authority and make you visible in AI search and ChatGPT.</p>
        </div>
        <span class="why-tag-right">Authority</span>
      </div>

      <div class="why-item">
        <span class="why-num">03</span>
        <div class="why-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="why-body">
          <h3>In good hands</h3>
          <p>Your account is handled directly by me, not outsourced, not delegated. I've taken multiple projects from zero to hundreds of thousands of monthly visitors.</p>
        </div>
        <span class="why-tag-right">Direct Access</span>
      </div>

      <div class="why-item">
        <span class="why-num">04</span>
        <div class="why-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="why-body">
          <h3>A focus on SEO</h3>
          <p>Every piece is SEO-optimised and written for humans and search engines alike. 1,000+ posts published, I know exactly what it takes to rank on Google and get cited by AI.</p>
        </div>
        <span class="why-tag-right">Performance</span>
      </div>

    </div>
  </div>
</section>



<!-- PRICING -->
<section class="pricing-section" id="pricing">
  <div class="container">

    <div class="section-head text-center">
      <div class="tag">Choose your SUITE</div>
      <h2 class="pricing-headline">GROWTH<br><span class="text-orange">AS A SERVICE.</span></h2>
    </div>

    <div class="grid-3 mt-5 pricing-cards">

      <!-- Performance Scale -->
      <div class="price-card">
        <div class="price-service-label">Performance Scale</div>
        <h3 class="price-card-name">Accelerator</h3>
        <p class="desc">Profitable paid media acquisition built around strictly defined ROI targets.</p>
        <a href="#contact" class="btn btn-outline btn-block mb-4 plan-select" data-plan="Performance Scale, Accelerator">Get Started</a>
        <ul class="features">
          <li>Meta &amp; Google Ads Management</li>
          <li>Custom Attribution Dashboard</li>
          <li>High-Conv. Landing Pages</li>
          <li>Creative Strategy &amp; Testing</li>
          <li>Weekly Growth Sprints</li>
        </ul>
      </div>

      <!-- Organic Growth, centre / featured -->
      <div class="price-card popular">
        <div class="pop-tag">Most Popular</div>
        <div class="price-service-label">Organic Growth</div>
        <h3 class="price-card-name">SEO Engine</h3>
        <p class="desc">Long-term organic traffic compounds and builds sustainable market authority.</p>
        <a href="#contact" class="btn btn-primary btn-block mb-4 plan-select" data-plan="Organic Growth, SEO Engine">Get Started</a>
        <ul class="features">
          <li>Revenue-focused Keyword Research</li>
          <li>Technical SEO Governance</li>
          <li>Semantic Content Strategy</li>
          <li>Authority &amp; Backlink Sourcing</li>
          <li>AI &amp; Search Generative Opt.</li>
        </ul>
      </div>

      <!-- Revenue CRO -->
      <div class="price-card">
        <div class="price-service-label">Optimization</div>
        <h3 class="price-card-name">Revenue CRO</h3>
        <p class="desc">Turn more of your existing traffic into measurable top-line revenue.</p>
        <a href="#contact" class="btn btn-outline btn-block mb-4 plan-select" data-plan="Revenue CRO">Get Started</a>
        <ul class="features">
          <li>Full-Funnel Friction Audit</li>
          <li>A/B Testing Implementation</li>
          <li>Advanced Analytics (GA4/GTM)</li>
          <li>UX &amp; UI Refinement</li>
          <li>LTV &amp; Retention Strategy</li>
        </ul>
      </div>

    </div>

    <!-- Growth Suite Banner -->
    <div class="suite-banner mt-5">
      <div class="suite-banner-left">
        <div class="suite-eyebrow">THE COMPLETE</div>
        <h3 class="suite-title">GROWTH SUITE</h3>
        <p class="suite-desc">SEO + PPC + CRO + Attribution, fully unified. One strategy. One partner. One revenue goal. Built for brands ready to cross the ₹100Cr mark.</p>
      </div>
      <div class="suite-banner-right">
        <a href="#contact" class="btn btn-white plan-select" data-plan="Growth Suite, All-in-One">Start the Suite &rarr;</a>
      </div>
    </div>

  </div>
</section>

<!-- FREE STRATEGY CALL CTA -->
<section class="free-post-cta text-center" style="padding: 6rem 0;">
  <div class="container border-top pt-5">
    <h2 style="font-size: 2.8rem; margin-bottom: 1.5rem;">Not sure where to start?<br>Book a free SEO strategy call.</h2>
    <p class="lead mt-3 mx-auto max-w-700" style="font-size: 1.15rem; color: var(--text-light);">In 30 minutes, I’ll audit your current organic presence and hand you a clear, actionable roadmap, no fluff, no pitch, just strategy.</p>
    <a href="#contact" class="btn btn-primary mt-4">Book My Free Strategy Call &rarr;</a>
  </div>
</section>

<!-- PROCESS -->
<section class="process-section section-gray pb-5">
  <div class="container pt-5">
    <div class="section-head text-center">
      <div class="tag text-orange">My Process</div>
      <h2 style="font-size: 2.8rem;">HOW I BUILD YOUR<br>REVENUE ENGINE.</h2>
    </div>
    <div class="process-steps mt-5">
      <div class="step">
        <div class="step-num" style="position:relative;top:0;">01</div>
        <div class="step-label">DISCOVER</div>
        <h4>Deep Diagnostic</h4>
        <p>I audit your SEO, paid accounts, funnels, and analytics. I find waste, gaps, and the highest-leverage opportunities before you spend a rupee.</p>
      </div>
      <div class="step">
        <div class="step-num" style="position:relative;top:0;">02</div>
        <div class="step-label">DESIGN</div>
        <h4>Growth Blueprint</h4>
        <p>A custom 90-day roadmap built around your unit economics: CAC, LTV, and MER targets—and the channels most likely to hit them.</p>
      </div>
      <div class="step">
        <div class="step-num" style="position:relative;top:0;">03</div>
        <div class="step-label">DEPLOY</div>
        <h4>Full Execution</h4>
        <p>Campaigns, content, and conversion work go live—all integrated, all tracked, and all accountable to the same revenue goal.</p>
      </div>
      <div class="step">
        <div class="step-num" style="position:relative;top:0;">04</div>
        <div class="step-label">SCALE</div>
        <h4>Compound &amp; Win</h4>
        <p>Weekly data loops drive continuous optimization. What works gets scaled. What doesn't gets cut. Growth compounds every month.</p>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA FORM -->
<section class="final-cta" id="contact" style="padding: 6rem 0; background: var(--gray-bg);">
  <div class="container" style="max-width: 1100px;">
    
    <div class="cta-header text-center" style="margin-bottom: 4rem;">
      <div class="tag" style="margin: 0 auto 1.5rem; background: var(--orange); color: #fff; border-color: var(--orange);">Direct Consultation</div>
      <h2 style="font-size: clamp(2.5rem, 5vw, 4.5rem); font-family: var(--font-display); color: var(--text-dark); margin-bottom: 0.8rem; line-height: 1.1;">Stop Guessing. Start Scaling.</h2>
    </div>

    <div class="cta-form-box" style="padding: 4rem; box-shadow: 0 40px 80px rgba(15,15,15,0.06); max-width: 100%;">
      <form class="utm-form form-horizontal-grid" id="form-contact" method="POST" action="success.php">
        <div class="form-inline-msg" id="msg-contact"></div>
        <input type="hidden" name="form_type" value="contact">
        <input type="hidden" name="utm_url" class="utm-url">
        <input type="hidden" name="utm_source" class="utm-source">
        <input type="hidden" name="utm_medium" class="utm-medium">
        <input type="hidden" name="utm_campaign" class="utm-campaign">
        <input type="hidden" name="utm_content" class="utm-content">
        <input type="hidden" name="pricing_plan" class="pricing-plan">
        
        <div class="form-row-3">
          <input type="text" name="name" placeholder="Full Name" required style="margin-bottom: 0;">
          <input type="email" name="email" placeholder="Work Email" required style="margin-bottom: 0;">
          <input type="tel" name="phone" placeholder="Phone Number" style="margin-bottom: 0;">
        </div>
        
        <div class="form-row-2">
          <select name="challenge" required style="margin-bottom: 0;">
            <option value="" disabled selected>Select Primary Challenge</option>
            <option>High Customer Acquisition Cost (CAC)</option>
            <option>Stagnant Organic Traffic</option>
            <option>Low Conversion Rates</option>
            <option>Scattered Attribution</option>
          </select>
          <input type="text" name="goal" placeholder="Tell me about your 90-day revenue goal" style="margin-bottom: 0;">
        </div>

        <input type="url" name="website" placeholder="Your Website URL (e.g. https://sumomedia.in)">

        <?= captcha_html() ?>
        <div class="text-center" style="margin-top: 0.5rem;">
          <button type="submit" class="btn btn-primary" style="padding: 1.25rem 4rem; font-size: 1.1rem; width: auto;">Get My Free Growth Roadmap</button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section section-gray" style="padding-top: 4rem; padding-bottom: 4rem;">
  <div class="container grid-2 align-center">
    <div class="nl-text">
      <div class="tag text-orange">Pulse Intelligence</div>
      <h2 style="font-size: 2.5rem; color: var(--text-dark);">The Growth Signal.</h2>
      <p class="opacity-70">Deep-dive growth strategies and market attribution insights delivered to your inbox every fortnight. No fluff. Just data.</p>
    </div>
    <div class="nl-form">
      <form method="POST" action="newsletter.php" id="nl-form-main">
        <div class="form-inline-msg" id="msg-nl-main"></div>
        <div class="d-flex" style="margin-bottom:0.6rem;">
          <input type="email" name="email" placeholder="Enter your work email" required class="nl-input" style="margin-bottom:0;">
          <input type="hidden" name="source"       value="homepage-newsletter">
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
  // ── UTM capture ──────────────────────────────────────────────────────────
  const params  = new URLSearchParams(window.location.search);
  const utmKeys = ['source', 'medium', 'campaign', 'content', 'term'];

  // Persist UTMs in sessionStorage so they survive anchor-scroll navigation
  utmKeys.forEach(k => {
    const val = params.get('utm_' + k);
    if (val) sessionStorage.setItem('utm_' + k, val);
  });
  if (params.toString()) {
    sessionStorage.setItem('utm_url', window.location.href);
  }

  function fillForms() {
    document.querySelectorAll('.utm-form').forEach(form => {
      form.querySelector('.utm-url').value      = sessionStorage.getItem('utm_url')      || window.location.href;
      form.querySelector('.utm-source').value   = sessionStorage.getItem('utm_source')   || '';
      form.querySelector('.utm-medium').value   = sessionStorage.getItem('utm_medium')   || '';
      form.querySelector('.utm-campaign').value = sessionStorage.getItem('utm_campaign') || '';
      form.querySelector('.utm-content').value  = sessionStorage.getItem('utm_content')  || '';
    });
    // Newsletter forms
    document.querySelectorAll('[class*="nl-page_url"]').forEach(el  => el.value = sessionStorage.getItem('utm_url')      || window.location.href);
    document.querySelectorAll('[class*="nl-utm_source"]').forEach(el   => el.value = sessionStorage.getItem('utm_source')   || '');
    document.querySelectorAll('[class*="nl-utm_medium"]').forEach(el   => el.value = sessionStorage.getItem('utm_medium')   || '');
    document.querySelectorAll('[class*="nl-utm_campaign"]').forEach(el => el.value = sessionStorage.getItem('utm_campaign') || '');
    document.querySelectorAll('[class*="nl-utm_content"]').forEach(el  => el.value = sessionStorage.getItem('utm_content')  || '');
    document.querySelectorAll('[class*="nl-utm_term"]').forEach(el     => el.value = params.get('utm_term') || sessionStorage.getItem('utm_term') || '');
  }
  fillForms();

  // ── Pricing plan selection ───────────────────────────────────────────────
  // Persist selected plan in sessionStorage
  function setPlan(plan) {
    sessionStorage.setItem('pricing_plan', plan);
    document.querySelectorAll('.utm-form .pricing-plan').forEach(el => el.value = plan);
  }

  // Restore plan if already chosen
  const savedPlan = sessionStorage.getItem('pricing_plan');
  if (savedPlan) {
    document.querySelectorAll('.utm-form .pricing-plan').forEach(el => el.value = savedPlan);
  }

  document.querySelectorAll('.plan-select').forEach(btn => {
    btn.addEventListener('click', () => setPlan(btn.dataset.plan));
  });
})();
</script>

<script>
/* ── Inline AJAX form handler ──────────────────────────────────────────── */
(function () {
  function showMsg(msgEl, type, html) {
    msgEl.className = 'form-inline-msg is-' + type;
    msgEl.innerHTML = html;
    msgEl.style.display = 'block';
    msgEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function wireForm(formEl, msgEl, opts) {
    formEl.addEventListener('submit', async function (e) {
      e.preventDefault();
      msgEl.style.display = 'none';

      const btn = formEl.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = 'Sending…';

      try {
        const res  = await fetch(formEl.action, {
          method: 'POST',
          body: new FormData(formEl),
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error('[Form] HTTP ' + res.status + ' — raw response:', text);
          showMsg(msgEl, 'error', 'Server error (HTTP ' + res.status + '). Open browser console (F12) to see details.');
          btn.disabled = false; btn.innerHTML = orig;
          return;
        }

        if (data.ok) {
          if (opts.onSuccess) {
            opts.onSuccess(formEl, msgEl, data);
          } else {
            showMsg(msgEl, 'success', opts.successMsg || 'Done!');
            formEl.reset();
          }
        } else {
          showMsg(msgEl, 'error', data.error || 'Something went wrong. Please try again.');
        }
      } catch (e) {
        console.error('[Form] Fetch failed:', e);
        // Fallback to native submission if AJAX fails (e.g. adblocker blocking utm_* payload, or file:// protocol)
        msgEl.style.display = 'none';
        btn.innerHTML = 'Sending…';
        formEl.submit();
        return;
      }

      btn.disabled = false;
      btn.innerHTML = orig;
    });
  }

  // ── Hero audit form ────────────────────────────────────────────────────
  const fAudit = document.getElementById('form-audit');
  const mAudit = document.getElementById('msg-audit');
  if (fAudit && mAudit) {
    wireForm(fAudit, mAudit, {
      onSuccess(form, msg, data) {
        window.location.href = 'success.php?submitted=1&name=' + encodeURIComponent(data.name || '');
      }
    });
  }

  // ── Contact / CTA form ─────────────────────────────────────────────────
  const fContact = document.getElementById('form-contact');
  const mContact = document.getElementById('msg-contact');
  if (fContact && mContact) {
    wireForm(fContact, mContact, {
      onSuccess(form, msg, data) {
        window.location.href = 'success.php?submitted=1&name=' + encodeURIComponent(data.name || '');
      }
    });
  }

  // ── Homepage newsletter ────────────────────────────────────────────────
  const fNl = document.getElementById('nl-form-main');
  const mNl = document.getElementById('msg-nl-main');
  if (fNl && mNl) {
    wireForm(fNl, mNl, {
      onSuccess(form, msg) {
        showMsg(msg, 'success', "You're in! Expect sharp growth insights every fortnight.");
        form.reset();
      }
    });
  }
})();
</script>
</body>
</html>
