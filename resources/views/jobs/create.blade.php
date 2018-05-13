@extends('layouts.app')

@section('content')
    <main class="purchase-job">
        <div class="benefits">
            <div class="benefit">
                @svg('layout')
                Highlighted job at the top of the page
            </div>
            <div class="benefit">
                @svg('calendar')
                Active for 30 days
            </div>
            <div class="benefit">
                @svg('mail')
                Emailed to all newsletter subscribers
            </div>
            <div class="benefit">
                @svg('share')
                Shared on AI Jobs Twitter page
            </div>
        </div>
        <h1>Post a job</h1>
        <purchase-job-form></purchase-job-form>
    </main>
@endsection
