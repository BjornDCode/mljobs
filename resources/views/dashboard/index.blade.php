@extends('layouts.app')

@section('content')
    <main class="content dashboard">
        <header>
            <h1>Dashboard</h1>
            <div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    Logout
                </a>    
                <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div> 
        </header>
        
        <ul class="jobs">
            @foreach ($jobs as $job)
                @include('jobs._card', [ 'url' => 'unpublished' ])
            @endforeach
        </ul>

    </main>
@endsection

