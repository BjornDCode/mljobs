@extends('layouts.app')

@section('content')
    <main class="content">
        <h1>Dashboard</h1>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            Logout
        </a>    
        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
                @include('jobs._card', [ 'url' => 'unpublished' ])
    </main>
@endsection

