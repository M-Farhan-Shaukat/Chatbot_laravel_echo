<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Chatbot</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#111b21;height:100vh;display:flex;align-items:center;justify-content:center}
        .card{background:#202c33;border-radius:12px;padding:48px 40px;width:100%;max-width:400px;text-align:center}
        .logo{font-size:56px;margin-bottom:16px}
        h1{color:#e9edef;font-size:24px;font-weight:400;margin-bottom:8px}
        p{color:#667781;font-size:14px;margin-bottom:32px;line-height:20px}
        .fg{margin-bottom:16px;text-align:left}
        label{display:block;color:#8696a0;font-size:13px;margin-bottom:6px}
        input{width:100%;padding:12px 16px;background:#2a3942;border:1px solid #2a3942;border-radius:8px;color:#e9edef;font-size:15px}
        input:focus{outline:none;border-color:#00a884}
        input::placeholder{color:#667781}
        button{width:100%;padding:14px;background:#00a884;color:#fff;border:none;border-radius:8px;font-size:16px;font-weight:500;cursor:pointer;margin-top:8px}
        button:hover{background:#06cf9c}
        .err{background:#2a1a1a;color:#f15c6d;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;text-align:left}
    </style>
</head>
<body>
<div class="card">
    <div class="logo">💬</div>
    <h1>Laravel Chatbot</h1>
    <p>Enter your phone number to continue.</p>

    @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="fg">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="+92 300 0000000" required autofocus value="{{ old('phone') }}">
        </div>
        <button type="submit">Continue</button>
    </form>
</div>
</body>
</html>
