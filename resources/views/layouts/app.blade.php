<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Machine Learning Jobs') }}</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700">
    <link rel="stylesheet" href="https://use.typekit.net/evl3rav.css">
</head>
<body>
    
    <div id="app">
        @yield('content')
    </div>

    <script src="js/app.js"></script>
</body>
</html>
