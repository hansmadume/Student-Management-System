<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | {{ config('app.name', 'Student Management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="register-page-app" data-vue-component="RegisterPage"></div>
    <script id="register-page-app-props" type="application/json">
        {!! json_encode([
            'csrfToken' => csrf_token(),
            'registerUrl' => route('register'),
            'loginUrl' => route('login'),
            'old' => [
                'name' => old('name', ''),
                'email' => old('email', ''),
            ],
            'errors' => $errors->toArray(),
        ]) !!}
    </script>
</body>
</html>