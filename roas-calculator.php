<?php require_once 'db.php'; require_once 'includes/tracking.php'; $nav_prefix = 'index.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Free ROAS & MER Calculator | SumoMedia.in</title>
<meta name="description" content="Free ROAS and MER calculator. Track channel-level ROAS, overall Marketing Efficiency Ratio, and break-even ROAS across Google, Meta, Pinterest and more.">
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style-2040.css?v=4">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<style>
/* ── Tools Page ─────────────────────────────────────────────────────────── */
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
.tools-hero-back {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.82rem;
  font-weight: 600;
  color: rgba(255,255,255,0.45);
  text-decoration: none;
  margin-bottom: 1.5rem;
  transition: color 0.2s;
}
.tools-hero-back:hover { color: rgba(255,255,255,0.75); }
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
  max-width: 560px;
}

/* ── Calculator Card ─────────────────────────────────────────────────────── */
.calc-section { padding: 4rem 0 6rem; background: var(--gray-bg); }
.calc-wrap { max-width: 960px; margin: 0 auto; }

.calc-summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}
.summary-card {
  background: var(--card);
  border: 1px solid var(--gray-border);
  border-radius: 12px;
  padding: 1.4rem 1.6rem;
  border-top: 3px solid var(--gray-border);
  transition: border-top-color 0.3s;
}
.summary-card.sc-mer  { border-top-color: var(--orange); }
.summary-card.sc-be   { border-top-color: #8b5cf6; }
.summary-card.sc-tot  { border-top-color: var(--navy); }
.summary-label {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--text-light);
  margin-bottom: 0.4rem;
}
.summary-value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-dark);
  line-height: 1;
  font-variant-numeric: tabular-nums;
}
.summary-value.good { color: #16a34a; }
.summary-value.warn { color: #d97706; }
.summary-value.bad  { color: #dc2626; }
.summary-hint {
  font-size: 0.78rem;
  color: var(--text-light);
  margin-top: 0.35rem;
}

/* ── Inputs Panel ────────────────────────────────────────────────────────── */
.calc-panel {
  background: var(--card);
  border: 1px solid var(--gray-border);
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 1.5rem;
}
.calc-panel-header {
  padding: 1.2rem 1.8rem;
  border-bottom: 1px solid var(--gray-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--gray-bg);
}
.calc-panel-header h3 {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 0;
}
.calc-panel-body { padding: 1.8rem; }

/* ── Global inputs ───────────────────────────────────────────────────────── */
.global-inputs {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 0;
}
.field-group { display: flex; flex-direction: column; gap: 0.35rem; }
.field-group label {
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.field-group .hint {
  font-size: 0.72rem;
  color: var(--text-light);
  margin-top: -0.2rem;
}
.calc-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1.5px solid var(--gray-border);
  border-radius: 8px;
  font-family: var(--font-body);
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-dark);
  background: #fff;
  margin: 0;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.calc-input:focus {
  outline: none;
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(255,122,89,0.12);
}

/* ── Channel Table ───────────────────────────────────────────────────────── */
.channel-table { width: 100%; border-collapse: collapse; }
.channel-table thead th {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--text-light);
  padding: 0 0.75rem 0.75rem;
  text-align: left;
  border-bottom: 1px solid var(--gray-border);
}
.channel-table thead th:first-child { padding-left: 0; }
.channel-table thead th.th-result { text-align: right; color: var(--orange); }
.channel-table tbody tr { transition: background 0.15s; }
.channel-table tbody tr:hover { background: var(--gray-bg); }
.channel-table tbody td {
  padding: 0.65rem 0.75rem;
  vertical-align: middle;
  border-bottom: 1px solid var(--gray-border);
}
.channel-table tbody td:first-child { padding-left: 0; }
.channel-table tbody tr:last-child td { border-bottom: none; }

.channel-name-input {
  width: 100%;
  border: none;
  background: transparent;
  font-family: var(--font-body);
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-dark);
  padding: 0.35rem 0;
  outline: none;
  border-bottom: 1.5px dashed var(--gray-border);
  transition: border-color 0.2s;
}
.channel-name-input:focus { border-color: var(--orange); }

.channel-num-input {
  width: 100%;
  border: 1.5px solid var(--gray-border);
  border-radius: 7px;
  background: #fff;
  font-family: var(--font-body);
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--text-dark);
  padding: 0.5rem 0.8rem;
  margin: 0;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.channel-num-input:focus {
  outline: none;
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(255,122,89,0.1);
}

.roas-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 1rem;
  font-weight: 800;
  padding: 0.4rem 0.9rem;
  border-radius: 20px;
  float: right;
  font-variant-numeric: tabular-nums;
  min-width: 80px;
  justify-content: center;
}
.roas-badge.good { background: #dcfce7; color: #15803d; }
.roas-badge.warn { background: #fef9c3; color: #a16207; }
.roas-badge.bad  { background: #fee2e2; color: #b91c1c; }
.roas-badge.neutral { background: var(--gray-bg); color: var(--text-light); }

.btn-del-row {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  font-size: 1rem;
  padding: 0.25rem;
  border-radius: 4px;
  transition: color 0.15s, background 0.15s;
  line-height: 1;
}
.btn-del-row:hover { color: #dc2626; background: #fee2e2; }

.btn-add-channel {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: 1.5px dashed var(--gray-border);
  border-radius: 8px;
  padding: 0.6rem 1.2rem;
  font-family: var(--font-body);
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-light);
  cursor: pointer;
  transition: all 0.2s;
  margin-top: 1rem;
}
.btn-add-channel:hover { border-color: var(--orange); color: var(--orange); background: rgba(255,122,89,0.04); }

/* ── Explainer ───────────────────────────────────────────────────────────── */
.explainer-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-top: 2rem;
}
.explainer-card {
  background: var(--card);
  border: 1px solid var(--gray-border);
  border-radius: 12px;
  padding: 1.5rem;
}
.explainer-card h4 {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.explainer-card h4 .e-badge {
  font-size: 0.65rem;
  background: rgba(255,122,89,0.12);
  color: var(--orange);
  border-radius: 4px;
  padding: 2px 7px;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.explainer-card p {
  font-size: 0.83rem;
  color: var(--text-light);
  line-height: 1.6;
  margin: 0;
}

.formula-chip {
  display: inline-block;
  background: var(--gray-bg);
  border: 1px solid var(--gray-border);
  border-radius: 6px;
  padding: 0.3rem 0.7rem;
  font-size: 0.78rem;
  font-family: 'Courier New', monospace;
  color: var(--text-dark);
  margin: 0.5rem 0 0;
}

@media(max-width: 700px) {
  .calc-summary { grid-template-columns: 1fr; }
  .global-inputs { grid-template-columns: 1fr; }
  .explainer-grid { grid-template-columns: 1fr; }
  .channel-table thead th:nth-child(2),
  .channel-table tbody td:nth-child(2) { display: none; }
}
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<?php require 'includes/nav.php'; ?>

<!-- HERO -->
<section class="tools-hero">
  <div class="container">
    <a href="tools.php" class="tools-hero-back">
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      All Tools
    </a>
    <div class="tools-hero-tag">Free Tool</div>
    <h1>ROAS &amp; MER Calculator</h1>
    <p>Track channel-level ROAS, overall Marketing Efficiency Ratio, and break-even ROAS across every ad channel, in real time.</p>
  </div>
</section>

<!-- CALCULATOR -->
<section class="calc-section">
  <div class="container calc-wrap">

    <!-- Summary Cards -->
    <div class="calc-summary">
      <div class="summary-card sc-mer">
        <div class="summary-label">MER (Overall)</div>
        <div class="summary-value" id="out-mer">—</div>
        <div class="summary-hint">Total Revenue ÷ Total Ad Spend</div>
      </div>
      <div class="summary-card sc-be">
        <div class="summary-label">Break-even ROAS</div>
        <div class="summary-value" id="out-be">—</div>
        <div class="summary-hint">Based on your gross margin</div>
      </div>
      <div class="summary-card sc-tot">
        <div class="summary-label">Total Ad Spend</div>
        <div class="summary-value" id="out-spend">—</div>
        <div class="summary-hint">Across all channels</div>
      </div>
    </div>

    <!-- Global Inputs -->
    <div class="calc-panel">
      <div class="calc-panel-header">
        <h3>Business Inputs</h3>
      </div>
      <div class="calc-panel-body">
        <div class="global-inputs">
          <div class="field-group">
            <label for="currency-select">Currency</label>
            <span class="hint">Select target currency</span>
            <select id="currency-select" class="calc-input" style="padding-top: 0.70rem; padding-bottom: 0.70rem;">
              <option value="$">USD ($)</option>
              <option value="€">EUR (€)</option>
              <option value="£">GBP (£)</option>
              <option value="₹">INR (₹)</option>
              <option value="A$">AUD (A$)</option>
              <option value="C$">CAD (C$)</option>
            </select>
          </div>
          <div class="field-group">
            <label for="total-revenue">Total Revenue (<span class="currency-symbol">$</span>)</label>
            <span class="hint">All revenue, not just from ads</span>
            <input type="number" id="total-revenue" class="calc-input" placeholder="e.g. 150000" min="0" step="1">
          </div>
          <div class="field-group">
            <label for="gross-margin">Gross Margin (%)</label>
            <span class="hint">Used to calculate break-even ROAS</span>
            <input type="number" id="gross-margin" class="calc-input" placeholder="e.g. 40" min="1" max="100" step="0.1">
          </div>
        </div>
      </div>
    </div>

    <!-- Channel Table -->
    <div class="calc-panel">
      <div class="calc-panel-header">
        <h3>Ad Channels</h3>
        <span style="font-size:0.78rem; color:var(--text-light);">Edit channel names inline</span>
      </div>
      <div class="calc-panel-body" style="padding-bottom:1.2rem;">
        <table class="channel-table">
          <thead>
            <tr>
              <th style="width:28%">Channel</th>
              <th style="width:28%">Ad Spend (<span class="currency-symbol">$</span>)</th>
              <th style="width:28%">Channel Revenue (<span class="currency-symbol">$</span>)</th>
              <th class="th-result" style="width:12%">ROAS</th>
              <th style="width:4%"></th>
            </tr>
          </thead>
          <tbody id="channel-tbody">
            <!-- rows injected by JS -->
          </tbody>
        </table>
        <button class="btn-add-channel" id="btn-add">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Add Channel
        </button>
      </div>
    </div>

    <!-- Explainer Cards -->
    <div class="explainer-grid">
      <div class="explainer-card">
        <h4><span class="e-badge">ROAS</span> Return on Ad Spend</h4>
        <p>Measures revenue generated per dollar spent on a specific channel. High ROAS looks good, but doesn't tell you if the business is profitable.</p>
        <span class="formula-chip">Revenue ÷ Ad Spend</span>
      </div>
      <div class="explainer-card">
        <h4><span class="e-badge">MER</span> Marketing Efficiency Ratio</h4>
        <p>The true north-star metric. Divides <em>all</em> revenue by <em>all</em> ad spend, exposing cross-channel halo effects ROAS misses.</p>
        <span class="formula-chip">Total Revenue ÷ Total Ad Spend</span>
      </div>
      <div class="explainer-card">
        <h4><span class="e-badge">BE</span> Break-even ROAS</h4>
        <p>The minimum ROAS you need to cover your cost of goods. Any channel below this number is destroying margin.</p>
        <span class="formula-chip">1 ÷ Gross Margin %</span>
      </div>
    </div>

  </div>
</section>

<?php require 'includes/footer.php'; ?>

<script>
const DEFAULT_CHANNELS = [
  { name: 'Google Ads',  spend: '', revenue: '' },
  { name: 'Meta Ads',    spend: '', revenue: '' },
  { name: 'Pinterest',   spend: '', revenue: '' },
];

let channels = DEFAULT_CHANNELS.map(c => ({ ...c }));

function fmt(n) {
  if (!isFinite(n) || n <= 0) return '—';
  return n.toFixed(2) + 'x';
}
function fmtMoney(n) {
  const sym = document.getElementById('currency-select').value;
  if (!isFinite(n) || n <= 0) return '—';
  if (n >= 1e6) return sym + (n / 1e6).toFixed(2) + 'M';
  if (n >= 1e3) return sym + (n / 1e3).toFixed(1) + 'K';
  return sym + n.toFixed(0);
}
function roasClass(roas, be) {
  if (!isFinite(roas) || roas <= 0) return 'neutral';
  if (be > 0 && roas >= be * 1.1) return 'good';
  if (be > 0 && roas < be) return 'bad';
  return 'warn';
}

function renderRows() {
  const tbody = document.getElementById('channel-tbody');
  tbody.innerHTML = '';
  channels.forEach((ch, i) => {
    const spend   = parseFloat(ch.spend)   || 0;
    const rev     = parseFloat(ch.revenue) || 0;
    const roas    = spend > 0 ? rev / spend : 0;
    const margin  = parseFloat(document.getElementById('gross-margin').value) || 0;
    const be      = margin > 0 ? 100 / margin : 0;
    const cls     = roas > 0 ? roasClass(roas, be) : 'neutral';
    const roasTxt = roas > 0 ? roas.toFixed(2) + 'x' : '—';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input class="channel-name-input" data-i="${i}" data-field="name" value="${ch.name}" placeholder="Channel name"></td>
      <td><input type="number" class="channel-num-input" data-i="${i}" data-field="spend" value="${ch.spend}" placeholder="0" min="0"></td>
      <td><input type="number" class="channel-num-input" data-i="${i}" data-field="revenue" value="${ch.revenue}" placeholder="0" min="0"></td>
      <td><span class="roas-badge ${cls}">${roasTxt}</span></td>
      <td><button class="btn-del-row" data-i="${i}" title="Remove">✕</button></td>
    `;
    tbody.appendChild(tr);
  });

  tbody.querySelectorAll('[data-i]').forEach(el => {
    el.addEventListener('input', e => {
      const i = +e.target.dataset.i;
      const f = e.target.dataset.field;
      channels[i][f] = e.target.value;
      recalc();
    });
  });
  tbody.querySelectorAll('.btn-del-row').forEach(btn => {
    btn.addEventListener('click', e => {
      const i = +e.target.dataset.i;
      if (channels.length > 1) { channels.splice(i, 1); renderRows(); recalc(); }
    });
  });
}

function recalc() {
  const totalRev  = parseFloat(document.getElementById('total-revenue').value) || 0;
  const margin    = parseFloat(document.getElementById('gross-margin').value)  || 0;
  const totalSpend = channels.reduce((s, ch) => s + (parseFloat(ch.spend) || 0), 0);

  const mer = totalSpend > 0 && totalRev > 0 ? totalRev / totalSpend : 0;
  const be  = margin > 0 ? 100 / margin : 0;

  const merEl = document.getElementById('out-mer');
  merEl.textContent = mer > 0 ? mer.toFixed(2) + 'x' : '—';
  merEl.className = 'summary-value' + (mer > 0 ? (be > 0 && mer >= be ? ' good' : mer > 0 && be > 0 && mer < be ? ' bad' : ' warn') : '');

  document.getElementById('out-be').textContent = be > 0 ? be.toFixed(2) + 'x' : '—';
  document.getElementById('out-spend').textContent = totalSpend > 0 ? fmtMoney(totalSpend) : '—';

  const tbody = document.getElementById('channel-tbody');
  tbody.querySelectorAll('.roas-badge').forEach((badge, i) => {
    const ch   = channels[i];
    const spend = parseFloat(ch?.spend) || 0;
    const rev   = parseFloat(ch?.revenue) || 0;
    const roas  = spend > 0 ? rev / spend : 0;
    const cls   = roas > 0 ? roasClass(roas, be) : 'neutral';
    badge.className = `roas-badge ${cls}`;
    badge.textContent = roas > 0 ? roas.toFixed(2) + 'x' : '—';
  });
}

document.getElementById('btn-add').addEventListener('click', () => {
  channels.push({ name: 'New Channel', spend: '', revenue: '' });
  renderRows();
});

document.getElementById('total-revenue').addEventListener('input', recalc);
document.getElementById('gross-margin').addEventListener('input', () => { renderRows(); recalc(); });
document.getElementById('currency-select').addEventListener('change', (e) => {
  const sym = e.target.value;
  document.querySelectorAll('.currency-symbol').forEach(el => el.textContent = sym);
  recalc();
});

renderRows();
</script>
</body>
</html>
