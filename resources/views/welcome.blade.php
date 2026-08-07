<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Student Management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="welcome-page-app" data-vue-component="WelcomePage"></div>
    <script id="welcome-page-app-props" type="application/json">
        {!! json_encode([
            'isAuthenticated' => auth()->check(),
            'loginUrl' => route('login'),
            'registerUrl' => Route::has('register') ? route('register') : '',
            'dashboardUrl' => url('/dashboard'),
        ]) !!}
    </script>
</body>
</html>