@extends('layouts.app')

@section('content')
    <main class="single-job">
        <div class="job__content">
            <h1>{{ $job->title }}</h1>
            <div class="job__description">
                {{ $job->description }}
            </div>
            <div class="job__meta">
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
            <div class="job__actions">
                <a class="button" target="_blank" href="{{ $job->apply_url }}">Apply</a>
                <div class="social">
                    <a class="twitter" target="_blank" href="https://twitter.com/home?status=Check out this awesome AI job!+{{ url()->current() }}">
                        @svg('twitter')
                    </a>
                    <a class="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}">
                        @svg('facebook')
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
