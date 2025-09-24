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
    <div id="app">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center sm:pt-0">
            <div>
                <a href="/">
                    <h1 class="font-bold text-3xl mx-auto text-[#2D82B7] dark:text-[#42E2B8] py-5" >Learn Platform</h1>
                </a>
            </div>

            <div
                class="md:w-1/2  overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
