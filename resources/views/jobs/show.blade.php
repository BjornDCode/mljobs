@extends('layouts.app')

@section('content')
    <main class="job">
        @if ($job->company_logo)
            <img class="job--image" src="{{ $job->company_logo }}" alt="{{ $job->company }}">
        @else
            <div class="placeholder-image">
                <span>
                    {{ substr($job->company, 0, 1) }}
                </span>
            </div>
        @endif
        <div class="job--content">
            <h1>{{ $job->title }}</h1>
            <div class="job--description">
                {{ $job->description }}
            </div>
            <div class="job--meta">
                @if ($job->company)
                    <p>@svg('home') {{ $job->company }}</p>
                @endif
                @if ($job->location)
                    <p>@svg('pin') {{ $job->location }}</p>
                @endif
                @if ($job->salary)
                    <p>@svg('dollar') {{ $job->salary }}</p>
                @endif
                @if ($job->type)
                    <p>@svg('hourglass') {{ $job->type }}</p>
                @endif
            </div>
            <div class="job--actions">
                <a class="apply" target="_blank" href="{{ $job->apply_url }}">Apply</a>
                <div class="social">
                    <a class="twitter" target="_blank" href="https://twitter.com/home?status=Check out this awesome maching learning job!+{{ url()->current() }}">
                        @svg('twitter')
                    </a>
                    <a class="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}">
                        @svg('facebook')
                    </a>
                </div>
            </div>
        </div>
        <span class="job--timeago">
            {{ $job->created_at->diffForHumans(null, true, true) }}
        </span>
    </main>
@endsection
