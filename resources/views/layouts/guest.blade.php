<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-4 py-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.35),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.28),_transparent_34%)]"></div>
            <div class="absolute left-10 top-10 h-28 w-28 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 h-36 w-36 rounded-full bg-sky-400/20 blur-3xl"></div>

            <div class="relative w-full max-w-md">
                <div class="mb-6 flex justify-center">
                    <a href="/" class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/10 px-5 py-3 text-sm font-semibold text-white shadow-2xl backdrop-blur transition hover:bg-white/15">
                        <x-application-logo class="h-8 w-8 fill-current text-white" />
                        <span>Student Management</span>
                    </a>
                </div>

                <div class="overflow-hidden rounded-[2rem] border border-white/20 bg-white/95 px-6 py-8 shadow-2xl shadow-indigo-950/40 backdrop-blur sm:px-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
