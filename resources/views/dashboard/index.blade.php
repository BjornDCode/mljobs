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

        <form class="content publish-job-form" method="post" action="/job/store">
            {{ csrf_field() }}
            <div class="group">
                <label>Title</label>
                <input type="text" name="title">
            </div>
            <div class="group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>
            <div class="group">
                <label>Company</label>
                <input type="text" name="company">
            </div>
            <div class="group">
                <label>Location</label>
                <input type="text" name="location">
            </div>
            <div class="group">
                <label>Salary</label>
                <input type="text" name="salary">
            </div>
            <div class="group">
                <label>Type</label>
                <input type="text" name="type">
            </div>
            <div class="group">
                <label>URL</label>
                <input type="text" name="apply_url">
            </div>
            <div class="group">
                <label>Company Logo</label>
                <image-upload></image-upload>
            </div>
            <div class="group">
                <label>Published</label>
                <input type="hidden" name="published" value="0">
                <input type="checkbox" name="published" value="1">
            </div>
            <button type="submit" class="button">Publish</button>
        </form>

        <hr>
        
        <ul class="jobs">
            @foreach ($jobs as $job)
                @include('jobs._card')
            @endforeach
        </ul>

    </main>
@endsection

