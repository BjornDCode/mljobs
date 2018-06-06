@extends('layouts.app')

@section('content')
    <form class="content publish-job-form" method="post" action="/job/{{ $job->id }}">
        @method('PATCH')
        {{ csrf_field() }}
        <div class="group">
            <label>Title</label>
            <input type="text" name="title" value="{{ $job->title }}">
        </div>
        <div class="group">
            <label>Description</label>
            <textarea name="description">{{ $job->description }}</textarea>
        </div>
        <div class="group">
            <label>Company</label>
            <input type="text" name="company" value="{{ $job->company }}">
        </div>
        <div class="group">
            <label>Location</label>
            <input type="text" name="location" value="{{ $job->location }}">
        </div>
        <div class="group">
            <label>Salary</label>
            <input type="text" name="salary" value="{{ $job->salary }}">
        </div>
        <div class="group">
            <label>Type</label>
            <input type="text" name="type" value="{{ $job->type }}">
        </div>
        <div class="group">
            <label>URL</label>
            <input type="text" name="apply_url" value="{{ $job->apply_url }}">
        </div>
        <div class="group">
            <label>Company Logo</label>
            <image-upload logo="{{ $job->company_logo }}"></image-upload>
        </div>
        <div class="group">
            <label>Published</label>
            <input type="hidden" name="published" value="0">
            <input type="checkbox" name="published" @if($job->published) checked @endif value="1">
        </div>
        <button type="submit" class="button">Publish</button>
    </form>
@endsection
