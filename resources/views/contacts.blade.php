<h2>Your Contacts</h2>

<a href="/logout">Logout</a>

<hr>

@foreach($users as $user)
    <div style="padding:10px; border:1px solid #ddd; margin:5px;">

        <b>{{ $user->name }}</b><br>
        {{ $user->phone }}

        <br><br>

        <a href="/chat/{{ $user->id }}">Message</a>

    </div>
@endforeach
