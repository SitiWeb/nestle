<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ url('/') }}/favicon.ico">
    <title>Nestlé International Travel Retail</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.7.2/dist/svgMap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.7.2/dist/svgMap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-50 min-h-[calc(100vh-64px)]">

        <div class="md:flex md:items-center md:justify-between bg-neutral-200 h-14 fixed w-100" style="width:100%;z-index:2;top:0;">
       
            <div class="w-1/5 px-4 py-1">
                <div style="margin-left:30px;margin-right:30px">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="w-20  fill-current text-gray-500" />
                    </a>
                </div>
            </div>
    
        </div>
        <div class=" flex mt-14">

            <div class="w-1/5 h-full bg-white shadow flex-grow ">
                <div class="fixed" style="width: 20%;">
                    @include('layouts.navigation')
                </div>
            </div>
            <div class="w-4/5 flex-grow">


                <!-- Page Content -->
                @if (Route::currentRouteName() === 'map')
                    <!-- Route name does not match the desired route name -->
                    <!-- Place other HTML or Blade code here -->
                    <main class="">
                    @else
                        <main>
                            <!-- Page Heading -->
                            @if (isset($header))
                                <div>
                                    {{ $header }}
                                </div>
                            @endif
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @endif
                {{ $slot }}
            </div>
            </main>
        </div>
    </div>



    </div>
    @livewireScripts
</body>

</html>
