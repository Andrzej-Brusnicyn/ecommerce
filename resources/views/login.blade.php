<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>

@if($errors->any())
    <div style="color: red;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ url('/login') }}">
    @csrf

    <div>
        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<a href="{{ url('/register') }}">Register</a>
</body>
</html>