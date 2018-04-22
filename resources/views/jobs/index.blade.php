@extends('layouts.app')

@section('content')
    <main class="jobs">
        @if (count($jobs))
            <ul>
                @foreach($jobs as $job)
                    @include('jobs._card')
                @endforeach      
            </ul>
        @else 
            <p>There are unfortunately no jobs to show at the moment.</p>
        @endif  
    </main>
@endsection
