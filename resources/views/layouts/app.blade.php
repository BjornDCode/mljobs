<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Machine Learning Jobs') }}</title>

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    
    <div id="app">
        <div class="container">
            <header class="page-header">
                <a href="/" class="page-header__logo">
                    @svg('logo')
                </a>
            </header>
            @yield('content')
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
