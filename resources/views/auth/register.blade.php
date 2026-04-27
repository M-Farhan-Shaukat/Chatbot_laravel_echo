<h2>Register</h2>

<form method="POST" action="/register">
    @csrf

    <input name="name" placeholder="Name"><br>
    <input name="phone" placeholder="Phone"><br>
    <input name="password" type="password"><br>

    <button>Register</button>
</form>
