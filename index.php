<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HariGopinath.com | Performance Digital Marketing & SEO</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css">
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
        <div class="tag" style="border-color: rgba(255,255,255,0.2); color: #fff; margin-bottom: 0;">Performance SEO &amp; Growth</div>
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
      <form class="audit-form utm-form" method="POST" action="success.php">
        <input type="hidden" name="form_type" value="audit">
        <input type="hidden" name="utm_url" class="utm-url">
        <input type="hidden" name="utm_source" class="utm-source">
        <input type="hidden" name="utm_medium" class="utm-medium">
        <input type="hidden" name="utm_campaign" class="utm-campaign">
        <input type="hidden" name="utm_content" class="utm-content">
        <input type="hidden" name="pricing_plan" class="pricing-plan">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Work Email" required>
        <input type="tel" name="phone" placeholder="Phone / WhatsApp">
        <select name="budget" required>
          <option value="" disabled selected>Monthly Budget</option>
          <option>Under ₹1L</option>
          <option>₹1L - ₹5L</option>
          <option>₹5L+</option>
        </select>
        <input type="text" name="goal" placeholder="Primary Goal">
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
  <div class="container text-center mt-5">
    <div style="font-size:2rem;">👇</div>
  </div>
</section>

<!-- PAIN POINTS -->
<section class="pain-points section-white" style="padding-top: 5rem;">
  <div class="container grid-2 align-center">
    <div class="pain-title">
      <div class="tag">The Opportunity</div>
      <h2 style="font-size: 3rem; margin-bottom: 0;">Turn Your Marketing<br>Into A Predictable<br>Revenue Engine.</h2>
    </div>
    <div class="pain-list">
      <ul>
        <li>You're spending more on ads but your profit margins keep shrinking</li>
        <li>SEO traffic is up, but qualified leads haven't moved</li>
        <li>Monthly reports are full of impressions and clicks — not revenue</li>
        <li>No one can tell you which channel is actually driving growth</li>
        <li>PPC, SEO, and content teams operate in total silos</li>
        <li>You've outgrown your agency but don't know where to go next</li>
      </ul>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="what-we-do section-navy">
  <div class="container text-center text-white">
    <div class="tag text-orange">What I do</div>
    <h2 class="text-white" style="font-size:2.8rem; margin-bottom: 1.5rem;">I help ambitious brands grow their SEO traffic</h2>
    <p class="max-w-700 opacity-70 mx-auto" style="font-size:1.1rem; margin-bottom:15px; padding-bottom:15px;">Most brands pour budget into ads, sponsorships, email, and social — yet very few build the one channel that compounds over time and costs nothing to maintain. In my experience, the brands that consistently produce high-quality, SEO-optimized content are the ones with the most resilient traffic — no matter what else they're spending on.</p>
    
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
      <img src="uploads/casestudy-01-img.png" alt="Google Search Console — clicks & impressions growth chart" class="gsc-chart-img">
    </div>

    <!-- Insights -->
    <div class="gsc-insights mt-5">
      <div class="gsc-insight">
        <div class="gsc-insight-icon">&#128200;</div>
        <div>
          <h4>Traffic compounded 67% in 6 months</h4>
          <p>Clicks grew from 10.8K to 18.1K — without increasing ad spend. Pure organic compounding driven by intent-matched content and technical SEO.</p>
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
          <h4>243K impressions — brand visibility at scale</h4>
          <p>Impressions surged 70% in six months, meaning the brand now appears in search results for 70% more queries than before — building authority across the entire funnel.</p>
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
        <p class="case-study-desc">A large-scale e-commerce brand with an established catalogue. We rebuilt their SEO architecture from the ground up — category pages, PDP optimisation, and a programmatic content layer that compounded traffic month over month.</p>
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
            <h4>12.7M clicks — 3&times; growth in 6 months</h4>
            <p>Clicks surged from 4.09M to 12.7M in a single period — driven by category-level keyword clustering and product page schema that unlocked rich results.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#127760;</div>
          <div>
            <h4>951M impressions — top-of-funnel dominance</h4>
            <p>Nearly 1 billion search impressions in 6 months. The brand now appears for virtually every commercial intent query in their vertical — building brand recall before the click.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#128200;</div>
          <div>
            <h4>Position 10.5 → 7.7 — page 1 consolidation</h4>
            <p>A 27% improvement in average position pushed hundreds of product and category pages from the bottom of page 1 into the top 5 — where 75% of all clicks happen.</p>
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
            <h4>Clicks up 81% — from 679 to 1.23K</h4>
            <p>By targeting high-intent recipe and product comparison keywords, organic clicks nearly doubled without any increase in ad spend — purely through content and on-page optimisation.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#128269;</div>
          <div>
            <h4>58.2K impressions — 64% more visibility</h4>
            <p>Impressions grew from 35.4K to 58.2K as new content covered a wider range of buyer queries across the funnel — from awareness-stage recipes to bottom-of-funnel product searches.</p>
          </div>
        </div>
        <div class="gsc-insight">
          <div class="gsc-insight-icon">&#127942;</div>
          <div>
            <h4>Position 14.5 → 10.2 — crossed onto page 1</h4>
            <p>Average position improved by 30%, moving the brand from deep page 2 to the first page of Google. A single page boundary shift that transforms click-through rates overnight.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- EXTENDED ABOUT (Who am I?) -->
<section class="extended-about" id="about">
  <div class="container">
    <div class="tag">Who am I?</div>
    <div class="grid-2 align-start mt-3">
      <div class="about-left">
        <img src="uploads/hari-photo.png" alt="Hari Gopinath" class="about-photo" style="-webkit-transform:scaleX(-1);transform:scaleX(-1);padding:30px;">
        <div class="founder-stats grid-2 mt-4 pt-4 border-top">
          <div class="f-stat"><div class="num">1,000+</div><div class="label">Blog posts published</div></div>
          <div class="f-stat"><div class="num">3.6M+</div><div class="label">Organic clicks generated</div></div>
          <div class="f-stat"><div class="num">$16.2M+</div><div class="label">Generated in traffic value</div></div>
          <div class="f-stat"><div class="num">∞</div><div class="label">Cups of coffee drank</div></div>
        </div>
      </div>
      <div class="about-right" style="padding-top: 1rem;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">Hey there 👋!</h2>
        <p class="lead text-dark" style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1.5rem;">I'm Hari — an artist, writer ✍️, marketer 📈, and absolute SEO fanatic 💻.</p>
        <p class="mb-3" style="font-size: 1.05rem; color: var(--text-light);">After an incredible run leading SEO and content marketing strategies for massive brands across India, the UAE, and the US, I channeled all my core learnings into building this dedicated growth engine.</p>
        <p class="mb-3" style="font-size: 1.05rem; color: var(--text-light);">In recent years, my strategies have reached millions of readers. Along the way, I engineered a highly repeatable, battle-tested framework for ambitious brands to consistently scale their revenue through organic search.</p>
        <p class="mb-3" style="font-size: 1.05rem; color: var(--text-light);">I live and breathe content and performance marketing. By staying deeply involved in an active growth community, my finger is always on the pulse of industry shifts — especially when navigating the future of SEO and the rise of AI-driven search.</p>

        <p class="mb-4" style="font-size: 1.05rem; color: var(--text-light);">At best, we build a seamless partnership to launch and aggressively scale your organic growth. At worst, you walk away from our strategy sessions equipped with an exact, actionable roadmap to dominate your market.</p>
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
<section class="offer-section border-top">
  <div class="container text-center">
    <div class="tag">Why Hari Gopinath</div>
    <h2 style="font-size: 2.8rem; margin-bottom: 2rem;">Not your typical SEO/content<br>marketing agency</h2>
    
    <div class="grid-2 mt-5 text-left gap-5">
      <div class="feature-row">
        <h4>No contracts</h4>
        <p>Say goodbye to monthly retainers. I am an SEO strategy expert that does not tie you down into a contract. If you don't like my approach, you can easily part ways.</p>
      </div>
      <div class="feature-row">
        <h4>More than just content</h4>
        <p>All of my packages include backlinks. This means you're not only getting great content, but also mentions on other websites that will grow your domain authority and traffic — key for ranking in AI search and ChatGPT.</p>
      </div>
      <div class="feature-row">
        <h4>In good hands</h4>
        <p>Your account is handled directly by me, an SEO and content expert who has taken several projects to hundreds of thousands of visitors a month.</p>
      </div>
      <div class="feature-row">
        <h4>A focus on SEO</h4>
        <p>Each article you receive is SEO-optimized and written for humans and search engines. I've released over 1,000 blog posts and have found the right way to write for Google (so you can also show up in AI search engines).</p>
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
        <a href="#contact" class="btn btn-outline btn-block mb-4 plan-select" data-plan="Performance Scale — Accelerator">Get Started</a>
        <ul class="features">
          <li>Meta &amp; Google Ads Management</li>
          <li>Custom Attribution Dashboard</li>
          <li>High-Conv. Landing Pages</li>
          <li>Creative Strategy &amp; Testing</li>
          <li>Weekly Growth Sprints</li>
        </ul>
      </div>

      <!-- Organic Growth — centre / featured -->
      <div class="price-card popular">
        <div class="pop-tag">Most Popular</div>
        <div class="price-service-label">Organic Growth</div>
        <h3 class="price-card-name">SEO Engine</h3>
        <p class="desc">Long-term organic traffic compounds and builds sustainable market authority.</p>
        <a href="#contact" class="btn btn-primary btn-block mb-4 plan-select" data-plan="Organic Growth — SEO Engine">Get Started</a>
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
        <p class="suite-desc">SEO + PPC + CRO + Attribution — fully unified. One strategy. One partner. One revenue goal. Built for brands ready to cross the ₹100Cr mark.</p>
      </div>
      <div class="suite-banner-right">
        <a href="#contact" class="btn btn-white plan-select" data-plan="Growth Suite — All-in-One">Start the Suite &rarr;</a>
      </div>
    </div>

  </div>
</section>

<!-- FREE STRATEGY CALL CTA -->
<section class="free-post-cta text-center" style="padding: 6rem 0;">
  <div class="container border-top pt-5">
    <h2 style="font-size: 2.8rem; margin-bottom: 1.5rem;">Not sure where to start?<br>Book a free SEO strategy call.</h2>
    <p class="lead mt-3 mx-auto max-w-700" style="font-size: 1.15rem; color: var(--text-light);">In 30 minutes, I’ll audit your current organic presence and hand you a clear, actionable roadmap — no fluff, no pitch, just strategy.</p>
    <a href="#contact" class="btn btn-primary mt-4">Book My Free Strategy Call &rarr;</a>
  </div>
</section>

<!-- PROCESS -->
<section class="process-section section-navy text-white pb-5">
  <div class="container pt-5">
    <div class="section-head text-center">
      <div class="tag text-orange">My Process</div>
      <h2 class="text-white" style="font-size: 2.8rem;">HOW I BUILD YOUR<br>REVENUE ENGINE.</h2>
    </div>
    <div class="process-steps mt-5">
      <div class="step">
        <div class="step-num text-white opacity-50" style="position:relative;top:0;">01</div>
        <div class="step-label">DISCOVER</div>
        <h4>Deep Diagnostic</h4>
        <p class="text-white opacity-70">I audit your SEO, paid accounts, funnels, and analytics. I find waste, gaps, and the highest-leverage opportunities before you spend a rupee.</p>
      </div>
      <div class="step">
        <div class="step-num text-white opacity-50" style="position:relative;top:0;">02</div>
        <div class="step-label">DESIGN</div>
        <h4>Growth Blueprint</h4>
        <p class="text-white opacity-70">A custom 90-day roadmap built around your unit economics: CAC, LTV, and MER targets—and the channels most likely to hit them.</p>
      </div>
      <div class="step">
        <div class="step-num text-white opacity-50" style="position:relative;top:0;">03</div>
        <div class="step-label">DEPLOY</div>
        <h4>Full Execution</h4>
        <p class="text-white opacity-70">Campaigns, content, and conversion work go live—all integrated, all tracked, and all accountable to the same revenue goal.</p>
      </div>
      <div class="step">
        <div class="step-num text-white opacity-50" style="position:relative;top:0;">04</div>
        <div class="step-label">SCALE</div>
        <h4>Compound &amp; Win</h4>
        <p class="text-white opacity-70">Weekly data loops drive continuous optimization. What works gets scaled. What doesn't gets cut. Growth compounds every month.</p>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA FORM -->
<section class="final-cta" id="contact">
  <div class="container grid-2 gap-5 align-center">
    <div class="cta-text">
      <div class="tag">Direct Strategist Access</div>
      <h2 style="font-size: 3.5rem;">Stop Guessing.<br>Start Scaling.</h2>
    </div>
    <div class="cta-form-box">
      <form class="utm-form" method="POST" action="success.php">
        <input type="hidden" name="form_type" value="contact">
        <input type="hidden" name="utm_url" class="utm-url">
        <input type="hidden" name="utm_source" class="utm-source">
        <input type="hidden" name="utm_medium" class="utm-medium">
        <input type="hidden" name="utm_campaign" class="utm-campaign">
        <input type="hidden" name="utm_content" class="utm-content">
        <input type="hidden" name="pricing_plan" class="pricing-plan">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Work Email" required>
        <input type="tel" name="phone" placeholder="Phone Number">
        <select name="challenge" required>
          <option value="" disabled selected>Select Primary Challenge</option>
          <option>High Customer Acquisition Cost (CAC)</option>
          <option>Stagnant Organic Traffic</option>
          <option>Low Conversion Rates</option>
          <option>Scattered Attribution</option>
        </select>
        <textarea name="goal" placeholder="Tell me about your 90-day revenue goal" rows="3"></textarea>
        <button type="submit" class="btn btn-primary btn-block">Get My Free Growth Roadmap</button>
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
      <form class="d-flex">
        <input type="email" placeholder="Enter your work email" required class="nl-input">
        <button type="submit" class="btn btn-orange">Subscribe Free</button>
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
  const utmKeys = ['source', 'medium', 'campaign', 'content'];

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
</body>
</html>
