<x-main-layout>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="text-gray-900 dark:text-gray-100 m-3">
                << Go Back</a>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                        <!-- Course Image -->
                        <img src="{{ asset('storage/' . $course->image) ?? asset('favicon.icon') }}"
                            alt="{{ $course->name }}" class="w-full h-64 object-cover rounded-lg mb-6" loading="lazy">

                        <!-- Course Info -->
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $course->name }}
                        </h1>

                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
                            {{ $course->description }}
                        </p>

                        <!-- Author & Date -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('storage/' . $course->user->image) ?? asset('images/default-avatar.png') }}"
                                    alt="{{ $course->user->name }}" class="w-10 h-10 rounded-full" loading="lazy">
                                <span class="text-gray-800 dark:text-gray-200">{{ $course->user->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500">
                                {{ $course->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <!-- Enroll Button -->
                       
                        @if (auth()->check() && auth()->user()->role === 'student' && !$enrolled)
                            <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mb-8">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                    Enroll Now
                                </button>
                            </form>
                        @endif


                        <!-- Lessons -->
                        
                        @if (auth()->check() && auth()->user()->role === 'student' && $enrolled)

                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Lessons</h2>

                            <div class="space-y-2" x-data="{ openLesson: null }">
                                @foreach ($course->lessons as $index => $lesson)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                        <button type="button"
                                            class="w-full px-4 py-3 text-left bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex justify-between items-center focus:outline-none"
                                            @click="openLesson === {{ $index }} ? openLesson = null : openLesson = {{ $index }}">
                                            <span class="font-medium">{{ $lesson->title }}</span>
                                            <svg x-bind:class="{ 'rotate-180': openLesson === {{ $index }} }"
                                                class="w-5 h-5 transition-transform duration-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div x-show="openLesson === {{ $index }}" x-collapse
                                            class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                            {{ $lesson->content }}
                                        </div>
                                    </div>
                                @endforeach

                                @if ($course->lessons->isEmpty())
                                    <p class="text-gray-600 dark:text-gray-300 mt-2">No lessons added yet.</p>
                                @endif
                            </div>
                        @elseif(auth()->check() && auth()->user()->role === 'student')
                            <p class="text-gray-600 dark:text-gray-300 mt-4">You need to enroll to access the lessons.
                            </p>
                        @endif
                    </div>
        </div>
    </div>

</x-main-layout>
