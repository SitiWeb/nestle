<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ url('/') }}/favicon.ico">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen grid grid-cols-2">
            <!-- Left Column -->
            <div class="flex flex-col justify-center items-center px-6 py-4 bg-gray-100">

                <div class="w-full sm:max-w-md mt-6 mx-auto overflow-hidden"> <!-- Added mx-auto to center the login form -->
                    {{ $slot }}
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex items-center justify-center bg-cover bg-no-repeat bg-center" style="background-image: url('{{ route('image', ['filename' => 'background-login.png']) }}');">
                <!-- Content of the right column goes here -->
            </div>
        </div>
</body>

</html>
