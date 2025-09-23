<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body x-data="app()" x-init="init()"
    :class="darkMode
        ?
        'dark bg-[#07004D] text-[#F3DFBF] transition-colors duration-300' :
        'bg-[#eeecea] text-[#07004D] transition-colors duration-300'"
    class="flex flex-col min-h-screen">

    <!-- Session Notifications -->
    <div x-show="showNotification" x-transition class="fixed bottom-4 right-4 z-50">
        @if (session('success'))
            <div class="bg-[#42E2B8] text-[#07004D] px-4 py-2 rounded shadow-lg flex items-center justify-between">
                {{ session('success') }}
                <button @click="showNotification = false" class="ml-2">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-[#EB8A90] text-white px-4 py-2 rounded shadow-lg flex items-center justify-between">
                {{ session('error') }}
                <button @click="showNotification = false" class="ml-2">&times;</button>
            </div>
        @endif
    </div>

    <header :class="scrolled ? 'shadow-md' : ''"
        class="w-full bg-[#2D82B7] dark:bg-[#07004D] text-white sticky top-0 z-50 transition-colors duration-300 border-b border-[#42E2B8]/40">
        @if (Route::has('login'))
            <nav class="flex items-center justify-between px-6 py-4">

                <div class="flex gap-10 items-center">
                    <!-- Logo / Home -->
                    <a href="{{ url('/') }}" class="text-lg font-semibold hover:text-[#42E2B8]">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    <!-- Desktop Navigation Links -->
                    <div class="hidden lg:flex gap-6">
                        <a href="{{ url('/') }}"
                            class="{{ request()->is('/') ? 'font-bold border-b-2 border-[#42E2B8] text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">
                            Home
                        </a>
                        <a href="{{ route('courses.list') }}"
                            class="{{ request()->is('courses*') ? 'font-bold border-b-2 border-[#42E2B8] text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">
                            Courses
                        </a>
                        <a href="{{ url('/about') }}"
                            class="{{ request()->is('about') ? 'font-bold border-b-2 border-[#42E2B8] text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">
                            About
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Desktop Dark Mode Toggle -->
                    <div class="hidden lg:flex items-center">
                        <div @click="darkMode = !darkMode"
                            class="relative w-12 h-6 cursor-pointer rounded-full transition-colors"
                            :class="darkMode ? 'bg-[#42E2B8]' : 'bg-[#F3DFBF]'">
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md flex items-center justify-center transition-transform duration-300"
                                :class="darkMode ? 'translate-x-6' : 'translate-x-0'">
                                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <circle cx="10" cy="10" r="3.5" />
                                </svg>
                                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[#07004D]"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8 8 0 1010.586 10.586z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Auth Links (Desktop) -->
                    @auth
                        <div class="hidden lg:flex items-center gap-4 relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('learning_logo.png') }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover border border-[#42E2B8]">
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 top-10 mt-2 w-44 bg-[#2D82B7] dark:bg-[#07004D] border border-[#42E2B8]/40 rounded-md shadow-lg z-50">
                                <div class="flex flex-col py-1">
                                    <a href="{{ route('profile.update') }}"
                                        class="px-4 py-2 hover:bg-[#42E2B8]/20">Profile</a>
                                    @if (Auth::user()->role !== 'student')
                                        <a href="{{ url('/dashboard') }}"
                                            class="px-4 py-2 hover:bg-[#42E2B8]/20">Dashboard</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#EB8A90]/20">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="hidden lg:flex gap-4">
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 border border-transparent rounded hover:border-[#42E2B8]">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 border border-[#42E2B8] rounded hover:bg-[#42E2B8] hover:text-[#07004D]">Register</a>
                            @endif
                        </div>
                    @endauth

                    <!-- Mobile Hamburger -->
                    <button @click="openMenu = !openMenu" class="lg:hidden focus:outline-none ml-2"
                        aria-label="Toggle menu" :aria-expanded="openMenu">
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
                </div>


                <!-- Mobile Drawer -->
                <div x-show="openMenu" x-transition:enter="transition transform duration-300"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="fixed top-0 left-0 h-full w-3/4 bg-[#2D82B7] dark:bg-[#07004D] text-[#F3DFBF] z-50 shadow-lg p-6 flex flex-col justify-between">

                    <!-- Close Button -->
                    <button @click="openMenu = false" class="self-end mb-6 p-2 rounded hover:bg-[#42E2B8]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Navigation Links -->
                    <div class="flex flex-col gap-4">
                        <a href="{{ url('/') }}"
                            class="px-4 py-3 rounded {{ request()->is('/') ? 'bg-[#42E2B8] text-[#07004D] font-bold' : 'hover:bg-[#42E2B8]/20' }}">Home</a>
                        <a href="{{ route('courses.list') }}"
                            class="px-4 py-3 rounded {{ request()->is('courses*') ? 'bg-[#42E2B8] text-[#07004D] font-bold' : 'hover:bg-[#42E2B8]/20' }}">Courses</a>
                        <a href="{{ url('/about') }}"
                            class="px-4 py-3 rounded {{ request()->is('about') ? 'bg-[#42E2B8] text-[#07004D] font-bold' : 'hover:bg-[#42E2B8]/20' }}">About</a>
                    </div>

                    <!-- Auth / User Links -->
                    <div class="flex flex-col gap-3 mt-6 border-t border-[#42E2B8]/40 pt-4">
                        @auth
                            <a href="{{ route('profile.update') }}"
                                class="px-4 py-3 rounded hover:bg-[#42E2B8]/20">Profile</a>
                            @if (Auth::user()->role !== 'student')
                                <a href="{{ url('/dashboard') }}"
                                    class="px-4 py-3 rounded hover:bg-[#42E2B8]/20">Dashboard</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-3 rounded hover:bg-[#EB8A90]/20">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-3 rounded hover:bg-[#42E2B8]/20">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-3 rounded hover:bg-[#42E2B8]/20">Register</a>
                            @endif
                        @endauth
                    </div>

                    <!-- Dark Mode Toggle -->
                    <div @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="mt-6 flex items-center gap-3 cursor-pointer px-4 py-3 bg-[#42E2B8]/10 rounded">
                        <span x-show="!darkMode">🌙 Dark Mode</span>
                        <span x-show="darkMode">☀️ Light Mode</span>
                    </div>
                </div>

                <!-- Overlay -->
                <div x-show="openMenu" @click="openMenu = false" x-transition.opacity
                    class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
            </nav>
        @endif
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full">
        <div class="max-w-screen-2xl mx-auto px-4 py-6">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer
        class="w-full bg-[#2D82B7] dark:bg-[#07004D] text-[#F3DFBF] mt-12 py-10 border-t border-[#42E2B8]/40 transition-colors duration-300">
        <div class="max-w-screen-2xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand -->
            <div>
                <h2 class="text-xl font-bold text-[#42E2B8] mb-3">{{ config('app.name', 'Laravel') }}</h2>
                <p class="text-[#F3DFBF]">Empowering learners with modern, practical courses. Learn. Build. Grow. 🚀
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold text-[#42E2B8] mb-3">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}"
                            class="{{ request()->is('/') ? 'font-bold text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">Home</a>
                    </li>
                    <li><a href="{{ route('courses.list') }}"
                            class="{{ request()->is('courses*') ? 'font-bold text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">Courses</a>
                    </li>
                    <li><a href="{{ url('/about') }}"
                            class="{{ request()->is('about') ? 'font-bold text-[#42E2B8]' : 'hover:text-[#42E2B8]' }}">About</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold text-[#42E2B8] mb-3">Contact</h3>
                <p><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ config('app.contact_email') }}" class="hover:text-[#42E2B8]">{{ config('app.contact_email') }}</a></p>
                <p><i class="fa-solid fa-phone"></i> <a href="tel:{{ config('app.phone_number') }}" class="hover:text-[#42E2B8]">{{ config('app.phone_number') }}</a></p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="hover:text-[#42E2B8]" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53..."></path>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-[#42E2B8]" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35C.6 0 0 .6..."></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-xs text-[#F3DFBF]">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </footer>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function app() {
            return {
                scrolled: false,
                openMenu: false,
                darkMode: localStorage.getItem('darkMode') === 'true',
                showNotification: true,
                init() {
                    window.addEventListener('scroll', () => this.scrolled = window.scrollY > 10);
                    this.$watch('darkMode', value => localStorage.setItem('darkMode', value));
                    setTimeout(() => this.showNotification = false, 5000);
                }
            }
        }
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"
        integrity="sha512-6BTOlkauINO65nLhXhthZMtepgJSghyimIalb+crKRPhvhmsCdnIuGcVbR5/aQY2A+260iC1OPy1oCdB6pSSwQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
