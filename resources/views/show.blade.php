<x-main-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Go Back Link -->
            <a href="{{ url('/') }}" class="text-gray-900 dark:text-gray-100 mb-4 inline-block">
                &lt;&lt; Go Back
            </a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Course Image -->
                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('learning_logo.png') }}"
                    alt="Course image for {{ $course->name }}" class="w-full h-64 object-cover rounded-lg mb-6"
                    loading="lazy">

                <!-- Course Info -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $course->name }}
                </h1>

                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
                    {{ $course->description }}
                </p>

                <!-- Author & Date -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('learning_logo.png') }}"
                            alt="Instructor {{ $course->user->name }}" class="w-10 h-10 rounded-full" loading="lazy">
                        <span class="text-gray-800 dark:text-gray-200">{{ $course->user->name }}</span>
                    </div>
                    <span class="text-sm text-gray-500">
                        {{ $course->created_at->format('M d, Y') }}
                    </span>
                </div>

                <!-- Enroll Button / Enrolled Badge -->
                @if (auth()->check() && auth()->user()->role === 'student')
                    @if (!$enrolled)
                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mb-6">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
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
                            $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                        @endphp

                        <div class="mb-6">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Course
                                    Progress</span>
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $progressPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                <div class="bg-indigo-600 h-4 rounded-full transition-all duration-500"
                                    style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Lessons Section -->
                @if (auth()->check() && auth()->user()->role === 'student' && $enrolled)
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Lessons</h2>

                    <div class="space-y-2" x-data="{ openLesson: null }">
                        @forelse($course->lessons as $index => $lesson)
                            @php
                                $submitted = $lesson
                                    ->assignment()
                                    ->where('user_id', auth()->id())
                                    ->first();
                                $lessonProgress = $submitted ? 100 : 0; // Mini progress for this lesson
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">

                                <!-- Lesson Accordion Header -->
                                <button type="button"
                                    class="w-full px-4 py-3 text-left bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex justify-between items-center focus:outline-none"
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
                                <div x-show="openLesson === {{ $index }}" x-collapse
                                    class="px-4 py-3 text-gray-700 dark:text-gray-300 transition-all duration-300">

                                    {{ $lesson->content }}

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
                                        <p class="text-green-600 dark:text-green-400 mt-2">Assignment submitted.</p>
                                        <a href="{{ asset('storage/' . $submitted->assignment_file) }}" target="_blank"
                                            class="text-blue-600 underline">View Submission</a>

                                        @if (!is_null($submitted->grade))
                                            <div class="mt-2 p-2 bg-green-50 dark:bg-green-900 rounded">
                                                <p>
                                                    <span class="font-semibold">Grade:</span> {{ $submitted->grade }}%
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
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
