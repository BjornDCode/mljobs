@extends('layouts.app')

@section('content')
    <form class="content single-job-form" method="post" action="/job">
        @method('PATCH')
        {{ csrf_field() }}
        {{-- Update form here --}}
    </form>
@endsection
