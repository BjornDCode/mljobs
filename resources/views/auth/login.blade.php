@extends('layouts.app')

@section('content')
    <div class="content">
        <form action="/login" method="post" class="login">
            {{ csrf_field() }}
            <h1>Login</h1>
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password"  name="password" placeholder="password" required>
            <button type="submit" class="button">Login</button>
        </form>
    </div>
@endsection
