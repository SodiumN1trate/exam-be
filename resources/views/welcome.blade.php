<h1>Login</h1>
<ul>
    @foreach($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
</ul>
<form method="POST" action="{{ route('login.process') }}">
    @csrf
    <input type="text" name="username" placeholder="Enter username">
    <br>
    <input type="password" name="password" placeholder="Enter password">
    <br>
    <br>
    <button>Login</button>
</form>
