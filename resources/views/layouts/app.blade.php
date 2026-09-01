<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('/kdklogo1.ico') }}">

        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/i18n/ja.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
            ])
            
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/9.1.1/bignumber.min.js"></script>
            
        <!-- 追加CSS -->
        <link rel="stylesheet" href="{{ asset('css/gen_style.css') }}">

        

        <style>
            /* @import url('https://fonts.googleapis.com/css?family=Noto+Sans+JP'); */
        </style>
    </head>
    <body class="antialiased"  style=" font-family:serif;">
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script> --}}
        <div class="min-h-screen bg-gray-200 dark:bg-gray-900" >
            @include('layouts.navigation')
            @include('layouts.header')


            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                      <span class="font-medium"></span> {{ $error }}
                                    </div>
                                  </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="position: absolute; z-index:20; top:2px;">
                <div nowrap class=" w-[70vw] success flex items-center px-4 py-1 mb-4 mx-[20%] text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <svg class="w-6 h-6 text-green-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div> 
                        <span class="font-medium whitespace-nowrap">{{ session('success') }}</span>
                    </div>
                </div>
                </div>
            @endif
                <script>
                    setTimeout(function() {
                        const alert = $('.success');
                        if (alert) {
                            alert.fadeOut(1000);
                        }
                    }, 1500); // 3秒後にアラートを非表示にする
                </script>
            
            <!-- Page Content -->
            <main class="px-2">
                {{-- {{ $slot }} --}}
                @yield('content')
            </main>
        </div>
        <link rel="stylesheet" href="{{ asset('animation/animate.css') }}">
        <script src="{{ asset('animation/jquery.textillate.js') }}"></script>
        <script src="{{ asset('animation/jquery.lettering.js') }}"></script>
        <script>
var asdf = $('.drawer-navigation');var tlt = $('.openAni');tlt.textillate({autoStart: false, initialDelay: 5,minDisplayTime: 5,in: {effect: 'wobble',delayScale: 2,delay: 3.5,shuffle: false  
}});asdf.click(function() {tlt.textillate('in');});$(document).on('focus', 'input:not(.noneFocus), select:not(.noneFocus), textarea:not(.noneFocus)', function(e){$(this).addClass('bg-yellow-100');});
$(document).on('blur', 'input:not(.noneFocus), select:not(.noneFocus), textarea:not(.noneFocus)', function(e){$(this).removeClass('bg-yellow-100');});
            function addHiroiColor(){$(document).off('focus', '.hiroiInputColor').on('focus', '.hiroiInputColor', function(e){$(this).addClass('bg-yellow-100');});$(document).off('blur', '.hiroiInputColor').on('blur', '.hiroiInputColor', function(e){$(this).removeClass('bg-yellow-100');});}
        </script>


        {{-- Copyright (c) 2014 Ivan Bozhanov
        Permission is hereby granted, free of charge, to any person
        obtaining a copy of this software and associated documentation
        files (the "Software"), to deal in the Software without
        restriction, including without limitation the rights to use,
        copy, modify, merge, publish, distribute, sublicense, and/or sell
        copies of the Software, and to permit persons to whom the
        Software is furnished to do so, subject to the following
        conditions:

        The above copyright notice and this permission notice shall be
        included in all copies or substantial portions of the Software.
        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
        EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
        OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
        NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
        HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
        WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
        FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
        OTHER DEALINGS IN THE SOFTWARE. --}}
    </body>
</html>
