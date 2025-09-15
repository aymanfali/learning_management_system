<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} / {{ __('Courses') }} / {{ $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-8">

                    {{-- Course Info Card --}}
                    <h3 class="text-xl font-bold">Course Information</h3>
                    <div
                        class="border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm p-6 bg-gray-50 dark:bg-gray-900">
                        <div class="flex flex-col md:flex-row gap-8">

                            {{-- Left Section --}}
                            <div class="flex-1 space-y-4">
                                {{-- Course Name --}}
                                <div>
                            <h3 class="text-xl font-bocld mb-4">Course Name</h3>
                                    <h3 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $course->name }}
                                    </h3>
                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-4 flex justify-between">
                                    <div class="flex items-center gap-5">
                                        @if ($course->user && $course->user->image)
                                            <img src="{{ asset('storage/' . $course->user->image) }}"
                                                alt="{{ $course->user->name }}"
                                                class="w-10 h-10 rounded-full object-cover shadow">
                                        @else
                                            {{-- Fallback Avatar --}}
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                                <span
                                                    class="text-sm font-bold">{{ strtoupper(substr($course->user->name ?? 'U', 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <p><span class="font-medium">{{ $course->user->name ?? 'Unknown' }}</span></p>
                                    </div>
                                    <p class="font-medium">{{ $course->created_at->format('F j, Y') }}</p>
                                </div>
                                <hr>
                                <div>
                            <h3 class="text-xl font-bocld mb-4">Description</h3>
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                        {{ $course->description }}
                                    </p>
                                </div>


                            </div>

                            {{-- Right Section (Course Image) --}}
                            <div class="flex-shrink-0">
                                @if ($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                                        class="w-48 h-48 rounded-lg object-cover shadow-md">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Lessons --}}
                    @if ($course->lessons->count())
                        <div>
                            <h3 class="text-xl font-bocld mb-4">Lessons</h3>
                            <div class="space-y-4">
                                @foreach ($course->lessons as $index => $lesson)
                                    <div
                                        class="border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm p-4 bg-gray-50 dark:bg-gray-900">
                                        <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400">
                                            Lesson {{ $loop->iteration }}: {{ $lesson->title }}
                                        </h4>
                                        <p class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                            {{ $lesson->content }}
                                        </p>

                                        @if ($lesson->file)
                                            <a href="{{ asset('storage/' . $lesson->file) }}" target="_blank"
                                                class="text-blue-600 dark:text-blue-400 underline mt-2 inline-block">
                                                📎 View Lesson File
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mt-6 text-gray-600 dark:text-gray-400">No lessons available for this course.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')
