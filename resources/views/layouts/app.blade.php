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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body x-data="{ sidebarOpen: false }" class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        @include('layouts.navigation')

        <!-- Page Content -->
        <div class="flex w-full">

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed sm:static inset-y-0 left-0 w-64 bg-[#2D82B7] dark:bg-[#07004D] text-white dark:text-[#F3DFBF] transform transition-transform duration-300 z-30 sm:translate-x-0">
                <div class="p-4 text-2xl font-bold">Dashboard</div>
                <nav class="mt-6 flex flex-col space-y-1 px-2">
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700 dark:bg-blue-800' : '' }}"><i
                                class="fa-solid fa-house me-3"></i>Home</a>
                        <a href="{{ route('users.all') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 {{ request()->routeIs('users.all') ? 'bg-blue-700 dark:bg-blue-800' : '' }}"><i
                                class="fa-solid fa-users me-3"></i>Users</a>
                    @endif
                    @if (in_array(Auth::user()->role, ['admin', 'instructor']))
                        <a href="{{ route('courses.all') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 {{ request()->routeIs('courses.all') ? 'bg-blue-700 dark:bg-blue-800' : '' }}"><i
                                class="fa-solid fa-graduation-cap me-3"></i>Courses</a>
                        <a href="{{ route('assignments.all') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 {{ request()->routeIs('assignments.all') ? 'bg-blue-700 dark:bg-blue-800' : '' }}"><i
                                class="fa-solid fa-spell-check me-3"></i>Assignments</a>
                    @endif
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow rounded mb-6">
                        <div class="mx-auto py-4 px-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Notifications -->
                <div class="fixed bottom-4 right-4 z-50 space-y-2">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms role="alert"
                            class="bg-green-500 dark:bg-green-600 text-white px-4 py-2 rounded shadow-lg flex items-center justify-between">
                            {{ session('success') }}
                            <button @click="show=false" class="ml-2 text-xl font-bold">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms role="alert"
                            class="bg-red-500 dark:bg-red-600 text-white px-4 py-2 rounded shadow-lg flex items-center justify-between">
                            {{ session('error') }}
                            <button @click="show=false" class="ml-2 text-xl font-bold">&times;</button>
                        </div>
                    @endif
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"
        integrity="sha512-6BTOlkauINO65nLhXhthZMtepgJSghyimIalb+crKRPhvhmsCdnIuGcVbR5/aQY2A+260iC1OPy1oCdB6pSSwQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>


</html>
