<?php
/**
 * SumoMedia.in - Database Setup & Migration Utility
 * Consolidates all table structures and seeds initial data.
 */

// Detect environment to use correct credentials
$is_local = (($_SERVER['HTTP_HOST'] ?? '') === 'localhost' || empty($_SERVER['HTTP_HOST']));

if ($is_local) {
    $host     = 'localhost';
    $dbname   = 'sumomedia';
    $username = 'root';
    $password = '';
} else {
    // Live Server Credentials
    $host     = 'localhost';
    $dbname   = 'nqatsxqe_mediasumo26';
    $username = 'nqatsxqe_media26sumo';
    $password = 'Rankmonk_988@';
}

echo "Starting SumoMedia Database Setup...\n";
echo "Environment: " . ($is_local ? 'Localhost' : 'Production') . "\n";
echo "Database: $dbname\n\n";

try {
    // Connect to MySQL (without db selection first)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$dbname' ready.\n";
    $pdo->exec("USE `$dbname` ");

    // 2. Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id         INT(11)      AUTO_INCREMENT PRIMARY KEY,
        username   VARCHAR(50)  NOT NULL UNIQUE,
        password   VARCHAR(255) NOT NULL,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ Table 'users' ready.\n";

    // 3. Posts table (Blog)
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
    echo "✓ Table 'posts' ready.\n";

    // 4. Leads table (CRM)
    $pdo->exec("CREATE TABLE IF NOT EXISTS leads (
        id           INT(11)        AUTO_INCREMENT PRIMARY KEY,
        name         VARCHAR(255)   NOT NULL DEFAULT '',
        email        VARCHAR(255)   NOT NULL DEFAULT '',
        phone        VARCHAR(50)    NOT NULL DEFAULT '',
        source       VARCHAR(20)    NOT NULL DEFAULT 'contact',
        budget       VARCHAR(100)   NOT NULL DEFAULT '',
        goal         TEXT,
        challenge    VARCHAR(255)   NOT NULL DEFAULT '',
        status       VARCHAR(30)    NOT NULL DEFAULT 'new',
        value        DECIMAL(12,2)  NOT NULL DEFAULT 0,
        comment      TEXT,
        website      VARCHAR(500)   NOT NULL DEFAULT '',
        utm_url      VARCHAR(1000)  NOT NULL DEFAULT '',
        utm_source   VARCHAR(255)   NOT NULL DEFAULT '',
        utm_medium   VARCHAR(255)   NOT NULL DEFAULT '',
        utm_campaign VARCHAR(255)   NOT NULL DEFAULT '',
        utm_content  VARCHAR(255)   NOT NULL DEFAULT '',
        pricing_plan VARCHAR(255)   NOT NULL DEFAULT '',
        created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        updated_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Table 'leads' ready.\n";

    // 5. Subscribers table (Newsletter)
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id           INT(11)      AUTO_INCREMENT PRIMARY KEY,
        email        VARCHAR(191) NOT NULL UNIQUE,
        status       VARCHAR(20)  NOT NULL DEFAULT 'active',
        source       VARCHAR(100) NOT NULL DEFAULT 'website',
        page_url     VARCHAR(500) DEFAULT NULL,
        utm_source   VARCHAR(100) DEFAULT NULL,
        utm_medium   VARCHAR(100) DEFAULT NULL,
        utm_campaign VARCHAR(100) DEFAULT NULL,
        utm_content  VARCHAR(100) DEFAULT NULL,
        utm_term     VARCHAR(100) DEFAULT NULL,
        created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Table 'subscribers' ready.\n";

    // 6. Chat tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS chat_sessions (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        token      VARCHAR(64)  NOT NULL UNIQUE,
        name       VARCHAR(255) NOT NULL,
        email      VARCHAR(255) NOT NULL,
        phone      VARCHAR(100) DEFAULT NULL,
        location   VARCHAR(255) DEFAULT NULL,
        page_url   VARCHAR(500) DEFAULT NULL,
        status     ENUM('active','closed') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS chat_messages (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        session_id INT NOT NULL,
        sender     ENUM('user','admin') NOT NULL,
        message    TEXT NOT NULL,
        sent_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Chat tables ready.\n";

    // 7. Seed Admin User
    $user        = 'harigopinath';
    $pass        = '9884208016@#';
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    $stmt        = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$user]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")->execute([$user, $hashed_pass]);
        echo "✓ Admin user '$user' created.\n";
    } else {
        echo "i Admin user '$user' already exists.\n";
    }

    // 8. Seed Blog Posts
    $posts = [
        [
            'title'     => 'How We Scaled SaaS Traffic from 25k to 500k in 12 Months',
            'slug'      => 'scale-saas-traffic-25k-500k-12-months',
            'category'  => 'SEO Strategy',
            'excerpt'   => 'A complete breakdown of the keyword clustering strategy and programmatic SEO architecture that drove massive pipeline growth.',
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-10-14 09:00:00',
            'content'   => '<p class="lead">When this particular SaaS client came to us, they had a decent organic foundation... [Truncated for setup]</p><h2>The Plateau Problem</h2><p>The issue was not that they were not producing content...</p>',
        ],
        [
            'title'     => 'Stop Wasting Ad Spend: The Attribution Crisis',
            'slug'      => 'stop-wasting-ad-spend-attribution-crisis',
            'category'  => 'PPC & Paid Ads',
            'excerpt'   => 'Why your dashboards are lying to you, and how to build a unified revenue engine that actually tracks MER and CAC accurately.',
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-09-28 09:00:00',
            'content'   => '<p class="lead">Your Google Ads dashboard says one thing. Your Meta Ads dashboard says another...</p>',
        ],
        [
            'title'     => 'Preparing Your Brand for AI-Driven Search',
            'slug'      => 'preparing-brand-for-ai-driven-search',
            'category'  => 'AI Search',
            'excerpt'   => 'How LLM-driven search engines fundamentally change content strategy, and what you need to do immediately to remain visible.',
            'image_url' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'status'    => 'published',
            'created_at'=> '2026-08-02 09:00:00',
            'content'   => '<p class="lead">Google\'s AI Overviews, Perplexity, ChatGPT Search — the way people find information is shifting.</p>',
        ]
    ];

    $checkStmt  = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
    $insertStmt = $pdo->prepare("INSERT INTO posts (title, slug, category, excerpt, content, image_url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($posts as $p) {
        $checkStmt->execute([$p['slug']]);
        if ($checkStmt->fetchColumn() == 0) {
            $insertStmt->execute([$p['title'], $p['slug'], $p['category'], $p['excerpt'], $p['content'], $p['image_url'], $p['status'], $p['created_at']]);
            echo "✓ Post '{$p['title']}' seeded.\n";
        } else {
            echo "i Post '{$p['slug']}' already exists.\n";
        }
    }

    echo "\n🎉 Setup complete! All connections, tables, and entries are ready.\n";
    echo "Go to /admin/login.php to manage your content.\n";

} catch (PDOException $e) {
    die("\n❌ Error during setup: " . $e->getMessage() . "\n");
}
