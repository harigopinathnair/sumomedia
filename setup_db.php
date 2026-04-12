<?php
$host     = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS harigopinathfi26 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'harigopinathfi26' ready.\n";
    $pdo->exec("USE harigopinathfi26");

    // Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id         INT(11)      AUTO_INCREMENT PRIMARY KEY,
        username   VARCHAR(50)  NOT NULL UNIQUE,
        password   VARCHAR(255) NOT NULL,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users' ready.\n";

    // Posts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id         INT(11)      AUTO_INCREMENT PRIMARY KEY,
        title      VARCHAR(255) NOT NULL,
        slug       VARCHAR(191) NOT NULL UNIQUE,
        category   VARCHAR(100) NOT NULL DEFAULT 'General',
        excerpt    TEXT,
        content    LONGTEXT,
        image_url  VARCHAR(500),
        status     ENUM('draft','published') NOT NULL DEFAULT 'draft',
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "Table 'posts' ready.\n";

    // Seed admin user
    $user        = 'harigopinath';
    $pass        = '9884208016@#';
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    $stmt        = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$user]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")->execute([$user, $hashed_pass]);
        echo "Admin user '$user' created.\n";
    } else {
        echo "Admin user '$user' already exists.\n";
    }

    // Seed posts
    $posts = [
        [
            'title'     => 'How We Scaled SaaS Traffic from 25k to 500k in 12 Months',
            'slug'      => 'scale-saas-traffic-25k-500k-12-months',
            'category'  => 'SEO Strategy',
            'excerpt'   => 'A complete breakdown of the keyword clustering strategy and programmatic SEO architecture that drove massive pipeline growth.',
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-10-14 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">When this particular SaaS client came to us, they had a decent organic foundation — pulling around 25,000 monthly visits. But growth had flatlined for six consecutive months. Here is the exact programmatic architecture and clustering strategy we deployed to 20x their traffic in just one year.</p>

<img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="SEO Growth Graph" />

<h2>The Plateau Problem</h2>
<p>The issue was not that they were not producing content — it was that their content lacked intent-driven structural alignment. They were writing generic "ultimate guides" instead of targeting bottom-of-funnel comparable keywords that actually drive pipeline conversions.</p>

<blockquote>"Traffic without revenue attribution is just a vanity metric. If the graph goes up but the pipeline stays flat, you have a conversion problem, not a traffic problem."</blockquote>

<h2>Step 1: The Keyword Audit &amp; Clustering</h2>
<p>Our first move was entirely destroying their existing editorial calendar. Instead of focusing on arbitrary search volume, we clustered keywords by intent frameworks:</p>
<ul>
  <li>High-intent software comparisons (e.g., "Best Alternative to X")</li>
  <li>Vertical-specific programmatic use cases</li>
  <li>Data-density pages leveraging their product\'s existing backend API</li>
</ul>

<h2>Step 2: Programmatic Scaling Architecture</h2>
<p>We leveraged a Headless CMS approach to build out 300+ unique landing pages targeting hyper-niche integrations. Because we didn\'t write these manually, we maintained consistency while scaling rapidly without bloating the budget.</p>

<h2>The Revenue Result</h2>
<p>By month 6, we broke the 150k barrier. By month 12, we organically crossed 500k monthly visits — driving a 415% increase in highly qualified demo requests. Not magic. Just rigorous revenue engineering combined with data attribution.</p>',
        ],
        [
            'title'     => 'Stop Wasting Ad Spend: The Attribution Crisis',
            'slug'      => 'stop-wasting-ad-spend-attribution-crisis',
            'category'  => 'PPC & Paid Ads',
            'excerpt'   => 'Why your dashboards are lying to you, and how to build a unified revenue engine that actually tracks MER and CAC accurately.',
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-09-28 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">Your Google Ads dashboard says one thing. Your Meta Ads dashboard says another. Your CRM tells a third story. This is the attribution crisis — and it is costing brands millions in misallocated spend every single month.</p>

<h2>Why Every Channel Claims the Win</h2>
<p>Last-click attribution is the default for most platforms. That means Google, Meta, and every other channel are all claiming credit for the same conversion. The result? Your reported ROAS looks great on paper but your actual Marketing Efficiency Ratio (MER) tells a very different story.</p>

<blockquote>"When every channel claims 100% of the credit, you end up with 400% of the conversions — none of which are real."</blockquote>

<h2>The MER-First Approach</h2>
<p>MER (Marketing Efficiency Ratio) is simple: Total Revenue ÷ Total Ad Spend. It does not lie. It does not care about channel-level attribution models. It just tells you whether your overall marketing investment is generating returns.</p>
<ul>
  <li>Calculate MER weekly, not monthly</li>
  <li>Set MER targets before allocating budget</li>
  <li>Use MER as the kill switch for underperforming channels</li>
</ul>

<h2>Building a Unified Revenue Engine</h2>
<p>The fix requires combining server-side tracking, a single source of truth CRM, and incrementality testing. No shortcut exists — but the payoff is knowing exactly which spend is generating real returns.</p>',
        ],
        [
            'title'     => 'The "Original Research" Backlink Strategy',
            'slug'      => 'original-research-backlink-strategy',
            'category'  => 'Content Creation',
            'excerpt'   => 'How to attract high DR backlinks automatically by doing simple, targeted data research and presenting it properly.',
            'image_url' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-09-12 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">Outreach-based link building is expensive, time-consuming, and increasingly ineffective. The highest-leverage backlink strategy I\'ve found costs almost nothing: original data research that journalists and bloggers want to cite.</p>

<h2>Why Original Data Earns Links Passively</h2>
<p>Journalists and content creators are constantly looking for statistics to cite. If you publish a piece of research with a compelling stat — say, "72% of B2B SaaS companies spend less than 10% of their budget on SEO" — that number becomes a citation magnet. Every piece that references it links back to you.</p>

<blockquote>"One well-placed statistic from original research can earn more backlinks in a year than 100 outreach emails."</blockquote>

<h2>How to Do the Research (Without a Budget)</h2>
<ul>
  <li>Survey your existing customers (even 50-100 responses is enough)</li>
  <li>Mine public datasets (Statista free tier, government data, industry reports)</li>
  <li>Analyse patterns in your own platform data (anonymised)</li>
  <li>Run a simple LinkedIn poll and present the aggregate results</li>
</ul>

<h2>Presenting it to Maximise Citation Value</h2>
<p>Format matters. Present your findings in a dedicated, easily-linkable URL. Use clean data visualisations. Write a clear "Key Findings" summary at the top. Make it trivial for a journalist to extract a quote and link to you as the source.</p>',
        ],
        [
            'title'     => '3 Landing Page Friction Points Killing Your Conversions',
            'slug'      => 'landing-page-friction-points-killing-conversions',
            'category'  => 'CRO',
            'excerpt'   => 'Small layout adjustments that drastically reduce bounce rates and increase demo bookings for B2B SaaS platforms.',
            'image_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-08-30 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">Most landing page audits focus on copy or design aesthetics. But the biggest conversion killers are structural friction points that most marketers never notice — until they see the heatmaps.</p>

<h2>Friction Point 1: The Form Above the Value</h2>
<p>Placing a lead capture form before the user has understood your value proposition is the single most common conversion mistake. Users who don\'t yet trust you will not fill out your form. Move the proof first: a testimonial, a stat, a case study headline — then the form.</p>

<h2>Friction Point 2: Too Many Fields</h2>
<p>Every additional form field reduces conversions by roughly 4-8%. For a discovery call, you need: name, email, and optionally a phone number. Budget and company size can be captured in the follow-up. The goal is to get the lead, not profile them.</p>

<blockquote>"Your form is not a qualification tool. It is a door. Make the door as easy to open as possible."</blockquote>

<h2>Friction Point 3: No Directional Cues</h2>
<p>Heat mapping consistently shows users bouncing because they do not know what to do next. Directional cues — a pointed arrow, a bold CTA contrast, whitespace that creates visual flow — tell the eye where to go. Add them. Test them. They work.</p>

<h2>Quick Wins to Implement Today</h2>
<ul>
  <li>Reduce form fields to three maximum</li>
  <li>Place your strongest social proof immediately above the CTA</li>
  <li>Add a single directional arrow pointing at your primary button</li>
  <li>Test a sticky CTA bar for mobile users</li>
</ul>',
        ],
        [
            'title'     => 'Why Measuring Vanity Metrics is Destroying Agencies',
            'slug'      => 'vanity-metrics-destroying-agencies',
            'category'  => 'Leadership',
            'excerpt'   => 'If your agency sends monthly reports full of impressions without mentioning revenue, firing them might be the best decision you can make.',
            'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-08-15 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">There is a quiet epidemic in digital marketing. Agencies across the world are sending monthly reports packed with impressive-looking numbers — page views, impressions, follower counts — while their clients\' actual revenue remains flat or declining.</p>

<h2>The Vanity Metric Trap</h2>
<p>Impressions, reach, and organic clicks are not inherently bad metrics. The problem is when they are used as primary KPIs in isolation from revenue. An agency reporting "500,000 impressions this month" without any connection to pipeline or revenue is essentially reporting nothing.</p>

<blockquote>"A metric that cannot be connected to a business outcome is not a metric. It is noise."</blockquote>

<h2>What to Measure Instead</h2>
<p>The metrics that actually matter for growth:</p>
<ul>
  <li><strong>Marketing Efficiency Ratio (MER)</strong> — total revenue divided by total marketing spend</li>
  <li><strong>Customer Acquisition Cost (CAC)</strong> — broken down by channel</li>
  <li><strong>Pipeline Generated</strong> — qualified leads with a realistic close probability</li>
  <li><strong>Revenue Attributed</strong> — not just last-click, but incrementally tested</li>
</ul>

<h2>How to Have the Conversation</h2>
<p>Ask your agency one question: "What was the direct revenue impact of your work last month?" If they cannot answer it — or pivot to traffic numbers — you have your answer about whether this is the right partnership.</p>',
        ],
        [
            'title'     => 'Preparing Your Brand for AI-Driven Search',
            'slug'      => 'preparing-brand-for-ai-driven-search',
            'category'  => 'AI Search',
            'excerpt'   => 'How LLM-driven search engines fundamentally change content strategy, and what you need to do immediately to remain visible.',
            'image_url' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-08-02 09:00:00',
            'content'   => '<p class="lead" style="font-size:1.3rem;font-weight:500;color:var(--text-dark);margin-bottom:2rem;line-height:1.8;">Google\'s AI Overviews, Perplexity, ChatGPT Search — the way people find information is undergoing its biggest shift since the mid-2000s. Brands that adapt their content strategy now will own the next decade of organic visibility.</p>

<h2>How AI Search Differs From Traditional SEO</h2>
<p>Traditional SEO is about ranking in a list of blue links. AI-driven search is about being cited as a source within a synthesised answer. The algorithm no longer just measures keyword relevance — it evaluates trustworthiness, depth, and how often your content is referenced by other authoritative sources.</p>

<blockquote>"In AI search, you are not competing to rank #1. You are competing to be cited as the most credible source."</blockquote>

<h2>What Changes in Your Content Strategy</h2>
<ul>
  <li><strong>Go deep, not broad</strong> — Comprehensive, expert-level articles beat thin content even more decisively in AI results</li>
  <li><strong>Build entity authority</strong> — Be consistently mentioned alongside the core topics in your niche</li>
  <li><strong>Original data is critical</strong> — AI systems preferentially cite primary sources over aggregators</li>
  <li><strong>Structured data matters more</strong> — Schema markup helps AI parse and attribute your content correctly</li>
</ul>

<h2>The Actions to Take Now</h2>
<p>Audit your top 20 pages. Do they answer the full question a searcher might have? Do they cite primary sources? Do they include original data or perspectives? If not, those pages will lose visibility as AI search scales. The window to fix them is open — but it is closing fast.</p>',
        ],
    ];

    $checkStmt  = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
    $insertStmt = $pdo->prepare("INSERT INTO posts (title, slug, category, excerpt, content, image_url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($posts as $p) {
        $checkStmt->execute([$p['slug']]);
        if ($checkStmt->fetchColumn() == 0) {
            $insertStmt->execute([$p['title'], $p['slug'], $p['category'], $p['excerpt'], $p['content'], $p['image_url'], $p['status'], $p['created_at']]);
            echo "Post '{$p['title']}' seeded.\n";
        } else {
            echo "Post '{$p['slug']}' already exists.\n";
        }
    }

    echo "\n✓ Setup complete. Visit /admin/login.php to manage posts.\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
