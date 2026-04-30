<?php
require_once 'auth.php';

// ── Auto-migrate leads table ───────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS leads (
    id         INT(11)        AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255)   NOT NULL DEFAULT '',
    email      VARCHAR(255)   NOT NULL DEFAULT '',
    phone      VARCHAR(50)    NOT NULL DEFAULT '',
    source     VARCHAR(20)    NOT NULL DEFAULT 'contact',
    budget     VARCHAR(100)   NOT NULL DEFAULT '',
    goal       TEXT,
    challenge  VARCHAR(255)   NOT NULL DEFAULT '',
    website    VARCHAR(500)   NOT NULL DEFAULT '',
    status     VARCHAR(30)    NOT NULL DEFAULT 'new',
    value      DECIMAL(12,2)  NOT NULL DEFAULT 0,
    comment    TEXT,
    created_at TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// ── Fetch all leads ────────────────────────────────────────────────────────
$all_leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$stages = [
    'new'           => ['label' => 'New',           'color' => '#3b82f6', 'light' => '#eff6ff'],
    'engaged'       => ['label' => 'Engaged',        'color' => '#f59e0b', 'light' => '#fffbeb'],
    'proposal_sent' => ['label' => 'Proposal Sent',  'color' => '#8b5cf6', 'light' => '#f5f3ff'],
    'won'           => ['label' => 'Won',             'color' => '#22c55e', 'light' => '#f0fdf4'],
    'lost'          => ['label' => 'Lost',            'color' => '#ef4444', 'light' => '#fef2f2'],
];

// Group by status + compute totals
$by_stage    = array_fill_keys(array_keys($stages), []);
$stage_value = array_fill_keys(array_keys($stages), 0);
$stage_count = array_fill_keys(array_keys($stages), 0);

foreach ($all_leads as $lead) {
    $s = $lead['status'];
    if (!isset($by_stage[$s])) { $s = 'new'; }
    $by_stage[$s][]    = $lead;
    $stage_value[$s]  += $lead['value'];
    $stage_count[$s]++;
}

$total_leads    = count($all_leads);
$pipeline_value = $stage_value['new'] + $stage_value['engaged'] + $stage_value['proposal_sent'];
$won_value      = $stage_value['won'];
$lost_value     = $stage_value['lost'];
$won_count      = $stage_count['won'];
$conv_rate      = $total_leads > 0 ? round($won_count / $total_leads * 100) : 0;

function fmt_currency(float $v): string {
    if ($v >= 100000) return '₹' . number_format($v / 100000, 1) . 'L';
    if ($v >= 1000)   return '₹' . number_format($v / 1000, 1) . 'K';
    return $v > 0 ? '₹' . number_format($v, 0) : '—';
}

function source_badge(string $s): string {
    return $s === 'audit'
        ? '<span class="lead-source audit">Audit</span>'
        : '<span class="lead-source contact">Contact</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRM | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<style>
/* ── Export button ──────────────────────────────────────────── */
.export-wrap     { position: relative; }
.btn-export      { display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.65rem 1.2rem; background: #fff; border: 1px solid var(--gray-border); border-radius: 6px; font-size: 0.88rem; font-weight: 600; color: var(--text-dark); cursor: pointer; font-family: var(--font-b); transition: all 0.15s; }
.btn-export:hover { border-color: var(--navy); background: var(--navy); color: #fff; }
.export-dropdown { display: none; position: absolute; top: calc(100% + 6px); right: 0; background: #fff; border: 1px solid var(--gray-border); border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); min-width: 160px; z-index: 100; overflow: hidden; }
.export-dropdown.open { display: block; }
.export-option   { display: block; padding: 0.65rem 1.1rem; font-size: 0.88rem; color: var(--text-dark); font-weight: 500; transition: background 0.15s; }
.export-option:hover { background: var(--gray-bg); color: var(--orange); }
.export-option + .export-option { border-top: 1px solid var(--gray-bg); }

/* ── CRM Summary Widgets ────────────────────────────────────── */
.crm-widgets {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}
.crm-widget {
  background: #fff;
  border: 1px solid var(--gray-border);
  border-radius: 10px;
  padding: 1.25rem 1.4rem;
  border-top: 3px solid var(--gray-border);
}
.crm-widget.w-total   { border-top-color: var(--navy); }
.crm-widget.w-pipe    { border-top-color: #8b5cf6; }
.crm-widget.w-won     { border-top-color: #22c55e; }
.crm-widget.w-lost    { border-top-color: #ef4444; }
.crm-widget.w-conv    { border-top-color: var(--orange); }
.widget-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-light); margin-bottom: 0.5rem; }
.widget-value { font-size: 1.9rem; font-weight: 700; font-family: var(--font-h); color: var(--text-dark); line-height: 1; }
.widget-sub   { font-size: 0.8rem; color: var(--text-light); margin-top: 0.35rem; }

/* ── Kanban Board ───────────────────────────────────────────── */
.kanban-board {
  display: grid;
  grid-template-columns: repeat(5, minmax(240px, 1fr));
  gap: 1rem;
  align-items: start;
  overflow-x: auto;
  padding-bottom: 1rem;
}
.kanban-col {
  background: var(--gray-bg);
  border-radius: 10px;
  border: 1px solid var(--gray-border);
  display: flex;
  flex-direction: column;
  min-height: 200px;
}
.kanban-col.drag-over { outline: 2px dashed var(--orange); outline-offset: -2px; background: #fff5f2; }
.col-header {
  padding: 1rem 1.1rem 0.8rem;
  border-bottom: 1px solid var(--gray-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
}
.col-header-left { display: flex; align-items: center; gap: 0.5rem; }
.col-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.col-title { font-weight: 700; font-size: 0.88rem; font-family: var(--font-h); color: var(--text-dark); }
.col-count { font-size: 0.75rem; background: rgba(0,0,0,0.06); border-radius: 20px; padding: 0.1rem 0.5rem; font-weight: 700; color: var(--text-light); }
.col-total { font-size: 0.8rem; font-weight: 700; color: var(--text-dark); }
.col-body  { padding: 0.75rem; flex: 1; display: flex; flex-direction: column; gap: 0.65rem; }

/* ── Lead Card ──────────────────────────────────────────────── */
.lead-card {
  background: #fff;
  border: 1px solid var(--gray-border);
  border-radius: 8px;
  padding: 0.9rem 1rem;
  cursor: grab;
  transition: box-shadow 0.15s, transform 0.15s;
  user-select: none;
}
.lead-card:active { cursor: grabbing; }
.lead-card.dragging { opacity: 0.45; transform: rotate(1deg); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
.lead-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem; }
.lead-name  { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); }
.lead-source { display: inline-block; padding: 0.1rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; flex-shrink: 0; }
.lead-source.audit   { background: #eff6ff; color: #1d4ed8; }
.lead-source.contact { background: #f5f3ff; color: #6d28d9; }
.lead-email { font-size: 0.8rem; color: var(--text-light); margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lead-phone { font-size: 0.8rem; color: var(--text-light); margin-bottom: 0.4rem; }
.lead-goal  { font-size: 0.78rem; color: var(--text-dark); background: var(--gray-bg); border-radius: 5px; padding: 0.35rem 0.5rem; margin-bottom: 0.5rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.lead-date  { font-size: 0.72rem; color: var(--text-light); }
.lead-plan  { font-size: 0.75rem; font-weight: 600; color: #6d28d9; background: #f5f3ff; border-radius: 5px; padding: 0.25rem 0.5rem; margin-bottom: 0.4rem; }
.lead-utm   { font-size: 0.72rem; color: var(--text-light); background: var(--gray-bg); border-radius: 5px; padding: 0.25rem 0.5rem; margin-top: 0.3rem; }
.lead-value-display { font-size: 0.82rem; font-weight: 700; color: var(--text-dark); }
.lead-value-display.has-value { color: #22c55e; }
.card-foot  { display: flex; align-items: center; justify-content: space-between; margin-top: 0.6rem; }

/* ── Card expand / inline form ──────────────────────────────── */
.card-toggle { background: none; border: 1px solid var(--gray-border); border-radius: 5px; cursor: pointer; padding: 0.25rem 0.5rem; color: var(--text-light); display: inline-flex; align-items: center; gap: 0.3rem; transition: all 0.15s; }
.card-toggle:hover { border-color: var(--orange); color: var(--orange); background: #fff5f2; }
.card-toggle.open  { border-color: var(--orange); color: var(--orange); background: #fff5f2; }
.card-toggle svg   { flex-shrink: 0; }
.card-detail {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid var(--gray-bg);
  display: none;
}
.card-detail.open { display: block; }
.card-detail .d-field { margin-bottom: 0.6rem; }
.card-detail label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-light); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.4px; }
.card-detail input[type=number],
.card-detail textarea { width: 100%; padding: 0.45rem 0.6rem; border: 1px solid var(--gray-border); border-radius: 5px; font-family: var(--font-b); font-size: 0.82rem; color: var(--text-dark); background: #fff; }
.card-detail input[type=number]:focus,
.card-detail textarea:focus { outline: none; border-color: var(--orange); }
.card-detail textarea { resize: vertical; rows: 2; }
.detail-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-top: 0.6rem; }
.btn-save-lead { padding: 0.4rem 0.9rem; background: var(--orange); color: #fff; border: none; border-radius: 5px; font-size: 0.8rem; font-weight: 700; cursor: pointer; font-family: var(--font-b); transition: background 0.15s; }
.btn-save-lead:hover { background: #ff8f73; }
.btn-del-lead  { padding: 0.4rem 0.6rem; background: none; border: 1px solid #fca5a5; color: #b91c1c; border-radius: 5px; font-size: 0.78rem; font-weight: 600; cursor: pointer; font-family: var(--font-b); transition: all 0.15s; }
.btn-del-lead:hover { background: #fee2e2; }
.save-feedback { font-size: 0.75rem; color: #22c55e; font-weight: 600; display: none; }

/* ── Empty column state ─────────────────────────────────────── */
.col-empty { text-align: center; padding: 2rem 1rem; color: var(--text-light); font-size: 0.82rem; border: 2px dashed var(--gray-border); border-radius: 7px; }

/* ── Move dropdown ──────────────────────────────────────────── */
.move-wrap { position: relative; }
.btn-move  { background: none; border: 1px solid var(--gray-border); border-radius: 5px; padding: 0.25rem 0.5rem; font-size: 0.72rem; font-weight: 600; color: var(--text-light); cursor: pointer; font-family: var(--font-b); transition: all 0.15s; }
.btn-move:hover { border-color: var(--navy); color: var(--navy); }

@media (max-width: 1100px) {
  .crm-widgets { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 700px) {
  .crm-widgets { grid-template-columns: 1fr 1fr; }
  .kanban-board { grid-template-columns: repeat(5, 260px); }
}
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo"><img src="../logo.png" alt="SumoMedia" style="max-width: 150px; height: auto;"></div>
    <nav class="sidebar-nav">
      <a href="posts.php" class="nav-item">Posts</a>
      <a href="post-form.php" class="nav-item">New Post</a>
      <a href="crm.php" class="nav-item active">CRM</a>
      <a href="subscribers.php" class="nav-item">Subscribers</a>
      <a href="settings.php" class="nav-item">Settings</a>
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
        <h1>CRM &mdash; Lead Tracker</h1>
        <p class="page-sub">Drag cards between columns to update status</p>
      </div>
      <div class="export-wrap">
        <button class="btn-export" onclick="toggleExport(this)">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export CSV
        </button>
        <div class="export-dropdown" id="export-dropdown">
          <a href="crm-export.php?status=all"           class="export-option">All Leads</a>
          <a href="crm-export.php?status=new"           class="export-option">New</a>
          <a href="crm-export.php?status=engaged"       class="export-option">Engaged</a>
          <a href="crm-export.php?status=proposal_sent" class="export-option">Proposal Sent</a>
          <a href="crm-export.php?status=won"           class="export-option">Won</a>
          <a href="crm-export.php?status=lost"          class="export-option">Lost</a>
        </div>
      </div>
    </div>

    <!-- ── Summary Widgets ───────────────────────────────────── -->
    <div class="crm-widgets">
      <div class="crm-widget w-total">
        <div class="widget-label">Total Leads</div>
        <div class="widget-value"><?= $total_leads ?></div>
        <div class="widget-sub"><?= $conv_rate ?>% conversion rate</div>
      </div>
      <div class="crm-widget w-pipe">
        <div class="widget-label">Pipeline Value</div>
        <div class="widget-value"><?= fmt_currency($pipeline_value) ?></div>
        <div class="widget-sub">New + Engaged + Proposal</div>
      </div>
      <div class="crm-widget w-won">
        <div class="widget-label">Won Value</div>
        <div class="widget-value"><?= fmt_currency($won_value) ?></div>
        <div class="widget-sub"><?= $stage_count['won'] ?> deal<?= $stage_count['won'] !== 1 ? 's' : '' ?> closed</div>
      </div>
      <div class="crm-widget w-lost">
        <div class="widget-label">Lost Value</div>
        <div class="widget-value"><?= fmt_currency($lost_value) ?></div>
        <div class="widget-sub"><?= $stage_count['lost'] ?> deal<?= $stage_count['lost'] !== 1 ? 's' : '' ?> lost</div>
      </div>
      <div class="crm-widget w-conv">
        <div class="widget-label">Stage Breakdown</div>
        <div class="widget-value" style="font-size:1rem; line-height:1.6; margin-top:0.2rem;">
          <?php foreach ($stages as $sk => $sv): ?>
            <span style="color:<?= $sv['color'] ?>; font-size:0.82rem; font-weight:700; margin-right:0.6rem;">
              <?= $sv['label'] ?>: <?= $stage_count[$sk] ?>
            </span><br>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ── Kanban Board ──────────────────────────────────────── -->
    <div class="kanban-board" id="kanban">
      <?php foreach ($stages as $stage_key => $stage_info): ?>
      <div class="kanban-col"
           data-stage="<?= $stage_key ?>"
           ondragover="onDragOver(event, this)"
           ondragleave="onDragLeave(this)"
           ondrop="onDrop(event, this)">

        <div class="col-header">
          <div class="col-header-left">
            <span class="col-dot" style="background:<?= $stage_info['color'] ?>;"></span>
            <span class="col-title"><?= $stage_info['label'] ?></span>
            <span class="col-count"><?= $stage_count[$stage_key] ?></span>
          </div>
          <span class="col-total"><?= fmt_currency($stage_value[$stage_key]) ?></span>
        </div>

        <div class="col-body" data-stage="<?= $stage_key ?>">
          <?php if (empty($by_stage[$stage_key])): ?>
            <div class="col-empty">No leads yet</div>
          <?php else: ?>
            <?php foreach ($by_stage[$stage_key] as $lead): ?>
            <?php
              $goal_text = $lead['goal'] ?: $lead['challenge'] ?: $lead['budget'];
              $has_value = $lead['value'] > 0;
            ?>
            <div class="lead-card"
                 draggable="true"
                 data-id="<?= $lead['id'] ?>"
                 ondragstart="onDragStart(event, this)"
                 ondragend="onDragEnd(this)">

              <div class="lead-card-top">
                <div class="lead-name"><?= htmlspecialchars($lead['name']) ?></div>
                <?= source_badge($lead['source']) ?>
              </div>

              <?php if ($lead['email']): ?>
              <div class="lead-email" title="<?= htmlspecialchars($lead['email']) ?>">
                <?= htmlspecialchars($lead['email']) ?>
              </div>
              <?php endif; ?>

              <?php if ($lead['phone']): ?>
              <div class="lead-phone"><?= htmlspecialchars($lead['phone']) ?></div>
              <?php endif; ?>

              <?php if (!empty($lead['pricing_plan'])): ?>
              <div class="lead-plan">📋 <?= htmlspecialchars($lead['pricing_plan']) ?></div>
              <?php endif; ?>

              <?php if ($goal_text): ?>
              <div class="lead-goal"><?= htmlspecialchars($goal_text) ?></div>
              <?php endif; ?>

              <?php if (!empty($lead['utm_source'])): ?>
              <div class="lead-utm">
                🔗 <?= htmlspecialchars($lead['utm_source']) ?>
                <?= !empty($lead['utm_medium'])   ? ' / ' . htmlspecialchars($lead['utm_medium'])   : '' ?>
                <?= !empty($lead['utm_campaign']) ? ' — ' . htmlspecialchars($lead['utm_campaign']) : '' ?>
              </div>
              <?php endif; ?>

              <div class="card-foot">
                <span class="lead-date"><?= date('d M Y', strtotime($lead['created_at'])) ?></span>
                <span class="lead-value-display <?= $has_value ? 'has-value' : '' ?>">
                  <?= $has_value ? fmt_currency((float)$lead['value']) : 'No value' ?>
                </span>
              </div>

              <div class="card-foot" style="margin-top:0.5rem;">
                <button class="card-toggle" onclick="toggleDetail(this)" title="Edit lead">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <select class="btn-move" onchange="moveLead(<?= $lead['id'] ?>, this.value, this)" title="Move to stage">
                  <?php foreach ($stages as $sk => $sv): ?>
                    <option value="<?= $sk ?>" <?= $stage_key === $sk ? 'selected' : '' ?>><?= $sv['label'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Expandable detail form -->
              <div class="card-detail" data-id="<?= $lead['id'] ?>">
                <div class="d-field">
                  <label>Deal Value (₹)</label>
                  <input type="number" class="lead-value-input" value="<?= htmlspecialchars($lead['value']) ?>" min="0" step="1000" placeholder="0">
                </div>
                <div class="d-field">
                  <label>Notes / Comment</label>
                  <textarea class="lead-comment-input" rows="3" placeholder="Add internal notes..."><?= htmlspecialchars($lead['comment'] ?? '') ?></textarea>
                </div>
                <?php if ($lead['budget']): ?>
                <div class="d-field">
                  <label>Stated Budget</label>
                  <div style="font-size:0.82rem; color:var(--text-dark); padding:0.3rem 0;"><?= htmlspecialchars($lead['budget']) ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($lead['pricing_plan'])): ?>
                <div class="d-field">
                  <label>Pricing Plan Selected</label>
                  <div style="font-size:0.82rem; color:#6d28d9; padding:0.3rem 0; font-weight:600;"><?= htmlspecialchars($lead['pricing_plan']) ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($lead['website'])): ?>
                <div class="d-field">
                  <label>Website</label>
                  <div style="font-size:0.82rem; padding:0.3rem 0;">
                    <a href="<?= htmlspecialchars($lead['website']) ?>" target="_blank" rel="noopener" style="color:var(--orange);word-break:break-all;"><?= htmlspecialchars($lead['website']) ?></a>
                  </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($lead['utm_source']) || !empty($lead['utm_url'])): ?>
                <div class="d-field">
                  <label>UTM Tracking</label>
                  <div style="font-size:0.78rem; color:var(--text-light); line-height:1.7; padding:0.3rem 0;">
                    <?php if (!empty($lead['utm_source'])):   ?><b>Source:</b> <?= htmlspecialchars($lead['utm_source'])   ?><br><?php endif; ?>
                    <?php if (!empty($lead['utm_medium'])):   ?><b>Medium:</b> <?= htmlspecialchars($lead['utm_medium'])   ?><br><?php endif; ?>
                    <?php if (!empty($lead['utm_campaign'])): ?><b>Campaign:</b> <?= htmlspecialchars($lead['utm_campaign']) ?><br><?php endif; ?>
                    <?php if (!empty($lead['utm_content'])):  ?><b>Content:</b> <?= htmlspecialchars($lead['utm_content'])  ?><br><?php endif; ?>
                    <?php if (!empty($lead['utm_url'])):      ?><b>URL:</b> <a href="<?= htmlspecialchars($lead['utm_url']) ?>" target="_blank" style="color:var(--orange);word-break:break-all;"><?= htmlspecialchars($lead['utm_url']) ?></a><?php endif; ?>
                  </div>
                </div>
                <?php endif; ?>
                <div class="detail-actions">
                  <div style="display:flex;gap:0.5rem;align-items:center;">
                    <button class="btn-save-lead" onclick="saveLead(<?= $lead['id'] ?>, this)">Save</button>
                    <span class="save-feedback" id="sf-<?= $lead['id'] ?>">Saved!</span>
                  </div>
                  <button class="btn-del-lead" onclick="deleteLead(<?= $lead['id'] ?>, this)">Delete</button>
                </div>
              </div>

            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

  </main>
</div>

<script>
// ── Drag-and-drop ──────────────────────────────────────────────────────────
let dragId   = null;
let dragCard = null;

function onDragStart(e, el) {
  dragId   = el.dataset.id;
  dragCard = el;
  setTimeout(() => el.classList.add('dragging'), 0);
  e.dataTransfer.effectAllowed = 'move';
}

function onDragEnd(el) {
  el.classList.remove('dragging');
  document.querySelectorAll('.kanban-col').forEach(c => c.classList.remove('drag-over'));
}

function onDragOver(e, col) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  col.classList.add('drag-over');
}

function onDragLeave(col) {
  col.classList.remove('drag-over');
}

function onDrop(e, col) {
  e.preventDefault();
  col.classList.remove('drag-over');
  const newStage = col.dataset.stage;
  if (!dragId || !dragCard) return;

  // Move card in DOM
  const body = col.querySelector('.col-body');
  const empty = body.querySelector('.col-empty');
  if (empty) empty.remove();
  body.appendChild(dragCard);

  // Update move select
  dragCard.querySelector('.btn-move').value = newStage;

  ajax({ action: 'move', id: dragId, status: newStage }, () => {
    refreshCounts();
  });

  dragId   = null;
  dragCard = null;
}

// ── Move via dropdown ──────────────────────────────────────────────────────
function moveLead(id, status, selectEl) {
  const card = selectEl.closest('.lead-card');
  const targetColBody = document.querySelector(`.col-body[data-stage="${status}"]`);
  if (!targetColBody) return;

  const empty = targetColBody.querySelector('.col-empty');
  if (empty) empty.remove();
  targetColBody.appendChild(card);

  // Check if source column is now empty
  const srcBody = card.closest('.col-body') || targetColBody;

  ajax({ action: 'move', id, status }, () => { refreshCounts(); });
}

// ── Toggle detail panel ────────────────────────────────────────────────────
function toggleDetail(btn) {
  const detail = btn.closest('.lead-card').querySelector('.card-detail');
  detail.classList.toggle('open');
  btn.classList.toggle('open', detail.classList.contains('open'));
}

// ── Save comment + value ───────────────────────────────────────────────────
function saveLead(id, btn) {
  const card    = btn.closest('.lead-card');
  const detail  = card.querySelector('.card-detail');
  const value   = detail.querySelector('.lead-value-input').value;
  const comment = detail.querySelector('.lead-comment-input').value;

  btn.disabled = true;
  ajax({ action: 'save', id, value, comment }, () => {
    btn.disabled = false;
    const fb = document.getElementById('sf-' + id);
    fb.style.display = 'inline';
    setTimeout(() => { fb.style.display = 'none'; }, 2000);

    // Update value display on card
    const disp = card.querySelector('.lead-value-display');
    const numVal = parseFloat(value) || 0;
    disp.textContent = numVal > 0 ? fmtCurrency(numVal) : 'No value';
    disp.className = 'lead-value-display' + (numVal > 0 ? ' has-value' : '');

    refreshCounts();
  });
}

// ── Delete lead ────────────────────────────────────────────────────────────
function deleteLead(id, btn) {
  if (!confirm('Delete this lead? This cannot be undone.')) return;
  const card = btn.closest('.lead-card');
  ajax({ action: 'delete', id }, () => {
    card.remove();
    refreshCounts();
  });
}

// ── Refresh column counts + totals (client-side after mutations) ───────────
function refreshCounts() {
  document.querySelectorAll('.kanban-col').forEach(col => {
    const stage  = col.dataset.stage;
    const body   = col.querySelector('.col-body');
    const cards  = body.querySelectorAll('.lead-card');

    // Count
    col.querySelector('.col-count').textContent = cards.length;

    // Add empty placeholder if no cards
    if (cards.length === 0 && !body.querySelector('.col-empty')) {
      const em = document.createElement('div');
      em.className = 'col-empty';
      em.textContent = 'No leads yet';
      body.appendChild(em);
    }

    // Sum values
    let total = 0;
    cards.forEach(c => {
      const v = parseFloat(c.querySelector('.lead-value-input')?.value || 0);
      total += v;
    });
    col.querySelector('.col-total').textContent = total > 0 ? fmtCurrency(total) : '—';
  });
}

// ── Currency formatter (JS) ────────────────────────────────────────────────
function fmtCurrency(v) {
  if (v >= 100000) return '₹' + (v / 100000).toFixed(1) + 'L';
  if (v >= 1000)   return '₹' + (v / 1000).toFixed(1) + 'K';
  return '₹' + v.toLocaleString('en-IN');
}

// ── Export dropdown toggle ─────────────────────────────────────────────────
function toggleExport(btn) {
  const dd = document.getElementById('export-dropdown');
  dd.classList.toggle('open');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.export-wrap')) {
    document.getElementById('export-dropdown').classList.remove('open');
  }
});

// ── AJAX helper ───────────────────────────────────────────────────────────
function ajax(data, cb) {
  const fd = new FormData();
  Object.entries(data).forEach(([k, v]) => fd.append(k, v));
  fetch('crm-action.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(j => { if (j.ok && cb) cb(); })
    .catch(() => {});
}
</script>
</body>
</html>
