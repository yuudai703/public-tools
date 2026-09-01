<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('/kdklogo1.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- アニメーション --}}
        <link rel="stylesheet" href="{{ asset('animation/animate.css') }}">
        <script src="{{ asset('animation/jquery.textillate.js') }}"></script>
        <script src="{{ asset('animation/jquery.lettering.js') }}"></script>
        {{-- ============= --}}
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>
            <h1 class="text-xl text-center items-center tlt text-gray-800" style=" font-family:serif;">
                Welcome to Ｍ-System made in ＫＤＫ
            </h1>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <script defer>
            $('.tlt').textillate({
                loop: true,
                initialDelay: 0,
                in: {
                    effect: 'bounce',
                    delayScale: 2,
                    delay: 50,
                    sync: false,
                    
                },
                 out: {
                    effect: 'bounce',
                    delayScale: 2,
                    delay: 50,
                    sync: false,
                    reverse: true
                }
            });
        
        </script>
    </body>
</html>
