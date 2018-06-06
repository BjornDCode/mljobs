<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Machine Learning Jobs') }}</title>

    <script>
        window.AIJobs = {
            stripe: {
                publicKey: '{{ config('services.stripe.key') }}'
            }
        }
    </script>

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    
    <div id="app">
        <div class="container">
            <header class="page-header">
                <a href="/" class="page-header__logo">
                    @svg('logo')
                </a>
                @if( auth()->check() && auth()->user()->isAdmin() )
                    <a href="/dashboard">Dashboard</a>
                @endif()
                @if (!request()->is('featured-job/create'))
                    <a class="button" href="/job/create">Post a job</a>
                @endif
            </header>
            @yield('content')
            <footer class="page-footer">
                <p>Made by <a href="https://twitter.com/bjornlindholmdk" target="_blank">@bjornlindholmdk</a></p>
            </footer>
            <a class="twitter-link" href="https://twitter.com/aijobs_work" target="_black">
                @svg('twitter')
            </a>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="/js/app.js"></script>
</body>
</html>
