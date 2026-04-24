<?php
require 'auth.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// API: Send message from admin
if ($action === 'send') {
    $session_id = (int)($_POST['session_id'] ?? 0);
    $message    = trim($_POST['message'] ?? '');
    
    if ($session_id && $message !== '') {
        // Also check if we should notify Telegram
        $pdo->prepare("UPDATE chat_sessions SET updated_at = NOW() WHERE id = ?")->execute([$session_id]);
        $pdo->prepare("INSERT INTO chat_messages (session_id, sender, message) VALUES (?, 'admin', ?)")->execute([$session_id, $message]);
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false]);
    }
    exit;
}

// API: Load messages for a session
if ($action === 'load_msgs') {
    $session_id = (int)($_GET['session_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT sender, message, sent_at FROM chat_messages WHERE session_id = ? ORDER BY id ASC");
    $stmt->execute([$session_id]);
    echo json_encode(['ok' => true, 'messages' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

// API: Close session
if ($action === 'close') {
    $session_id = (int)($_POST['session_id'] ?? 0);
    if ($session_id) {
        $pdo->prepare("UPDATE chat_sessions SET status = 'closed', updated_at = NOW() WHERE id = ?")->execute([$session_id]);
        echo json_encode(['ok' => true]);
    }
    exit;
}

// Standard page load: show all sessions
$sessions = $pdo->query("SELECT * FROM chat_sessions ORDER BY updated_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat Dashboard | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
.dashboard-layout { display: flex; height: calc(100vh - 60px); }
.sidebar { width: 320px; background: #fff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; }
.sidebar-header { padding: 1rem; border-bottom: 1px solid #e5e7eb; background: #f9fafb; font-weight: bold; }
.session-list { flex: 1; overflow-y: auto; }
.session-item { padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: 0.2s; }
.session-item:hover { background: #f3f4f6; }
.session-item.active { background: #fee2e2; border-left: 4px solid #ef4444; }
.session-name { font-weight: 700; color: #111827; }
.session-meta { font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem; }
.session-status { font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
.status-active { background: #dcfce7; color: #15803d; }
.status-closed { background: #f3f4f6; color: #374151; }

.chat-area { flex: 1; display: flex; flex-direction: column; background: #f9fafb; }
.chat-placeholder { flex: 1; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1.2rem; }

.chat-header { padding: 1.5rem; background: #fff; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
.chat-info h2 { margin: 0 0 0.25rem 0; font-size: 1.2rem; }
.chat-info p { margin: 0; font-size: 0.85rem; color: #6b7280; }

.messages-view { flex: 1; padding: 1.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem; }
.msg { max-width: 70%; padding: 0.8rem 1rem; border-radius: 8px; font-size: 0.95rem; line-height: 1.4; word-wrap: break-word; }
.msg.user { background: #fff; border: 1px solid #e5e7eb; align-self: flex-start; border-bottom-left-radius: 0; }
.msg.admin { background: var(--primary, #1e3a8a); color: #fff; align-self: flex-end; border-bottom-right-radius: 0; }
.msg-time { font-size: 0.7rem; opacity: 0.7; margin-top: 0.2rem; display: block; text-align: right; }

.chat-input { padding: 1.5rem; background: #fff; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; }
.chat-input input { flex: 1; margin: 0; padding: 0.8rem 1rem; border-radius: 6px; border: 1px solid #d1d5db; }
.chat-input button { padding: 0.8rem 1.5rem; background: var(--primary, #1e3a8a); color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
.chat-input button:hover { opacity: 0.9; }
</style>
<?= $custom_code_head ?? '' ?>
</head>
<body>

<header class="admin-header">
  <div class="h-left">
    <div class="logo">HG Admin</div>
    <nav class="h-nav">
      <a href="posts.php">Articles</a>
      <a href="crm.php">CRM</a>
      <a href="chats.php" class="active">Live Chat</a>
      <a href="settings.php">Settings</a>
    </nav>
  </div>
  <div class="h-right">
    <a href="../" target="_blank" class="h-btn">View Site</a>
    <a href="logout.php" class="h-btn text-danger">Logout</a>
  </div>
</header>

<div class="dashboard-layout">
  <div class="sidebar">
    <div class="sidebar-header">Live Sessions</div>
    <div class="session-list">
      <?php if(empty($sessions)): ?>
        <div style="padding: 1rem; color: #6b7280; text-align: center; font-size: 0.9rem;">No chat sessions found.</div>
      <?php endif; ?>
      <?php foreach($sessions as $sess): ?>
        <div class="session-item" data-id="<?= $sess['id'] ?>" onclick="openChat(<?= htmlspecialchars(json_encode($sess)) ?>)">
          <div style="display:flex; justify-content:space-between; align-items:center;">
             <div class="session-name"><?= htmlspecialchars($sess['name']) ?></div>
             <span class="session-status status-<?= $sess['status'] ?>"><?= ucfirst($sess['status']) ?></span>
          </div>
          <div class="session-meta"><?= htmlspecialchars($sess['email']) ?></div>
          <div class="session-meta" style="font-size:0.7rem;"><?= htmlspecialchars(date('M j, g:i a', strtotime($sess['updated_at']))) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="chat-area" id="mainChatArea">
    <div class="chat-placeholder">Select a chat session to view and reply.</div>
  </div>
</div>

<script>
let currentSession = null;
let pollTimer = null;

function renderChatArea() {
    const area = document.getElementById('mainChatArea');
    if (!currentSession) {
        area.innerHTML = `<div class="chat-placeholder">Select a chat session to view and reply.</div>`;
        return;
    }

    const isActive = currentSession.status === 'active';
    area.innerHTML = `
      <div class="chat-header">
        <div class="chat-info">
          <h2>${escapeHtml(currentSession.name)} <span class="session-status status-${currentSession.status}">${currentSession.status.toUpperCase()}</span></h2>
          <p>${escapeHtml(currentSession.email)} &middot; Phone: ${escapeHtml(currentSession.phone || 'N/A')} &middot; Loc: ${escapeHtml(currentSession.location || 'N/A')} &middot; Page: <a href="${escapeHtml(currentSession.page_url||'#')}" target="_blank">Link</a></p>
        </div>
        ${isActive ? `<button class="h-btn text-danger" onclick="closeSession()">Close Chat</button>` : ''}
      </div>
      <div class="messages-view" id="adminMessagesBlock"></div>
      ${isActive ? `
      <form class="chat-input" onsubmit="adminSend(event)">
        <input type="text" id="adminMsgInput" placeholder="Type your reply..." required autocomplete="off" autofocus>
        <button type="submit">Send Reply</button>
      </form>
      ` : `<div style="padding:1.5rem;text-align:center;color:#6b7280;background:#fff;border-top:1px solid #e5e7eb;">This chat session is closed.</div>`}
    `;

    document.querySelectorAll('.session-item').forEach(el => {
        el.classList.toggle('active', parseInt(el.dataset.id) === currentSession.id);
    });

    loadMessages();
    clearTimeout(pollTimer);
    if (isActive) {
        pollTimer = setInterval(loadMessages, 3000);
    }
}

async function loadMessages() {
    if (!currentSession) return;
    try {
        const r = await fetch('chats.php?action=load_msgs&session_id=' + currentSession.id);
        const data = await r.json();
        if (data.ok && data.messages) {
            const block = document.getElementById('adminMessagesBlock');
            if (!block) return;
            const currentScroll = block.scrollHeight - block.scrollTop;
            
            block.innerHTML = data.messages.map(m => `
              <div class="msg ${m.sender}">
                 ${escapeHtml(m.message)}
                 <span class="msg-time">${m.sent_at}</span>
              </div>
            `).join('');

            // Scroll to bottom if we were already relatively near the bottom
            if (currentScroll < block.clientHeight + 100) {
                block.scrollTop = block.scrollHeight;
            }
        }
    } catch(e) {}
}

async function adminSend(e) {
    e.preventDefault();
    const inp = document.getElementById('adminMsgInput');
    const msg = inp.value.trim();
    if (!msg || !currentSession) return;

    inp.value = '';
    const fd = new FormData();
    fd.append('action', 'send');
    fd.append('session_id', currentSession.id);
    fd.append('message', msg);
    
    await fetch('chats.php', { method: 'POST', body: fd });
    loadMessages();
}

async function closeSession() {
    if(!confirm('Close this chat session?')) return;
    const fd = new FormData();
    fd.append('action', 'close');
    fd.append('session_id', currentSession.id);
    await fetch('chats.php', { method: 'POST', body: fd });
    currentSession.status = 'closed';
    renderChatArea();
    location.reload(); // Quick refresh for side list
}

function openChat(sessionObj) {
    currentSession = sessionObj;
    renderChatArea();
}

function escapeHtml(unsafe) {
    return (unsafe || '').toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>

</body>
</html>
