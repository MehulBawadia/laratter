<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @yield('title')
    <meta name="robots" content="NOINDEX, NOFOLLOW" />

    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/vendor.css') }}" />
    <link href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet" />

    @yield('pageStyles')
</head>
<body class="bg-gray-300">
    @yield('content')

    <script src="{{ asset('/js/app.js') }}"></script>
    @yield('pageScripts')
</body>
</html>
