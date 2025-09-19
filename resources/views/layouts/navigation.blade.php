<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Alpine Store for Dark Mode -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                dark: localStorage.getItem('theme') === 'dark',
                toggle() {
                    this.dark = !this.dark;
                    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', this.dark);
                }
            });
        });
    </script>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">


                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('users.all')" :active="request()->routeIs('users.all')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif

                    @if (in_array(Auth::user()->role, ['admin', 'instructor']))
                        <x-nav-link :href="route('courses.all')" :active="request()->routeIs('courses.all')">
                            {{ __('Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('assignments.all')" :active="request()->routeIs('assignments.all')">
                            {{ __('Assignments') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings & Actions (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications Dropdown -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button aria-label="Notifications"
                            class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                            <!-- Bell Icon -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405C18.79 15.21 18 13.7 18 12V8a6 6 0 10-12 0v4c0 1.7-.79 3.21-1.595 3.595L3 17h5m7 0a3 3 0 11-6 0h6z" />
                            </svg>

                            <!-- Red Badge -->
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-h-64 overflow-y-auto w-80">

                            {{-- Unread Notifications --}}
                            @if (auth()->user()->unreadNotifications->count())
                                <h3
                                    class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600">
                                    Unread
                                </h3>
                                @foreach (auth()->user()->unreadNotifications as $notification)
                                    <div x-data="{ open: false }"
                                        class="border-b border-gray-200 dark:border-gray-600 px-4 py-2">
                                        <button @click.stop="open = !open"
                                            class="w-full text-left flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </span>
                                                @if (!empty($notification->data['email']))
                                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $notification->data['email'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <svg :class="{ 'rotate-180': open }"
                                                class="h-4 w-4 transform transition-transform duration-200"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                            class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                                            {{ $notification->data['body'] ?? 'No details available.' }}
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Read Notifications --}}
                            @if (auth()->user()->readNotifications->count())
                                <h3
                                    class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 mt-2">
                                    Read
                                </h3>
                                @foreach (auth()->user()->readNotifications as $notification)
                                    <div x-data="{ open: false }"
                                        class="border-b border-gray-200 dark:border-gray-600 px-4 py-2">
                                        <button @click.stop="open = !open"
                                            class="w-full text-left flex justify-between items-center">
                                            <div>
                                                <span class="truncate text-gray-500 dark:text-gray-400">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </span>
                                                @if (!empty($notification->data['email']))
                                                    <span class="ml-2 text-xs text-gray-400">
                                                        {{ $notification->data['email'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <svg :class="{ 'rotate-180': open }"
                                                class="h-4 w-4 transform transition-transform duration-200"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                            class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                                            {{ $notification->data['body'] ?? 'No details available.' }}
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Empty State --}}
                            @if (auth()->user()->notifications->count() === 0)
                                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    No notifications
                                </div>
                            @endif

                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown with Avatar -->
                @auth
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button aria-label="User menu"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                                <div class="flex items-center space-x-2">
                                    <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}"
                                        alt="User Avatar"
                                        class="h-8 w-8 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                    <span>{{ Auth::user()->name }}</span>
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info -->
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">
                                    {{ Auth::user()->email }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 capitalize">
                                    Role: {{ Auth::user()->role }}
                                </div>
                            </div>

                            <!-- Links -->
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>

                            @if (Auth::user()->role !== 'student')
                                <x-dropdown-link :href="route('dashboard')">{{ __('Dashboard') }}</x-dropdown-link>
                            @endif

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                <!-- Dark/Light Mode Toggle -->
                <div @click="$store.theme.toggle()"
                    class="relative w-12 h-6 cursor-pointer rounded-full transition-colors"
                    :class="$store.theme.dark ? 'bg-gray-600' : 'bg-gray-300'">

                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md flex items-center justify-center transition-transform duration-300"
                        :class="$store.theme.dark ? 'translate-x-6' : 'translate-x-0'">

                        <!-- Light Mode Icon -->
                        <svg x-show="!$store.theme.dark" xmlns="http://www.w3.org/2000/svg"
                            class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM4.22 4.22a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L4.22 5.28a.75.75 0 010-1.06zM2 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 012 10zM4.22 15.78a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM10 16.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM15.78 15.78a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM16.25 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM15.78 4.22a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L15.78 5.28a.75.75 0 010-1.06z" />
                            <circle cx="10" cy="10" r="3.5" />
                        </svg>

                        <!-- Dark Mode Icon -->
                        <svg x-show="$store.theme.dark" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-900"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8 8 0 1010.586 10.586z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" aria-label="Toggle menu"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.all')" :active="request()->routeIs('users.all')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endif

            @if (in_array(Auth::user()->role, ['admin', 'instructor']))
                <x-responsive-nav-link :href="route('courses.all')" :active="request()->routeIs('courses.all')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('assignments.all')" :active="request()->routeIs('assignments.all')">
                    {{ __('Assignments') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4 flex items-center space-x-3">
                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}"
                    alt="User Avatar"
                    class="h-10 w-10 rounded-full object-cover border border-gray-300 dark:border-gray-600">

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // Immediately set dark/light mode before anything renders
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>
