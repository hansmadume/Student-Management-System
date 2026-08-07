<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ config('app.name', 'Student Management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="login-page-app" data-vue-component="LoginPage"></div>
    <script id="login-page-app-props" type="application/json">
        {!! json_encode([
            'csrfToken' => csrf_token(),
            'loginUrl' => route('login'),
            'registerUrl' => route('register'),
            'passwordRequestUrl' => Route::has('password.request') ? route('password.request') : '',
            'status' => session('status') ?? '',
            'old' => [
                'email' => old('email', ''),
            ],
            'errors' => $errors->toArray(),
        ]) !!}
    </script>
</body>
</html>