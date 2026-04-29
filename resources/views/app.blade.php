<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>(function(){ document.getElementById('htmlRoot').setAttribute('data-theme', localStorage.getItem('theme') || 'dark'); })();</script>
    @vite(['resources/js/app.js'])
    <style>
        /* ══════════════════════════════════════
           CSS VARIABLES — DARK THEME (default)
        ══════════════════════════════════════ */
        :root {
            --bg-body:        #111b21;
            --bg-sidebar:     #111b21;
            --bg-header:      #202c33;
            --bg-chat:        #0b141a;
            --bg-input:       #2a3942;
            --bg-item-hover:  #202c33;
            --bg-item-active: #2a3942;
            --bg-bubble-in:   #202c33;
            --bg-bubble-out:  #005c4b;
            --bg-search:      #202c33;
            --bg-settings:    #111b21;
            --bg-settings-hd: #202c33;
            --bg-settings-row:#1a2530;
            --border:         #2a3942;
            --border-item:    #1f2c34;
            --text-primary:   #e9edef;
            --text-secondary: #667781;
            --text-meta:      #8696a0;
            --accent:         #00a884;
            --accent-hover:   #06cf9c;
            --tick-read:      #53bdeb;
            --online-border:  #111b21;
            --avatar-grad:    linear-gradient(135deg,#667781,#374248);
            --scrollbar:      #374248;
            --action-bg:      #2a3942;
            --action-hover:   #374248;
            --action-color:   #aebac1;
        }

        /* ══════════════════════════════════════
           LIGHT THEME — FB Messenger style
        ══════════════════════════════════════ */
        [data-theme="light"] {
            --bg-body:        #f0f2f5;
            --bg-sidebar:     #ffffff;
            --bg-header:      #0084ff;
            --bg-chat:        #f0f2f5;
            --bg-input:       #f0f2f5;
            --bg-item-hover:  #f5f5f5;
            --bg-item-active: #e4e6eb;
            --bg-bubble-in:   #e4e6eb;
            --bg-bubble-out:  #0084ff;
            --bg-search:      #f0f2f5;
            --bg-settings:    #ffffff;
            --bg-settings-hd: #0084ff;
            --bg-settings-row:#f7f8fa;
            --border:         #e4e6eb;
            --border-item:    #f0f2f5;
            --text-primary:   #050505;
            --text-secondary: #65676b;
            --text-meta:      #65676b;
            --accent:         #0084ff;
            --accent-hover:   #0073e6;
            --tick-read:      #0084ff;
            --online-border:  #ffffff;
            --avatar-grad:    linear-gradient(135deg,#0084ff,#0073e6);
            --scrollbar:      #ccc;
            --action-bg:      rgba(255,255,255,0.2);
            --action-hover:   rgba(255,255,255,0.35);
            --action-color:   #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: var(--bg-body); height: 100vh; overflow: hidden; }
        .container { display: flex; height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: 380px; flex-shrink: 0; background: var(--bg-sidebar); display: flex; flex-direction: column; border-right: 1px solid var(--border); position: relative; }
        .sidebar-header { background: var(--bg-header); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
        .sidebar-header h2 { color: var(--action-color); font-size: 18px; font-weight: 500; }
        .header-actions { display: flex; gap: 7px; }
        .header-actions a, .header-actions button { color: var(--action-color); text-decoration: none; font-size: 12px; padding: 5px 10px; border-radius: 20px; background: var(--action-bg); border: none; cursor: pointer; }
        .header-actions a:hover, .header-actions button:hover { background: var(--action-hover); }
        .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; position: absolute; top: 0; left: 0; }
        .search-box { padding: 8px 10px; background: var(--bg-sidebar); }
        .search-box input { width: 100%; padding: 9px 14px; background: var(--bg-search); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 14px; }
        .search-box input:focus { outline: none; }
        .search-box input::placeholder { color: var(--text-secondary); }
        .chat-list { flex: 1; overflow-y: auto; background: var(--bg-sidebar); }
        .chat-item { padding: 11px 16px; display: flex; align-items: center; gap: 12px; cursor: pointer; border-bottom: 1px solid var(--border-item); transition: background .15s; background: var(--bg-sidebar); }
        .chat-item:hover { background: var(--bg-item-hover); }
        .chat-item.active { background: var(--bg-item-active); }
        .avatar { width: 48px; height: 48px; border-radius: 50%; background: var(--avatar-grad); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 17px; flex-shrink: 0; position: relative; overflow: hidden; }
        .online-dot { position: absolute; bottom: 1px; right: 1px; width: 12px; height: 12px; background: var(--accent); border-radius: 50%; border: 2px solid var(--online-border); display: none; }
        .online-dot.show { display: block; }
        .chat-info { flex: 1; min-width: 0; }
        .chat-info-top { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .chat-name { color: var(--text-primary); font-size: 15px; }
        .chat-time { color: var(--text-secondary); font-size: 11px; }
        .chat-bottom { display: flex; justify-content: space-between; align-items: center; }
        .chat-preview { color: var(--text-secondary); font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
        .badge { background: var(--accent); color: #fff; font-size: 11px; font-weight: 600; min-width: 20px; height: 20px; border-radius: 10px; display: none; align-items: center; justify-content: center; padding: 0 5px; margin-left: 6px; flex-shrink: 0; }
        .badge.show { display: flex; }

        /* CHAT AREA */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: var(--bg-chat); }
        .chat-header { background: var(--bg-header); padding: 11px 18px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border); }
        .chat-header .avatar { width: 40px; height: 40px; font-size: 15px; }
        .chat-header .online-dot { border-color: var(--bg-header); }
        .chat-header-info h3 { color: var(--action-color); font-size: 16px; font-weight: 400; }
        .chat-header-info .status { color: rgba(255,255,255,0.75); font-size: 12px; }
        .chat-header-info .status.online { color: #fff; }
        .messages-box { flex: 1; overflow-y: auto; padding: 16px 20px; }
        .msg { display: flex; margin-bottom: 4px; }
        .msg.sent { justify-content: flex-end; }
        .bubble { max-width: 65%; padding: 7px 12px 5px; border-radius: 18px; word-wrap: break-word; }
        .msg.received .bubble { background: var(--bg-bubble-in); color: var(--text-primary); border-bottom-left-radius: 4px; }
        .msg.sent .bubble { background: var(--bg-bubble-out); color: #fff; border-bottom-right-radius: 4px; }
        .msg-text { font-size: 14px; line-height: 20px; }
        .msg-meta { display: flex; align-items: center; justify-content: flex-end; gap: 3px; margin-top: 2px; }
        .msg-time { font-size: 11px; color: var(--text-meta); }
        .msg.sent .msg-time { color: rgba(255,255,255,0.7); }
        .tick { font-size: 13px; }
        .tick.sent-tick { color: rgba(255,255,255,0.6); }
        .tick.delivered-tick { color: rgba(255,255,255,0.6); }
        .tick.read-tick { color: var(--tick-read); }
        .input-area { background: var(--bg-header); padding: 9px 18px; display: flex; gap: 10px; align-items: center; border-top: 1px solid var(--border); }
        .input-area input { flex: 1; padding: 11px 15px; background: var(--bg-input); border: 1px solid var(--border); border-radius: 20px; color: var(--text-primary); font-size: 15px; }
        .input-area input:focus { outline: none; }
        .input-area input::placeholder { color: var(--text-secondary); }
        .send-btn { background: var(--accent); color: #fff; border: none; padding: 11px 16px; border-radius: 50%; cursor: pointer; font-size: 16px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; }
        .send-btn:hover { background: var(--accent-hover); }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--text-secondary); text-align: center; gap: 10px; }
        .empty-state span { font-size: 48px; }
        .empty-state h3 { color: var(--text-primary); font-size: 20px; font-weight: 400; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }

        /* typing indicator */
        .typing-indicator { display: none; align-items: center; gap: 4px; padding: 6px 12px; background: var(--bg-bubble-in); border-radius: 18px; width: fit-content; margin-bottom: 8px; }
        .typing-indicator.show { display: flex; }
        .typing-indicator span { width: 7px; height: 7px; background: var(--text-meta); border-radius: 50%; animation: bounce 1.2s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay: .2s; }
        .typing-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes bounce { 0%,60%,100% { transform: translateY(0); } 30% { transform: translateY(-6px); } }

        /* ══════════════════════════════════════
           SETTINGS PANEL
        ══════════════════════════════════════ */
        .settings-panel {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: var(--bg-settings);
            z-index: 100;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform .25s ease;
        }
        .settings-panel.open { transform: translateX(0); }
        .settings-header {
            background: var(--bg-settings-hd);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .settings-header h2 { color: var(--action-color); font-size: 18px; font-weight: 500; flex: 1; }
        .settings-back { background: none; border: none; color: var(--action-color); font-size: 18px; cursor: pointer; padding: 4px 8px; border-radius: 50%; }
        .settings-back:hover { background: var(--action-hover); }
        .settings-body { flex: 1; overflow-y: auto; padding: 16px; }
        .settings-section { margin-bottom: 24px; }
        .settings-section-title { color: var(--accent); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; padding: 0 4px; }
        .settings-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 14px;
            border-radius: 10px;
            background: var(--bg-settings-row);
            margin-bottom: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s;
        }
        .settings-row:hover { opacity: .85; }
        .settings-row-icon { width: 38px; height: 38px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; }
        .settings-row-info { flex: 1; }
        .settings-row-label { color: var(--text-primary); font-size: 15px; }
        .settings-row-sub { color: var(--text-secondary); font-size: 12px; margin-top: 2px; }
        .settings-row-arrow { color: var(--text-secondary); font-size: 13px; }

        /* Theme picker */
        .theme-options { display: flex; gap: 12px; padding: 4px; }
        .theme-card {
            flex: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: border-color .2s;
        }
        .theme-card.selected { border-color: var(--accent); }
        .theme-card-preview { height: 70px; display: flex; }
        .theme-card-preview .tc-sidebar { width: 35%; }
        .theme-card-preview .tc-chat { flex: 1; }
        .theme-card-label { padding: 8px; text-align: center; font-size: 13px; font-weight: 500; color: var(--text-primary); background: var(--bg-settings-row); }

        /* Dark preview */
        .theme-card.dark-card .tc-sidebar { background: #111b21; }
        .theme-card.dark-card .tc-chat { background: #0b141a; }
        /* Light preview */
        .theme-card.light-card .tc-sidebar { background: #ffffff; }
        .theme-card.light-card .tc-chat { background: #f0f2f5; }

        .settings-logout { display: flex; align-items: center; gap: 14px; padding: 13px 14px; border-radius: 10px; background: var(--bg-settings-row); margin-bottom: 6px; cursor: pointer; border: none; width: 100%; text-decoration: none; transition: opacity .15s; }
        .settings-logout:hover { opacity: .85; }
        .settings-logout-icon { width: 38px; height: 38px; border-radius: 50%; background: #e53935; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; }
        .settings-logout-label { color: #e53935; font-size: 15px; font-weight: 500; }
    </style>
</head>
<body>
<div class="container">

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fa-brands fa-whatsapp"></i> Laravel ChatBot</h2>
            <div class="header-actions">
                <a href="/contacts/add" title="Add Contact"><i class="fa-solid fa-user-plus"></i></a>
                <a href="/contacts" title="Contacts"><i class="fa-solid fa-address-book"></i></a>
                <button onclick="openSettings()" title="Settings"><i class="fa-solid fa-gear"></i></button>
            </div>
        </div>

        <!-- SETTINGS PANEL -->
        <div class="settings-panel" id="settingsPanel">
            <div class="settings-header">
                <button class="settings-back" onclick="closeSettings()"><i class="fa-solid fa-arrow-left"></i></button>
                <h2>Settings</h2>
            </div>
            <div class="settings-body">

                <div class="settings-section">
                    <div class="settings-section-title">Account</div>
                    <a href="/profile" class="settings-row">
                        <div class="settings-row-icon"><i class="fa-solid fa-circle-user"></i></div>
                        <div class="settings-row-info">
                            <div class="settings-row-label">Profile</div>
                            <div class="settings-row-sub">Edit your name, photo and about</div>
                        </div>
                        <i class="fa-solid fa-chevron-right settings-row-arrow"></i>
                    </a>
                </div>

                <div class="settings-section">
                    <div class="settings-section-title">Appearance</div>
                    <div class="theme-options">
                        <div class="theme-card dark-card" id="themeCardDark" onclick="setTheme('dark')">
                            <div class="theme-card-preview">
                                <div class="tc-sidebar"></div>
                                <div class="tc-chat"></div>
                            </div>
                            <div class="theme-card-label">Dark</div>
                        </div>
                        <div class="theme-card light-card" id="themeCardLight" onclick="setTheme('light')">
                            <div class="theme-card-preview">
                                <div class="tc-sidebar"></div>
                                <div class="tc-chat"></div>
                            </div>
                            <div class="theme-card-label">Light</div>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <div class="settings-section-title">Session</div>
                    <a href="/logout" class="settings-logout">
                        <div class="settings-logout-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                        <span class="settings-logout-label">Log out</span>
                    </a>
                </div>

            </div>
        </div>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search...">
        </div>
        <div class="chat-list" id="chatList">
            @forelse($conversations as $conv)
                @php
                    $other       = $conv->otherUser(auth()->id());
                    $last        = $conv->latestMessage;
                    $displayName = $contactNames[$other->id] ?? $other->name;
                @endphp
                <div class="chat-item"
                     id="ci-{{ $other->id }}"
                     data-uid="{{ $other->id }}"
                     data-name="{{ $displayName }}"
                     data-phone="{{ $other->phone }}"
                     data-convid="{{ $conv->id }}"
                     onclick="openChat(this)">
                    <div class="avatar">
                        @if($other->avatar)
                            <img src="{{ asset('storage/'.$other->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($displayName,0,1)) }}
                        @endif
                        <span class="online-dot" id="dot-{{ $other->id }}"></span>
                    </div>
                    <div class="chat-info">
                        <div class="chat-info-top">
                            <span class="chat-name">{{ $displayName }}</span>
                            <span class="chat-time" id="ct-{{ $other->id }}">{{ $last ? $last->created_at->format('H:i') : '' }}</span>
                        </div>
                        <div class="chat-bottom">
                            <span class="chat-preview" id="cp-{{ $other->id }}">{{ $last ? Str::limit($last->message,38) : $other->phone }}</span>
                            <span class="badge" id="badge-{{ $other->id }}">{{ $conv->unread_count > 0 ? $conv->unread_count : '' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding:40px 20px;text-align:center;color:#667781">No conversations yet</div>
            @endforelse
        </div>
    </div>

    <div class="chat-area" id="chatArea">
        <div class="empty-state">
            <i class="fa-brands fa-whatsapp" style="font-size:80px;color:#00a884"></i>
            <h3>Laravel ChatBot</h3>
            <p>Select a chat to start messaging</p>
        </div>
    </div>

</div>

<script>
const ME   = {{ auth()->id() }};
const CSRF = '{{ csrf_token() }}';

// State
let activeConvId  = null;
let activeUserId  = null;
let activePhone   = null;
let activeName    = null;
let onlineUsers   = {};

// Pre-load badge counts
@foreach($conversations as $conv)
    @php $o = $conv->otherUser(auth()->id()); @endphp
    @if($conv->unread_count > 0)
        showBadge({{ $o->id }}, {{ $conv->unread_count }});
    @endif
@endforeach

/* ── OPEN CHAT ── */
function openChat(el) {
    const uid    = parseInt(el.dataset.uid);
    const name   = el.dataset.name;
    const phone  = el.dataset.phone;
    const convId = parseInt(el.dataset.convid);

    activeUserId  = uid;
    activeName    = name;
    activePhone   = phone;
    activeConvId  = convId;

    document.querySelectorAll('.chat-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    hideBadge(uid);

    // Load messages
    fetch(`/chat-data/${uid}`)
        .then(r => r.json())
        .then(data => {
            activeConvId = data.conversation.id;
            // Update data attr in case conv was just created
            el.dataset.convid = activeConvId;
            buildChatUI();
            renderMessages(data.messages);
            markRead();
        });
}

/* ── BUILD CHAT UI ── */
function buildChatUI() {
    const online   = !!onlineUsers[activeUserId];
    const item     = document.getElementById(`ci-${activeUserId}`);
    const avatarEl = item?.querySelector('.avatar');
    const hasImg   = avatarEl?.querySelector('img');
    const avatarHtml = hasImg
        ? `<img src="${hasImg.src}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;position:absolute;top:0;left:0">`
        : x(activeName[0].toUpperCase());

    document.getElementById('chatArea').innerHTML = `
        <div class="chat-header">
            <div class="avatar" style="overflow:hidden">
                ${avatarHtml}
                <span class="online-dot ${online?'show':''}" id="hDot"></span>
            </div>
            <div class="chat-header-info">
                <h3>${x(activeName)}</h3>
                <p class="status ${online?'online':''}" id="hStatus">${online ? 'online' : x(activePhone)}</p>
            </div>
        </div>
        <div class="messages-box" id="msgBox">
            <div class="typing-indicator" id="typingIndicator">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="input-area">
            <input id="msgInput" placeholder="Type a message" onkeydown="if(event.key==='Enter')send()" oninput="onTyping()">
            <button class="send-btn" onclick="send()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>`;
}

/* ── RENDER MESSAGES ── */
function renderMessages(msgs) {
    const box = document.getElementById('msgBox');
    if (!box) return;
    // Keep typing indicator, clear messages before it
    const indicator = document.getElementById('typingIndicator');
    box.innerHTML = '';
    msgs.forEach(m => addMsg(m, false));
    if (indicator) box.appendChild(indicator);
    scrollDown();
}

/* ── ADD SINGLE MESSAGE ── */
function addMsg(m, scroll = true) {
    const box = document.getElementById('msgBox');
    if (!box) return;

    const mine = m.sender_id == ME;
    const ts   = m.tick_status || (m.is_read ? 'read' : (m.delivered_at ? 'delivered' : 'sent'));
    // Use current time for brand-new messages (no created_at yet), else parse from server
    const time = m.created_at
        ? new Date(m.created_at).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',hour12:false})
        : new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',hour12:false});

    const div = document.createElement('div');
    div.className = `msg ${mine ? 'sent' : 'received'}`;
    div.dataset.id = m.id;
    div.innerHTML = `
        <div class="bubble">
            <div class="msg-text">${x(m.message)}</div>
            <div class="msg-meta">
                <span class="msg-time">${time}</span>
                ${mine ? `<span class="tick ${ts}-tick" id="tick-${m.id}">${ts==='sent'?'✓':'✓✓'}</span>` : ''}
            </div>
        </div>`;
    box.insertBefore(div, document.getElementById('typingIndicator'));
    if (scroll) scrollDown();
}

/* ── SEND ── */
function send() {
    const input = document.getElementById('msgInput');
    const text  = input?.value.trim();
    if (!text || !activeConvId) return;
    input.value = '';

    fetch('/chat/send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ conversation_id: activeConvId, message: text })
    })
    .then(r => r.json())
    .then(m => {
        addMsg(m);
        updatePreview(activeUserId, text);
    });
}

/* ── TYPING ── */
let typingTimer = null;

function onTyping() {
    if (!activeConvId || !window.Echo) return;
    // whisper to the other user
    window.Echo.private(`chat.${activeConvId}`).whisper('typing', { userId: ME });
    // stop typing after 2s of no input
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        window.Echo.private(`chat.${activeConvId}`).whisper('stopTyping', { userId: ME });
    }, 2000);
}

function showTyping(show) {
    const el = document.getElementById('typingIndicator');
    if (!el) return;
    el.classList.toggle('show', show);
    if (show) scrollDown();
}

/* ── MARK READ ── */
function markRead() {
    if (!activeConvId) return;
    fetch(`/chat/mark-read/${activeConvId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    });
    hideBadge(activeUserId);
}

/* ── BADGE ── */
function showBadge(uid, count) {
    const b = document.getElementById(`badge-${uid}`);
    if (!b) return;
    b.textContent = count;
    b.classList.add('show');
}

function hideBadge(uid) {
    const b = document.getElementById(`badge-${uid}`);
    if (!b) return;
    b.textContent = '';
    b.classList.remove('show');
}

function incBadge(uid) {
    if (uid == activeUserId) return;
    const b = document.getElementById(`badge-${uid}`);
    if (!b) return;
    const cur = parseInt(b.textContent) || 0;
    b.textContent = cur + 1;
    b.classList.add('show');
}

/* ── PREVIEW ── */
function updatePreview(uid, text) {
    const p = document.getElementById(`cp-${uid}`);
    const t = document.getElementById(`ct-${uid}`);
    if (p) p.textContent = text.slice(0, 38);
    if (t) t.textContent = new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',hour12:false});
    const item = document.getElementById(`ci-${uid}`);
    const list = document.getElementById('chatList');
    if (item && list) list.prepend(item);
}

/* ── TICKS ── */
function setTick(msgId, status) {
    const el = document.getElementById(`tick-${msgId}`);
    if (!el) return;
    el.className  = `tick ${status}-tick`;
    el.textContent = status === 'sent' ? '✓' : '✓✓';
}

function setAllRead() {
    document.querySelectorAll('.tick.sent-tick, .tick.delivered-tick').forEach(el => {
        el.className  = 'tick read-tick';
        el.textContent = '✓✓';
    });
}

/* ── SCROLL ── */
function scrollDown() {
    const b = document.getElementById('msgBox');
    if (b) b.scrollTop = b.scrollHeight;
}

/* ── ESCAPE HTML ── */
function x(s) {
    const d = document.createElement('div');
    d.textContent = String(s);
    return d.innerHTML;
}

/* ── ONLINE DOT ── */
function setDot(uid, on) {
    const d = document.getElementById(`dot-${uid}`);
    if (d) d.classList.toggle('show', on);
    if (uid == activeUserId) {
        const hd = document.getElementById('hDot');
        const hs = document.getElementById('hStatus');
        if (hd) hd.classList.toggle('show', on);
        if (hs) { hs.textContent = on ? 'online' : activePhone; hs.className = `status ${on?'online':''}`; }
    }
}

/* ── HEADER TYPING STATUS ── */
let stopTypingTimer = null;
function setHeaderTyping(isTyping) {
    const hs = document.getElementById('hStatus');
    if (!hs) return;
    if (isTyping) {
        hs.textContent = 'typing...';
        hs.className   = 'status online';
        clearTimeout(stopTypingTimer);
        stopTypingTimer = setTimeout(() => setHeaderTyping(false), 3000);
    } else {
        const online = !!onlineUsers[activeUserId];
        hs.textContent = online ? 'online' : activePhone;
        hs.className   = `status ${online ? 'online' : ''}`;
    }
}

/* ════════════════════════════════════════
   LARAVEL ECHO — SUBSCRIBE ALL CONVS
════════════════════════════════════════ */
function initEcho() {
    if (!window.Echo) {
        setTimeout(initEcho, 100);
        return;
    }

    @foreach($conversations as $conv)
    @php $o = $conv->otherUser(auth()->id()); @endphp
    window.Echo.private('chat.{{ $conv->id }}')
        .listen('.PrivateMessageSent', e => {
            const m   = e.message;
            const uid = {{ $o->id }};
            if (m.sender_id == ME) return;
            if (activeConvId == {{ $conv->id }}) {
                addMsg(m);
                markRead();
            } else {
                updatePreview(uid, m.message);
                incBadge(uid);
            }
        })
        .listen('.MessageRead', e => {
            if (activeConvId == {{ $conv->id }}) setAllRead();
        })
        .listen('.MessageDelivered', e => {
            setTick(e.message_id, 'delivered');
        })
        .listenForWhisper('typing', e => {
            if (e.userId == ME) return;
            if (activeConvId == {{ $conv->id }}) {
                showTyping(true);
                setHeaderTyping(true);
            }
        })
        .listenForWhisper('stopTyping', e => {
            if (e.userId == ME) return;
            if (activeConvId == {{ $conv->id }}) {
                showTyping(false);
                setHeaderTyping(false);
            }
        });
    @endforeach

    /* ── PRESENCE ── */
    window.Echo.join('online')
        .here(users => users.forEach(u => { if (u.id != ME) { onlineUsers[u.id]=true; setDot(u.id,true); } }))
        .joining(u  => { if (u.id==ME) return; onlineUsers[u.id]=true; setDot(u.id,true); })
        .leaving(u  => { delete onlineUsers[u.id]; setDot(u.id,false); });
}

/* ── SETTINGS ── */
function openSettings() {
    document.getElementById('settingsPanel').classList.add('open');
}
function closeSettings() {
    document.getElementById('settingsPanel').classList.remove('open');
}

/* ── THEME ── */
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    document.getElementById('themeCardDark').classList.toggle('selected', theme === 'dark');
    document.getElementById('themeCardLight').classList.toggle('selected', theme === 'light');
}

// Apply saved theme on load
(function() {
    const saved = localStorage.getItem('theme') || 'dark';
    setTheme(saved);
})();

initEcho();

/* ── SEARCH ── */
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.chat-item').forEach(el => {
        el.style.display = el.dataset.name.toLowerCase().includes(q) ? 'flex' : 'none';
    });
});

/* ── AUTO OPEN ── */
const openId = new URLSearchParams(location.search).get('open');
if (openId) {
    const el = document.querySelector(`.chat-item[data-uid="${openId}"]`);
    if (el) openChat(el);
    else fetch(`/chat-data/${openId}`).then(r=>r.json()).then(()=>location.href='/app');
}
</script>
</body>
</html>
