<?php
// Only allow POST requests — reject direct URL access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$form_type = $_POST['form_type'] ?? 'contact';
$name      = htmlspecialchars(trim($_POST['name']  ?? 'there'));

// ── Save lead to CRM ───────────────────────────────────────────────────────
try {
    require_once __DIR__ . '/db.php';

    // Auto-create leads table if missing
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
        utm_url      VARCHAR(1000)  NOT NULL DEFAULT '',
        utm_source   VARCHAR(255)   NOT NULL DEFAULT '',
        utm_medium   VARCHAR(255)   NOT NULL DEFAULT '',
        utm_campaign VARCHAR(255)   NOT NULL DEFAULT '',
        utm_content  VARCHAR(255)   NOT NULL DEFAULT '',
        pricing_plan VARCHAR(255)   NOT NULL DEFAULT '',
        created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        updated_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Auto-migrate UTM + pricing columns on existing tables
    $existing_cols = $pdo->query("DESCRIBE leads")->fetchAll(PDO::FETCH_COLUMN);
    $migrations = [
        'utm_url'      => "ALTER TABLE leads ADD COLUMN utm_url      VARCHAR(1000) NOT NULL DEFAULT ''",
        'utm_source'   => "ALTER TABLE leads ADD COLUMN utm_source   VARCHAR(255)  NOT NULL DEFAULT ''",
        'utm_medium'   => "ALTER TABLE leads ADD COLUMN utm_medium   VARCHAR(255)  NOT NULL DEFAULT ''",
        'utm_campaign' => "ALTER TABLE leads ADD COLUMN utm_campaign VARCHAR(255)  NOT NULL DEFAULT ''",
        'utm_content'  => "ALTER TABLE leads ADD COLUMN utm_content  VARCHAR(255)  NOT NULL DEFAULT ''",
        'pricing_plan' => "ALTER TABLE leads ADD COLUMN pricing_plan VARCHAR(255)  NOT NULL DEFAULT ''",
    ];
    foreach ($migrations as $col => $sql) {
        if (!in_array($col, $existing_cols)) $pdo->exec($sql);
    }

    // Derive initial value from pricing plan
    $pricing_plan = trim($_POST['pricing_plan'] ?? '');
    $plan_value   = 0;
    if (str_contains($pricing_plan, '150K'))     $plan_value = 150000;
    elseif (str_contains($pricing_plan, '95K'))  $plan_value = 95000;
    elseif (str_contains($pricing_plan, '85K'))  $plan_value = 85000;
    elseif (str_contains($pricing_plan, '75K'))  $plan_value = 75000;

    $lead_data = [
        'name'         => trim($_POST['name']         ?? ''),
        'email'        => trim($_POST['email']        ?? ''),
        'phone'        => trim($_POST['phone']        ?? ''),
        'source'       => $form_type,
        'budget'       => trim($_POST['budget']       ?? ''),
        'goal'         => trim($_POST['goal']         ?? ''),
        'challenge'    => trim($_POST['challenge']    ?? ''),
        'utm_url'      => trim($_POST['utm_url']      ?? ''),
        'utm_source'   => trim($_POST['utm_source']   ?? ''),
        'utm_medium'   => trim($_POST['utm_medium']   ?? ''),
        'utm_campaign' => trim($_POST['utm_campaign'] ?? ''),
        'utm_content'  => trim($_POST['utm_content']  ?? ''),
        'pricing_plan' => $pricing_plan,
        'plan_value'   => $plan_value,
    ];

    $stmt = $pdo->prepare("INSERT INTO leads
        (name, email, phone, source, budget, goal, challenge,
         utm_url, utm_source, utm_medium, utm_campaign, utm_content,
         pricing_plan, value, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')");
    $stmt->execute([
        $lead_data['name'], $lead_data['email'], $lead_data['phone'],
        $lead_data['source'], $lead_data['budget'], $lead_data['goal'],
        $lead_data['challenge'], $lead_data['utm_url'], $lead_data['utm_source'],
        $lead_data['utm_medium'], $lead_data['utm_campaign'], $lead_data['utm_content'],
        $lead_data['pricing_plan'], $lead_data['plan_value'],
    ]);

    // Send email notification via Mailgun
    require_once __DIR__ . '/mailer.php';
    send_lead_notification($lead_data);

} catch (Exception $e) {
    // Silently fail — don't break the success page for the user
    error_log('Lead save error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Message Received | HariGopinath.com</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css">
<style>
  .success-section {
    min-height: calc(100vh - 73px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-bg);
    padding: 4rem 0;
  }
  .success-card {
    background: #fff;
    border-radius: 16px;
    padding: 4rem 3.5rem;
    max-width: 560px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 50px rgba(45,62,80,0.08);
    border: 1px solid var(--gray-border);
  }
  .success-icon {
    width: 72px;
    height: 72px;
    background: rgba(255,122,89,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
  }
  .success-card h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-dark);
  }
  .success-card p {
    font-size: 1.05rem;
    color: var(--text-light);
    line-height: 1.7;
    margin-bottom: 2rem;
  }
  .success-card .next-steps {
    background: var(--gray-bg);
    border-radius: 10px;
    padding: 1.5rem;
    text-align: left;
    margin-bottom: 2rem;
  }
  .success-card .next-steps h4 {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--orange);
    font-weight: 700;
    margin-bottom: 1rem;
  }
  .success-card .next-steps ul {
    list-style: none;
    padding: 0;
  }
  .success-card .next-steps li {
    font-size: 0.95rem;
    color: var(--text-dark);
    font-weight: 500;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  .success-card .next-steps li:last-child { border-bottom: none; }
  .success-card .next-steps li::before {
    content: '';
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    background: rgba(255,122,89,0.1);
    border-radius: 50%;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2 6l3 3 5-5' stroke='%23FF7A59' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
  }
  .success-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
</style>
</head>
<body>

<?php require 'includes/nav.php'; ?>

<section class="success-section">
  <div class="success-card">

    <!-- Icon -->
    <div class="success-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 6L9 17L4 12" stroke="#FF7A59" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <h1>
      <?php if ($form_type === 'audit'): ?>
        Audit Request Received!
      <?php else: ?>
        Message Received!
      <?php endif; ?>
    </h1>

    <p>
      Thanks, <strong><?= $name ?></strong>. I've got your details and will personally review your request. Expect to hear from me within <strong>24 hours</strong>.
    </p>

    <div class="next-steps">
      <h4>What happens next</h4>
      <ul>
        <?php if ($form_type === 'audit'): ?>
          <li>I'll audit your website &amp; current SEO/ad performance</li>
          <li>You'll receive a personalised growth roadmap</li>
          <li>We'll schedule a 30-minute strategy call</li>
        <?php else: ?>
          <li>I'll review your challenge and goals</li>
          <li>We'll schedule a free 30-minute strategy call</li>
          <li>You'll walk away with a clear action plan</li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="success-actions">
      <a href="index.php" class="btn btn-primary">Back to Homepage</a>
      <a href="blog.php" class="btn btn-outline">Read the Blog</a>
    </div>

  </div>
</section>

<?php require 'includes/footer.php'; ?>

</body>
</html>
