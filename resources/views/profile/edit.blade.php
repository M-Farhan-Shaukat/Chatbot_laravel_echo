<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#111b21;min-height:100vh}
        .header{background:#202c33;padding:14px 20px;display:flex;align-items:center;gap:16px;border-bottom:1px solid #2a3942}
        .header a{color:#00a884;text-decoration:none;font-size:14px}
        .header h2{color:#e9edef;font-size:18px;font-weight:400}
        .body{max-width:500px;margin:24px auto;padding:0 16px}
        .avatar-wrap{display:flex;flex-direction:column;align-items:center;margin-bottom:32px}
        .av{width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#667781,#374248);display:flex;align-items:center;justify-content:center;color:#e9edef;font-size:36px;font-weight:600;overflow:hidden;margin-bottom:12px;cursor:pointer;position:relative}
        .av img{width:100%;height:100%;object-fit:cover}
        .av-hint{color:#00a884;font-size:13px;cursor:pointer}
        .card{background:#202c33;border-radius:12px;padding:24px;margin-bottom:16px}
        label{display:block;color:#8696a0;font-size:12px;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px}
        input,textarea{width:100%;padding:12px 0;background:transparent;border:none;border-bottom:1px solid #2a3942;color:#e9edef;font-size:15px;font-family:inherit;resize:none}
        input:focus,textarea:focus{outline:none;border-bottom-color:#00a884}
        input::placeholder,textarea::placeholder{color:#667781}
        .hint{color:#667781;font-size:12px;margin-top:6px}
        button{width:100%;padding:14px;background:#00a884;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:500;cursor:pointer;margin-top:8px}
        button:hover{background:#06cf9c}
        .ok{background:#0d2b22;color:#00a884;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;text-align:center}
        #avatarInput{display:none}
    </style>
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

        <div class="card" style="color:#667781;font-size:14px">
            <label>Phone</label>
            <div style="color:#e9edef;padding:12px 0;border-bottom:1px solid #2a3942">{{ $user->phone }}</div>
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
