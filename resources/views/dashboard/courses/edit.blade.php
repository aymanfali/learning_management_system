<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} / {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('courses.update', $course) }}" method="post" class="space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="w-full md:flex gap-8">
                            {{-- Left Section --}}
                            <div class="w-full">
                                {{-- Course Name --}}
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Course Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                        :value="old('name', $course->name)" required autofocus autocomplete="name" />
                                </div>

                                {{-- Description --}}
                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea name="description" id="description" rows="6" maxlength="2000"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $course->description) }}</textarea>
                                </div>

                                {{-- Lessons --}}
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold mb-3">Lessons</h3>
                                    <div id="lessons-wrapper" class="space-y-4">
                                        @foreach ($course->lessons as $index => $lesson)
                                            <div
                                                class="lesson-item border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm p-4 relative bg-gray-50 dark:bg-gray-900 transition-all duration-200">
                                                <div
                                                    class="flex justify-between items-center cursor-pointer toggle-lesson">
                                                    <h4 class="font-semibold">Lesson #{{ $index + 1 }}</h4>
                                                    <span class="text-sm text-gray-500">▼</span>
                                                </div>

                                                <div class="lesson-body mt-3 space-y-3">
                                                    <div>
                                                        <x-input-label :value="'Lesson Title'" />
                                                        <x-text-input type="text"
                                                            name="lessons[{{ $index }}][title]"
                                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                            :value="old('lessons.$index.title', $lesson->title)"
                                                            required />
                                                    </div>

                                                    <div>
                                                        <x-input-label :value="'Lesson Content'" />
                                                        <textarea name="lessons[{{ $index }}][content]" rows="3"
                                                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old("lessons.$index.content", $lesson->content) }}</textarea>
                                                    </div>

                                                    <div>
                                                        <x-input-label :value="'Lesson File (optional)'" />
                                                        <input type="file" name="lessons[{{ $index }}][file]"
                                                            class="block mt-1 w-full border-gray-300 dark:border-gray-700  
                                               dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  
                                               dark:focus:border-indigo-600 focus:ring-indigo-500  
                                               dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                        @if ($lesson->file)
                                                            <div class="mt-2">
                                                                <a href="{{ asset('storage/' . $lesson->file) }}"
                                                                    target="_blank" class="text-blue-600 underline">
                                                                    View current file
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <button type="button"
                                                    class="remove-lesson absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Add Lesson Button --}}
                                    <button type="button" id="add-lesson"
                                        class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        + Add Lesson
                                    </button>
                                </div>

                                {{-- Submit --}}
                                <div class="flex justify-end gap-4 my-5">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                                </div>
                            </div>

                            {{-- Right Section (Course Image) --}}
                            <div>
                                <x-input-label for="image" :value="__('Course Image')" />
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @if ($course->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                                            class="w-24 h-24 rounded-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lessonIndex = {{ $course->lessons->count() }};
        const lessonsWrapper = document.getElementById("lessons-wrapper");
        const addLessonBtn = document.getElementById("add-lesson");

        addLessonBtn.addEventListener("click", () => {
            const lessonDiv = document.createElement("div");
            lessonDiv.classList.add("lesson-item", "border", "border-gray-300", "dark:border-gray-700",
                "rounded-lg", "shadow-sm", "p-4", "relative", "bg-gray-50", "dark:bg-gray-900",
                "transition-all", "duration-200");

            lessonDiv.innerHTML = `
            <div class="flex justify-between items-center cursor-pointer toggle-lesson">
                <h4 class="font-semibold">Lesson #${lessonIndex + 1}</h4>
                <span class="text-sm text-gray-500">▼</span>
            </div>

            <div class="lesson-body mt-3 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lesson Title</label>
                    <input type="text" name="lessons[${lessonIndex}][title]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lesson Content</label>
                    <textarea name="lessons[${lessonIndex}][content]" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lesson File (optional)</label>
                    <input type="file" name="lessons[${lessonIndex}][file]" class="block mt-1 w-full border-gray-300 dark:border-gray-700  
                                               dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  
                                               dark:focus:border-indigo-600 focus:ring-indigo-500  
                                               dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>
            </div>

            <button type="button" class="remove-lesson absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">✕</button>
        `;

            lessonsWrapper.appendChild(lessonDiv);
            lessonIndex++;
        });

        lessonsWrapper.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-lesson")) {
                if (confirm("Are you sure you want to remove this lesson?")) {
                    e.target.closest(".lesson-item").remove();
                }
            }
            if (e.target.closest(".toggle-lesson")) {
                const body = e.target.closest(".lesson-item").querySelector(".lesson-body");
                body.classList.toggle("hidden");
            }
        });
    });
</script>
