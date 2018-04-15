<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Machine Learning Jobs') }}</title>

    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700">
    <link rel="stylesheet" href="https://use.typekit.net/evl3rav.css">
</head>
<body>
    
    <div id="app">
        <header>
            <a class="logo" href="/">
                @svg('logo')
            </a>
            <nav>
                <a href="/" class="{{ (request()->input('filter') === null) ? 'active' : '' }}">All</a>
                <a href="/?filter=full-time" class="{{ (request()->input('filter') === 'full-time') ? 'active' : '' }}">Full Time</a>
                <a href="/?filter=part-time" class="{{ (request()->input('filter') === 'part-time') ? 'active' : '' }}">Part Time</a>
                <a href="/?filter=internship" class="{{ (request()->input('filter') === 'internship') ? 'active' : '' }}">Internship</a>
                <a href="/?filter=freelance" class="{{ (request()->input('filter') === 'freelance') ? 'active' : '' }}">Freelance</a>
                <a href="/?filter=temporary" class="{{ (request()->input('filter') === 'temporary') ? 'active' : '' }}">Temporary</a>
            </nav>
        </header>
        @yield('content')
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
