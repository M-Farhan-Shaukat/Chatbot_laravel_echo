<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #111b21;
            min-height: 100vh;
        }
        .header {
            background: #202c33;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid #2a3942;
        }
        .header a {
            color: #00a884;
            text-decoration: none;
            font-size: 14px;
        }
        .header h2 {
            color: #e9edef;
            font-size: 18px;
            font-weight: 400;
            flex: 1;
        }
        .add-btn {
            background: #00a884;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
        }
        .contact-list {
            max-width: 600px;
            margin: 0 auto;
            padding: 16px;
        }
        .contact-item {
            background: #202c33;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .contact-item:hover {
            background: #2a3942;
        }
        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667781, #374248);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e9edef;
            font-weight: 600;
            font-size: 18px;
            flex-shrink: 0;
        }
        .contact-info h3 {
            color: #e9edef;
            font-size: 16px;
            font-weight: 400;
        }
        .contact-info p {
            color: #667781;
            font-size: 13px;
            margin-top: 2px;
        }
        .empty {
            text-align: center;
            color: #667781;
            padding: 60px 20px;
        }
        .empty p { margin-top: 8px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <a href="/app">← Back</a>
        <h2>Contacts</h2>
        <a href="/contacts/add" class="add-btn">+ Add Contact</a>
    </div>

    <div class="contact-list">
        @forelse($contacts as $c)
            <a href="/chat/{{ $c->contactUser->id }}" class="contact-item">
                <div class="avatar">{{ strtoupper(substr($c->contactUser->name, 0, 1)) }}</div>
                <div class="contact-info">
                    <h3>{{ $c->contactUser->name }}</h3>
                    <p>{{ $c->contactUser->phone }}</p>
                </div>
            </a>
        @empty
            <div class="empty">
                <div style="font-size: 48px;">👥</div>
                <p>No contacts yet. Add someone to start chatting.</p>
            </div>
        @endforelse
    </div>
</body>
</html>
