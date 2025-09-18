<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body :class="darkMode ? 'dark bg-[#0a0a0a] text-[#EDEDEC]' : 'bg-[#FDFDFC] text-[#1b1b18]'" x-data="{ scrolled: false, openMenu: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 });
    $watch('darkMode', value => localStorage.setItem('darkMode', value))" class="p-6 lg:p-8 flex flex-col min-h-screen">

    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-4 right-4">
        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>


    <!-- Sticky Header -->
    <header x-data="{ scrolled: false, openMenu: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })" :class="scrolled ? 'shadow-md' : ''"
        class="w-full lg:max-w-6xl mx-auto mb-6 bg-[#FDFDFC] dark:bg-[#0a0a0a] transition-shadow duration-300 sticky top-0 z-50">

        @if (Route::has('login'))
            <nav class="flex items-center justify-between p-4">

                <!-- Logo / Home -->
                <a href="{{ url('/') }}" class="text-lg font-semibold hover:underline">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Hamburger (mobile) -->
                <button @click="openMenu = !openMenu" class="lg:hidden focus:outline-none">
                    <svg x-show="!openMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="openMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Navigation Links -->
                <div :class="openMenu ? 'block' : 'hidden'"
                    class="w-full lg:flex lg:items-center lg:w-auto lg:gap-4 mt-2 lg:mt-0">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="block px-4 py-2 border border-transparent hover:border-gray-300 dark:hover:border-gray-600 rounded-sm transition">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 border border-transparent hover:border-gray-300 dark:hover:border-gray-600 rounded-sm transition">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 border border-transparent hover:border-gray-300 dark:hover:border-gray-600 rounded-sm transition">
                            Log In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 border border-gray-300 dark:border-gray-600 hover:border-gray-500 rounded-sm transition">
                                Register
                            </a>
                        @endif
                    @endauth
                    <!-- Dark/Light Mode Toggle Switch -->
                    <div @click="darkMode = !darkMode"
                        class="relative w-12 h-6 cursor-pointer rounded-full transition-colors"
                        :class="darkMode ? 'bg-gray-600' : 'bg-gray-300'">

                        <!-- Circle -->
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md flex items-center justify-center transition-transform duration-300"
                            :class="darkMode ? 'translate-x-6' : 'translate-x-0'">

                            <!-- Icons -->
                            <!-- Light Mode Icon -->
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-yellow-400"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM4.22 4.22a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L4.22 5.28a.75.75 0 010-1.06zM2 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 012 10zM4.22 15.78a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM10 16.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM15.78 15.78a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM16.25 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM15.78 4.22a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L15.78 5.28a.75.75 0 010-1.06z" />
                                <circle cx="10" cy="10" r="3.5" />
                            </svg>

                            <!-- Dark Mode Icon -->
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-900"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8 8 0 1010.586 10.586z" />
                            </svg>

                        </div>
                    </div>



                </div>
            </nav>
        @endif
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full lg:max-w-6xl mx-auto">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer
        class="w-full lg:max-w-6xl mx-auto mt-12 py-6 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-600 dark:text-gray-400">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
    </footer>

    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>


</html>
