<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="AI Jobs - Find a job in the AI and Machine Learning Industry" />
    <meta property="og:description" content="A daily curated list of jobs in the field of AI and Machine Learning." />
    <meta property="og:image" content="/images/facebook.png" />

    <meta name="twitter:site" content="summary"></meta>
    <meta name="twitter:card" content="aijobs_work"></meta>
    <meta name="twitter:creator" content="bjornlindholmdk"></meta>
    <meta name="twitter:image" content="/images/twitter.png"></meta>

    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

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
