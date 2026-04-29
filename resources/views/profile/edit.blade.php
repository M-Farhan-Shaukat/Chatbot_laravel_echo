<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-body:    #111b21;
            --bg-header:  #202c33;
            --bg-card:    #202c33;
            --border:     #2a3942;
            --text:       #e9edef;
            --text-sub:   #667781;
            --accent:     #00a884;
            --accent-h:   #06cf9c;
            --input-bg:   transparent;
            --ok-bg:      #0d2b22;
            --ok-color:   #00a884;
            --action-color: #00a884;
        }
        [data-theme="light"] {
            --bg-body:    #f0f2f5;
            --bg-header:  #0084ff;
            --bg-card:    #ffffff;
            --border:     #e4e6eb;
            --text:       #050505;
            --text-sub:   #65676b;
            --accent:     #0084ff;
            --accent-h:   #0073e6;
            --input-bg:   transparent;
            --ok-bg:      #e7f3ff;
            --ok-color:   #0084ff;
            --action-color: #ffffff;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:var(--bg-body);min-height:100vh}
        .header{background:var(--bg-header);padding:14px 20px;display:flex;align-items:center;gap:16px;border-bottom:1px solid var(--border)}
        .header a{color:var(--action-color);text-decoration:none;font-size:14px}
        .header h2{color:var(--action-color);font-size:18px;font-weight:400}
        .body{max-width:500px;margin:24px auto;padding:0 16px}
        .avatar-wrap{display:flex;flex-direction:column;align-items:center;margin-bottom:32px}
        .av{width:100px;height:100px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;color:#fff;font-size:36px;font-weight:600;overflow:hidden;margin-bottom:12px;cursor:pointer;position:relative}
        .av img{width:100%;height:100%;object-fit:cover}
        .av-hint{color:var(--accent);font-size:13px;cursor:pointer}
        .card{background:var(--bg-card);border-radius:12px;padding:24px;margin-bottom:16px;border:1px solid var(--border)}
        label{display:block;color:var(--text-sub);font-size:12px;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px}
        input,textarea{width:100%;padding:12px 0;background:var(--input-bg);border:none;border-bottom:1px solid var(--border);color:var(--text);font-size:15px;font-family:inherit;resize:none}
        input:focus,textarea:focus{outline:none;border-bottom-color:var(--accent)}
        input::placeholder,textarea::placeholder{color:var(--text-sub)}
        .hint{color:var(--text-sub);font-size:12px;margin-top:6px}
        button[type=submit]{width:100%;padding:14px;background:var(--accent);color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:500;cursor:pointer;margin-top:8px}
        button[type=submit]:hover{background:var(--accent-h)}
        .ok{background:var(--ok-bg);color:var(--ok-color);padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;text-align:center}
        #avatarInput{display:none}
    </style>
    <script>
        // Apply theme before render to avoid flash
        (function(){ document.getElementById('htmlRoot').setAttribute('data-theme', localStorage.getItem('theme') || 'dark'); })();
    </script>
</head>
<body>
<div class="header">
    <a href="/app">← Back</a>
    <h2>Edit Profile</h2>
</div>

<div class="body">
    @if(session('success'))
        <div class="ok">{{ session('success') }}</div>
    @endif

    <form method="POST" action="/profile" enctype="multipart/form-data">
        @csrf

        <div class="avatar-wrap">
            <div class="av" onclick="document.getElementById('avatarInput').click()">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" id="avatarPreview" alt="">
                @else
                    <span id="avatarInitial">{{ $user->initial }}</span>
                    <img src="" id="avatarPreview" style="display:none" alt="">
                @endif
            </div>
            <span class="av-hint" onclick="document.getElementById('avatarInput').click()">Change photo</span>
            <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="previewAvatar(this)">
        </div>

        <div class="card">
            <label>Your Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Enter your name">
            <div class="hint">This is your name visible to others</div>
        </div>

        <div class="card">
            <label>About</label>
            <textarea name="about" rows="2" placeholder="Write something about yourself...">{{ old('about', $user->about) }}</textarea>
            <div class="hint">{{ strlen($user->about ?? '') }}/200 characters</div>
        </div>

        <div class="card">
            <label>Phone</label>
            <div style="color:var(--text);padding:12px 0;border-bottom:1px solid var(--border)">{{ $user->phone }}</div>
            <div class="hint">Phone number cannot be changed</div>
        </div>

        <button type="submit">Save Changes</button>
    </form>
</div>

<script>
function previewAvatar(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        const initial = document.getElementById('avatarInitial');
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (initial) initial.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
</body>
</html>
