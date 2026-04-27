<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#111b21;min-height:100vh}
        .header{background:#202c33;padding:14px 20px;display:flex;align-items:center;gap:16px;border-bottom:1px solid #2a3942}
        .header a.back{color:#00a884;text-decoration:none;font-size:14px}
        .header h2{color:#e9edef;font-size:18px;font-weight:400;flex:1}
        .add-btn{background:#00a884;color:#fff;padding:7px 14px;border-radius:20px;text-decoration:none;font-size:13px}
        .search-wrap{padding:10px 16px;background:#111b21}
        .search-wrap input{width:100%;padding:9px 14px;background:#202c33;border:none;border-radius:8px;color:#e9edef;font-size:14px}
        .search-wrap input:focus{outline:none}
        .search-wrap input::placeholder{color:#667781}
        .list{max-width:600px;margin:0 auto;padding:8px 16px}
        a.contact{background:#202c33;border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:14px;margin-bottom:8px;text-decoration:none;transition:background .2s}
        a.contact:hover{background:#2a3942}
        .av{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#667781,#374248);display:flex;align-items:center;justify-content:center;color:#e9edef;font-weight:600;font-size:18px;flex-shrink:0;overflow:hidden}
        .av img{width:100%;height:100%;object-fit:cover}
        .ci h3{color:#e9edef;font-size:16px;font-weight:400}
        .ci p{color:#667781;font-size:13px;margin-top:2px}
        .empty{text-align:center;color:#667781;padding:60px 20px}
        .empty p{margin-top:8px;font-size:14px}
    </style>
</head>
<body>
<div class="header">
    <a href="/app" class="back">← Back</a>
    <h2>Contacts</h2>
    <a href="/contacts/add" class="add-btn">+ Add</a>
</div>

<div class="search-wrap">
    <form method="GET" action="/contacts">
        <input type="text" name="search" placeholder="Search contacts..." value="{{ request('search') }}" oninput="this.form.submit()">
    </form>
</div>

<div class="list">
    @forelse($contacts as $c)
        <a href="/chat/{{ $c->contactUser->id }}" class="contact">
            <div class="av">
                @if($c->contactUser->avatar)
                    <img src="{{ asset('storage/'.$c->contactUser->avatar) }}" alt="">
                @else
                    {{ strtoupper(substr($c->display_name, 0, 1)) }}
                @endif
            </div>
            <div class="ci">
                <h3>{{ $c->display_name }}</h3>
                <p>{{ $c->contactUser->phone }}</p>
            </div>
        </a>
    @empty
        <div class="empty">
            <div style="font-size:48px">👥</div>
            <p>No contacts found.</p>
        </div>
    @endforelse
</div>
</body>
</html>
