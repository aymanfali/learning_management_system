<x-main-layout>
    <div class="py-10">
        <div class="mx-auto px-6">

            <!-- Hero Section -->
            <div class="relative mb-12">
                <!-- Soft Gradient Background -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-[#2D82B7] via-[#42E2B8] to-[#F3DFBF] opacity-90 rounded-3xl shadow-xl">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start p-8 md:p-12 gap-8">

                    <!-- Course Image -->
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('learning_logo.png') }}"
                            alt="{{ $course->name }}"
                            class="w-full h-56 md:h-64 object-cover rounded-2xl shadow-lg border-4 border-white dark:border-[#07004D]">
                    </div>

                    <!-- Course Info -->
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white drop-shadow mb-4">
                            {{ $course->name }}
                        </h1>
                        <p class="text-lg text-white leading-relaxed  text-justify">
                            {{ $course->description }}
                        </p>

                        <!-- Enroll Button / Enrolled Badge -->
                        @if (auth()->check() && auth()->user()->role === 'student')
                            @if (!$enrolled)
                                <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mb-6">
                                    @csrf
                                    <button type="submit"
                                        class="px-8 py-3 font-semibold rounded-xl bg-white text-[#2D82B7] shadow-lg hover:bg-[#F3DFBF] hover:text-[#07004D] transition-all">
                                        Enroll Now
                                    </button>
                                </form>
                            @else
                                <span
                                    class="inline-block mb-6 px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full shadow">
                                    Enrolled
                                </span>

                                <!-- Course Progress Bar -->
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
                                    $progressPercent =
                                        $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                                @endphp

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
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Author & Date -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('learning_logo.png') }}"
                        alt="Instructor {{ $course->user->name }}"
                        class="w-10 h-10 rounded-full border border-[#42E2B8] shadow" loading="lazy">
                    <span class="text-gray-800 dark:text-gray-200">{{ $course->user->name }}</span>
                </div>
                <span class="text-sm text-[#07004D]/70 dark:text-[#F3DFBF]/70">
                    {{ $course->created_at->format('M d, Y') }}
                </span>
            </div>



            <!-- Lessons Section -->
            @if (auth()->check() && auth()->user()->role === 'student' && $enrolled)
                <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">Lessons</h2>

                <div class="space-y-6" x-data="{ openLesson: null }">
                    @forelse($course->lessons as $index => $lesson)
                        @php
                            $submitted = $lesson
                                ->assignment()
                                ->where('user_id', auth()->id())
                                ->first();
                            $lessonProgress = $submitted ? 100 : 0; // Mini progress for this lesson
                        @endphp
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">

                            <!-- Lesson Accordion Header -->
                            <button type="button"
                                class="w-full px-5 py-4 flex justify-between items-center text-left font-semibold text-[#07004D] dark:text-[#F3DFBF]"
                                @click="openLesson === {{ $index }} ? openLesson = null : openLesson = {{ $index }}"
                                :aria-expanded="openLesson === {{ $index }}">
                                <span class="font-medium">{{ $lesson->title }}</span>
                                <svg x-bind:class="{ 'rotate-180': openLesson === {{ $index }} }"
                                    class="w-5 h-5 transition-transform duration-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Lesson Content -->
                            <div x-show="openLesson === {{ $index }}" x-collapse>
                                <div
                                    class="px-5 py-4 border-t border-gray-200 dark:border-gray-600 text-sm text-[#07004D]/80 dark:text-[#F3DFBF]/80 text-justify">
                                    {!! nl2br(e($lesson->content)) !!}
                                    <!-- Lesson Document -->
                                    @if ($lesson->file)
                                        <div class="mt-3 p-3 bg-gray-100 dark:bg-gray-800 rounded">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                                                Related
                                                Files:</p>
                                            <a href="{{ asset('storage/' . $lesson->file) }}" target="_blank"
                                                class="text-blue-600 dark:text-blue-400 underline">
                                                View File
                                            </a>
                                        </div>
                                    @endif
                                    <!-- Lesson Mini Progress Bar -->
                                    <div class="mb-3 mt-2">
                                        <div class="flex justify-between mb-1">
                                            <span
                                                class="text-xs font-medium text-gray-700 dark:text-gray-300">Completion</span>
                                            <span
                                                class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $lessonProgress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                                style="width: {{ $lessonProgress }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Assignment Upload -->
                                    @if ($submitted)
                                        <p class="text-green-600 dark:text-green-400 mt-2">Assignment submitted.
                                        </p>
                                        <a href="{{ asset('storage/' . $submitted->assignment_file) }}" target="_blank"
                                            class="text-blue-600 underline">View Submission</a>

                                        @if (!is_null($submitted->grade))
                                            <div class="mt-2 p-2 bg-green-50 dark:bg-green-900 rounded">
                                                <p>
                                                    <span class="font-semibold">Grade:</span>
                                                    {{ $submitted->grade }}%
                                                </p>
                                                @if ($submitted->feedback)
                                                    <p>
                                                        <span class="font-semibold">Feedback:</span>
                                                        {{ $submitted->feedback }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <form action="{{ route('assignments.store') }}" method="POST"
                                            enctype="multipart/form-data" class="space-y-3 mt-3">
                                            @csrf
                                            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                    Upload Assignment
                                                </label>
                                                <input type="file" name="assignment_file"
                                                    accept=".pdf,.doc,.docx,.zip"
                                                    class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 border rounded-lg cursor-pointer focus:outline-none"
                                                    required>
                                            </div>
                                            <button type="submit"
                                                class="px-6 py-2 font-semibold rounded-xl bg-gradient-to-r from-[#42E2B8] to-[#F3DFBF] text-[#07004D] shadow hover:scale-105 transform transition-all duration-300">
                                                Submit Assignment
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-300 mt-2">No lessons added yet.</p>
                    @endforelse
                </div>
            @elseif(auth()->check() && auth()->user()->role === 'student')
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    You need to enroll to access the lessons.
                </p>
            @endif

        </div>
    </div>
    </div>
</x-main-layout>
