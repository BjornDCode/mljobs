@extends('layouts.app')

@section('content')
    <main class="content">
        <newsletter-form></newsletter-form>
        {{-- @include('jobs._filters') --}}

        <div class="jobs">
            @foreach ($groups as $title => $jobs)
                <div class="jobs-group">
                    <h2>{{ $title }}</h2>
                    <ul>
                        @foreach ($jobs as $job)
                            @include('jobs._card')
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </main>
@endsection
