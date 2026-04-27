<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contact</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#111b21;height:100vh;display:flex;align-items:center;justify-content:center}
        .card{background:#202c33;border-radius:12px;padding:40px;width:100%;max-width:380px}
        a.back{display:inline-flex;align-items:center;gap:6px;color:#00a884;text-decoration:none;font-size:14px;margin-bottom:24px}
        h2{color:#e9edef;font-size:20px;font-weight:400;margin-bottom:8px}
        p{color:#667781;font-size:13px;margin-bottom:28px}
        label{display:block;color:#8696a0;font-size:13px;margin-bottom:6px}
        input{width:100%;padding:12px 16px;background:#2a3942;border:1px solid #2a3942;border-radius:8px;color:#e9edef;font-size:15px;margin-bottom:16px}
        input:focus{outline:none;border-color:#00a884}
        input::placeholder{color:#667781}
        button{width:100%;padding:13px;background:#00a884;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:500;cursor:pointer}
        button:hover{background:#06cf9c}
        .err{background:#2a1a1a;color:#f15c6d;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
        .ok{background:#0d2b22;color:#00a884;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
    </style>
</head>
<body>
<div class="card">
    <a href="/app" class="back">← Back</a>
    <h2>Add Contact</h2>
    <p>Enter phone number. Name is optional — if left blank, contact's registered name will be used.</p>

    @if(session('error'))  <div class="err">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="ok">{{ session('success') }}</div> @endif

    <form method="POST" action="/contacts/add">
        @csrf
        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="+92 300 0000000" required autofocus>
        <label>Name <span style="color:#667781">(optional)</span></label>
        <input type="text" name="name" placeholder="e.g. Ali Bhai">
        <button type="submit">Add Contact</button>
    </form>
</div>
</body>
</html>
