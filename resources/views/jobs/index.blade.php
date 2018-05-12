@extends('layouts.app')

@section('content')
    <main class="content">
        <newsletter-form></newsletter-form>
        @include('jobs._filters')

        <div class="jobs">
            <div class="job-group">
                <h2></h2>
            </div>
        </div>
    </main>
@endsection
