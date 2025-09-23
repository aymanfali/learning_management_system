<x-main-layout>
    <div class="py-10">
        <div class="">

            <!-- Hero Section -->
            <div class="relative overflow-hidden rounded-2xl text-center shadow-xl mb-16"
                style="background: linear-gradient(135deg, #2D82B7, #42E2B8);">
                <div class="py-20 px-6">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
                        Welcome to <span class="font-mono">Learn</span>
                    </h1>
                    <p class="text-lg sm:text-xl mb-6 max-w-2xl mx-auto text-white/90">
                        Discover new skills, track your progress, and complete courses at your own pace.
                    </p>
                    @if (!auth()->check())
                        <a href="{{ route('register') }}"
                            class="inline-block px-8 py-3 font-semibold rounded-xl bg-gradient-to-r from-[#42E2B8] to-[#F3DFBF] text-[#07004D] shadow-lg hover:scale-105 transform transition-all duration-300">
                            Get Started
                        </a>
                    @endif
                </div>
            </div>

            <!-- My Enrolled Courses -->
            @if (auth()->check() && auth()->user()->enrolledCourses->count())
                <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">My Enrolled Courses</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-15">
                    @foreach (auth()->user()->enrolledCourses as $course)
                        @php
                            $totalLessons = $course->lessons->count();
                            $completedLessons = $course->lessons
                                ->filter(
                                    fn($lesson) => $lesson
                                        ->assignment()
                                        ->where('user_id', auth()->id())
                                        ->exists(),
                                )
                                ->count();
                            $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                            $isCompleted = $progressPercent == 100;
                        @endphp
                        <div
                            class="bg-white dark:bg-[#07004D] border border-[#42E2B8]/40 rounded-2xl shadow-lg overflow-hidden flex flex-col relative hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">

                            <!-- Enrolled Badge -->
                            <span
                                class="absolute top-3 right-3 px-2 py-1 rounded-full font-semibold text-xs shadow-lg z-10
                                {{ $isCompleted ? 'bg-[#42E2B8] text-[#07004D] animate-pulse' : 'bg-[#F3DFBF] text-[#07004D]' }}">
                                Enrolled
                            </span>


                            <!-- Course Image -->
                            <div class="relative">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('learning_logo.png') }}"
                                    alt="{{ $course->name }}" class="w-full h-40 object-cover rounded-t-2xl">

                                @if ($isCompleted)
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-t-2xl">
                                        <div class="text-white font-bold flex items-center space-x-2 text-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Completed</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Course Info -->
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#07004D] dark:text-[#F3DFBF]">
                                        {{ $course->name }}
                                    </h3>
                                    <p class="text-sm text-[#07004D]/80 dark:text-[#F3DFBF]/80 mt-2 line-clamp-3">
                                        {{ $course->description }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center space-x-2">
                                        <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('learning_logo.png') }}"
                                            class="w-8 h-8 rounded-full border border-[#42E2B8]">
                                        <span
                                            class="text-sm text-[#07004D] dark:text-[#F3DFBF]">{{ $course->user->name }}</span>
                                    </div>
                                    <span class="text-xs text-[#07004D]/60 dark:text-[#F3DFBF]/60">
                                        {{ $course->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div
                                        class="w-full bg-[#EB8A90]/20 dark:bg-[#42E2B8]/20 h-2 rounded-full overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-500"
                                            style="width: {{ $progressPercent }}%; background: linear-gradient(to right, #42E2B8, #F3DFBF);">
                                        </div>
                                    </div>
                                    <span class="text-xs text-[#07004D] dark:text-[#F3DFBF] mt-1 inline-block">
                                        {{ $progressPercent }}% Complete
                                    </span>
                                </div>

                                <a href="{{ route('course.show', $course->id) }}"
                                    class="mt-4 inline-block px-4 py-2 text-center rounded-xl bg-gradient-to-r from-[#2D82B7] to-[#42E2B8] text-[#F3DFBF] font-semibold shadow hover:scale-105 transform transition-all duration-300">
                                    Show
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Popular Courses Slider -->
            <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">
                Popular Courses
            </h2>

            <div x-data="{
                scrollLeft() { this.$refs.slider.scrollBy({ left: -280, behavior: 'smooth' }) },
                    scrollRight() { this.$refs.slider.scrollBy({ left: 280, behavior: 'smooth' }) }
            }" class="relative mb-16">
                <!-- Slider Container -->
                <div x-ref="slider" class="flex overflow-x-hidden gap-6 scroll-smooth snap-x snap-mandatory no-scrollbar">
                    @foreach ($popularCourses as $course)
                        <div
                            class="flex-none w-[240px] sm:w-[260px] md:w-[280px] lg:w-[300px] bg-white dark:bg-[#07004D] rounded-2xl border border-[#42E2B8]/40 shadow-lg flex flex-col hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 snap-start">

                            <div class="relative">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('learning_logo.png') }}"
                                    alt="{{ $course->name }}" class="w-full h-40 object-cover rounded-t-2xl">
                            </div>

                            <!-- Content -->
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h3 class="text-lg font-semibold text-[#07004D] dark:text-[#F3DFBF]">
                                    {{ $course->name }}
                                </h3>
                                <p class="text-sm text-[#07004D]/80 dark:text-[#F3DFBF]/80 mt-2 line-clamp-3">
                                    {{ $course->description }}
                                </p>

                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center space-x-2">
                                        <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('learning_logo.png') }}"
                                            class="w-8 h-8 rounded-full border border-[#42E2B8]">
                                        <span class="text-sm text-[#07004D] dark:text-[#F3DFBF]">
                                            {{ $course->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-[#07004D]/60 dark:text-[#F3DFBF]/60">
                                        {{ $course->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <a href="{{ route('course.show', $course->id) }}"
                                    class="mt-4 inline-block px-4 py-2 text-center rounded-xl bg-gradient-to-r from-[#2D82B7] to-[#42E2B8] text-[#F3DFBF] font-semibold shadow hover:scale-105 transform transition-all duration-300">
                                    Show
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Arrows -->
                <button @click="scrollLeft"
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-[#07004D]/80 rounded-full shadow p-3 hover:scale-110 transition">
                    <svg class="w-6 h-6 text-[#07004D] dark:text-[#F3DFBF]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="scrollRight"
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-[#07004D]/80 rounded-full shadow p-3 hover:scale-110 transition">
                    <svg class="w-6 h-6 text-[#07004D] dark:text-[#F3DFBF]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- All Courses Section -->
            <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">All Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 ">
                @forelse($courses->whereNotIn('id', auth()->check() ? auth()->user()->enrolledCourses->pluck('id') : collect()) as $course)
                    <div
                        class="bg-white dark:bg-[#07004D] rounded-2xl border border-[#42E2B8]/40 shadow-lg overflow-hidden flex flex-col hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('learning_logo.png') }}"
                            alt="{{ $course->name }}" class="w-full h-40 object-cover rounded-t-2xl">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-[#07004D] dark:text-[#F3DFBF]">
                                    {{ $course->name }}</h3>
                                <p class="text-sm text-[#07004D]/80 dark:text-[#F3DFBF]/80 mt-2 line-clamp-3">
                                    {{ $course->description }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('learning_logo.png') }}"
                                        class="w-8 h-8 rounded-full border border-[#42E2B8]">
                                    <span
                                        class="text-sm text-[#07004D] dark:text-[#F3DFBF]">{{ $course->user->name }}</span>
                                </div>
                                <span
                                    class="text-xs text-[#07004D]/60 dark:text-[#F3DFBF]/60">{{ $course->created_at->format('M d, Y') }}</span>
                            </div>
                            <a href="{{ route('course.show', $course->id) }}"
                                class="mt-4 inline-block px-4 py-2 text-center rounded-xl bg-gradient-to-r from-[#2D82B7] to-[#42E2B8] text-[#F3DFBF] font-semibold shadow hover:scale-105 transform transition-all duration-300">
                                Show
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-[#07004D] dark:text-[#F3DFBF] col-span-full text-center">
                        No courses available yet.
                    </p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8 mb-16">
                {{ $courses->links() }}
            </div>

            <!-- Instructors Slider -->
            <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">
                Meet Our Instructors
            </h2>

            <div x-data="{
                scrollLeft() { this.$refs.slider.scrollBy({ left: -280, behavior: 'smooth' }) },
                    scrollRight() { this.$refs.slider.scrollBy({ left: 280, behavior: 'smooth' }) }
            }" class="relative mb-16">

                <!-- Slider Container -->
                <div x-ref="slider"
                    class="flex overflow-x-hidden gap-6 scroll-smooth snap-x snap-mandatory no-scrollbar pb-4">
                    @foreach ($instructors as $instructor)
                        <div
                            class="flex-none w-[220px] border border-[#42E2B8]/40 sm:w-[240px] md:w-[260px] lg:w-[280px] bg-white dark:bg-[#07004D] rounded-2xl shadow-lg flex flex-col items-center text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 snap-start p-4">

                            <!-- Profile Image with border -->
                            <div class="relative">
                                <img src="{{ $instructor->image ? asset('storage/' . $instructor->image) : asset('learning_logo.png') }}"
                                    class="w-24 h-24 rounded-full border-4 border-[#42E2B8] mx-auto object-cover"
                                    alt="{{ $instructor->name }}">
                            </div>

                            <!-- Name & Bio -->
                            <h3 class="mt-4 font-semibold text-[#07004D] dark:text-[#F3DFBF]">{{ $instructor->name }}
                            </h3>
                            <p class="text-sm text-[#07004D]/80 dark:text-[#F3DFBF]/80 mt-2 line-clamp-3">
                                {{ $instructor->bio }}
                            </p>

                            <span class="text-xs text-[#07004D]/60 dark:text-[#F3DFBF]/60 mt-2 inline-block">Courses:
                                {{ $instructor->courses->count() }}</span>


                            <!-- Optional CTA -->
                            {{-- <a href=""
                                class="mt-4 inline-block px-4 py-2 text-center rounded-xl bg-gradient-to-r from-[#2D82B7] to-[#42E2B8] text-[#F3DFBF] font-semibold shadow hover:scale-105 transform transition-all duration-300">
                                View Profile
                            </a> --}}
                        </div>
                    @endforeach
                </div>

                <!-- Arrows -->
                <button @click="scrollLeft"
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-[#07004D]/80 rounded-full shadow p-3 hover:scale-110 transition">
                    <svg class="w-6 h-6 text-[#07004D] dark:text-[#F3DFBF]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="scrollRight"
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-[#07004D]/80 rounded-full shadow p-3 hover:scale-110 transition">
                    <svg class="w-6 h-6 text-[#07004D] dark:text-[#F3DFBF]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="text-[#07004D] rounded-2xl py-12 text-center mb-12 shadow-lg"
                style="background: linear-gradient(135deg, #2D82B7, #42E2B8);">
                <h2 class="text-3xl font-bold mb-4">Ready to Start Learning?</h2>
                <p class="mb-6">Join thousands of students and start your journey today.</p>
                @if (!auth()->check())
                    <a href="{{ route('register') }}"
                        class="inline-block px-8 py-3 font-semibold rounded-xl bg-[#07004D] text-[#F3DFBF] shadow hover:scale-105 transform transition-all duration-300">
                        Get Started
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-main-layout>
