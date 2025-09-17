<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- My Enrolled Courses Section -->
            @if (auth()->check() && auth()->user()->enrolledCourses->count())
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">My Enrolled Courses</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
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
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden flex flex-col relative">

                            <!-- Enrolled Badge -->
                            <span
                                class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">
                                Enrolled
                            </span>

                            <!-- Course Image with Completed Overlay -->
                            <div class="relative">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/default-course.jpg') }}"
                                    alt="{{ $course->name }}" class="w-full h-40 object-cover">

                                @if ($isCompleted)
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-t-2xl">
                                        <div class="text-white text-lg font-bold flex items-center space-x-2">
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
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $course->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">
                                        {{ $course->description }}
                                    </p>
                                </div>

                                <!-- Instructor and Date -->
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center space-x-2">
                                        <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('images/default-avatar.png') }}"
                                            class="w-8 h-8 rounded-full">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $course->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $course->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full">
                                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                            style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-700 dark:text-gray-300 mt-1 inline-block">
                                        {{ $progressPercent }}% Complete
                                    </span>
                                </div>

                                <a href="{{ route('course.show', $course->id) }}"
                                    class="mt-4 inline-block px-4 py-2 text-center bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                    Show
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif



            <!-- All Courses Section (exclude enrolled) -->
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">All Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($courses->whereNotIn('id', auth()->check() ? auth()->user()->enrolledCourses->pluck('id') : collect()) as $course)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden flex flex-col">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/default-course.jpg') }}"
                            alt="{{ $course->name }}" class="w-full h-40 object-cover">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $course->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">
                                    {{ $course->description }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('images/default-avatar.png') }}"
                                        class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $course->user->name }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $course->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <a href="{{ route('course.show', $course->id) }}"
                                class="mt-4 inline-block px-4 py-2 text-center bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                Show
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300 col-span-full text-center">
                        No courses available yet.
                    </p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $courses->links() }}
            </div>

        </div>
    </div>
</x-main-layout>
