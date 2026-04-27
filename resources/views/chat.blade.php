<!DOCTYPE html>
<html>
<head>
    <title>Laravel Chat with Laravel Echo and Reverb</title>

    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }

        .app {
            display: flex;
            height: 90vh;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        /* LEFT SIDEBAR */
        .sidebar {
            width: 30%;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }

        .chat-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .chat-item:hover {
            background: #f5f5f5;
        }

        /* RIGHT CHAT AREA */
        .chat-area {
            width: 70%;
            display: flex;
            flex-direction: column;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: #e9ecef;
        }

        .msg {
            margin-bottom: 10px;
            display: flex;
        }

        .msg.me {
            justify-content: flex-end;
        }

        .bubble {
            padding: 10px 15px;
            border-radius: 18px;
            max-width: 60%;
        }

        .me .bubble {
            background: #0d6efd;
            color: white;
        }

        .other .bubble {
            background: white;
        }

        .input-box {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .input-box input {
            flex: 1;
            margin-right: 10px;
        }
    </style>
</head>

<body>

<div class="app">

    <!-- LEFT SIDE: CHAT LIST -->
    <div class="sidebar">

        <h5 class="p-3">Chats</h5>

        @foreach($conversations as $conv)

            @php
                $userId = $conv->user_one == auth()->id()
                    ? $conv->user_two
                    : $conv->user_one;

                $user = App\Models\User::find($userId);
            @endphp

            <a href="/chat/{{ $user->id }}" style="text-decoration:none; color:black;">
                <div class="chat-item">
                    <b>{{ $user->name }}</b><br>
                    <small>{{ $user->phone }}</small>
                </div>
            </a>

        @endforeach

    </div>

    <!-- RIGHT SIDE -->
    <div class="chat-area">

        <div class="p-3 border-bottom">
            Select a chat
        </div>

        <div class="messages">
            <p class="text-muted text-center mt-5">
                Open a chat to start messaging
            </p>
        </div>

    </div>

</div>

</body>
</html>
