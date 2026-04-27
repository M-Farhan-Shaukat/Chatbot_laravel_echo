<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #111b21; height: 100vh; overflow: hidden; }
        .container { display: flex; height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: 380px; flex-shrink: 0; background: #111b21; display: flex; flex-direction: column; border-right: 1px solid #2a3942; }
        .sidebar-header { background: #202c33; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
        .sidebar-header h2 { color: #e9edef; font-size: 18px; font-weight: 500; }
        .header-actions { display: flex; gap: 7px; }
        .header-actions a { color: #aebac1; text-decoration: none; font-size: 12px; padding: 5px 10px; border-radius: 20px; background: #2a3942; }
        .header-actions a:hover { background: #374248; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; position: absolute; top: 0; left: 0; }        .search-box { padding: 8px 10px; }
        .search-box input { width: 100%; padding: 9px 14px; background: #202c33; border: none; border-radius: 8px; color: #e9edef; font-size: 14px; }
        .search-box input:focus { outline: none; }
        .search-box input::placeholder { color: #667781; }
        .chat-list { flex: 1; overflow-y: auto; }
        .chat-item { padding: 11px 16px; display: flex; align-items: center; gap: 12px; cursor: pointer; border-bottom: 1px solid #1f2c34; transition: background .15s; }
        .chat-item:hover { background: #202c33; }
        .chat-item.active { background: #2a3942; }
        .avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667781, #374248); display: flex; align-items: center; justify-content: center; color: #e9edef; font-weight: 600; font-size: 17px; flex-shrink: 0; position: relative; }
        .online-dot { position: absolute; bottom: 1px; right: 1px; width: 12px; height: 12px; background: #00a884; border-radius: 50%; border: 2px solid #111b21; display: none; }
        .online-dot.show { display: block; }
        .chat-info { flex: 1; min-width: 0; }
        .chat-info-top { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .chat-name { color: #e9edef; font-size: 15px; }
        .chat-time { color: #667781; font-size: 11px; }
        .chat-bottom { display: flex; justify-content: space-between; align-items: center; }
        .chat-preview { color: #667781; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
        .badge { background: #00a884; color: #fff; font-size: 11px; font-weight: 600; min-width: 20px; height: 20px; border-radius: 10px; display: none; align-items: center; justify-content: center; padding: 0 5px; margin-left: 6px; flex-shrink: 0; }
        .badge.show { display: flex; }

        /* CHAT AREA */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: #0b141a; }
        .chat-header { background: #202c33; padding: 11px 18px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #2a3942; }
        .chat-header .avatar { width: 40px; height: 40px; font-size: 15px; }
        .chat-header .online-dot { border-color: #202c33; }
        .chat-header-info h3 { color: #e9edef; font-size: 16px; font-weight: 400; }
        .chat-header-info .status { color: #667781; font-size: 12px; }
        .chat-header-info .status.online { color: #00a884; }
        .messages-box { flex: 1; overflow-y: auto; padding: 16px 20px; }
        .msg { display: flex; margin-bottom: 4px; }
        .msg.sent { justify-content: flex-end; }
        .bubble { max-width: 65%; padding: 7px 12px 5px; border-radius: 8px; word-wrap: break-word; }
        .msg.received .bubble { background: #202c33; color: #e9edef; }
        .msg.sent .bubble { background: #005c4b; color: #e9edef; }
        .msg-text { font-size: 14px; line-height: 20px; }
        .msg-meta { display: flex; align-items: center; justify-content: flex-end; gap: 3px; margin-top: 2px; }
        .msg-time { font-size: 11px; color: #8696a0; }
        .tick { font-size: 13px; }
        .tick.sent-tick { color: #8696a0; }
        .tick.delivered-tick { color: #8696a0; }
        .tick.read-tick { color: #53bdeb; }
        .input-area { background: #202c33; padding: 9px 18px; display: flex; gap: 10px; align-items: center; }
        .input-area input { flex: 1; padding: 11px 15px; background: #2a3942; border: none; border-radius: 8px; color: #e9edef; font-size: 15px; }
        .input-area input:focus { outline: none; }
        .input-area input::placeholder { color: #667781; }
        .send-btn { background: #00a884; color: #fff; border: none; padding: 11px 16px; border-radius: 50%; cursor: pointer; font-size: 16px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; }
        .send-btn:hover { background: #06cf9c; }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #667781; text-align: center; gap: 10px; }
        .empty-state span { font-size: 48px; }
        .empty-state h3 { color: #e9edef; font-size: 20px; font-weight: 400; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #374248; border-radius: 3px; }

        /* typing indicator */
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background: #202c33;
            border-radius: 18px;
            width: fit-content;
            margin-bottom: 8px;
        }
        .typing-indicator.show { display: flex; }
        .typing-indicator span {
            width: 7px; height: 7px;
            background: #8696a0;
            border-radius: 50%;
            animation: bounce 1.2s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: .2s; }
        .typing-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes bounce {
            0%,60%,100% { transform: translateY(0); }
            30%          { transform: translateY(-6px); }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fa-brands fa-whatsapp" style="color:#00a884"></i> WhatsApp</h2>
            <div class="header-actions">
                <a href="/contacts/add" title="Add Contact"><i class="fa-solid fa-user-plus"></i></a>
                <a href="/contacts" title="Contacts"><i class="fa-solid fa-address-book"></i></a>
                <a href="/profile" title="Profile"><i class="fa-solid fa-circle-user"></i></a>
                <a href="/logout" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
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
            <h3>WhatsApp Clone</h3>
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
