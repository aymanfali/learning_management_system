<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            {{ __('Grade Assignment') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto px-4">

        {{-- Toast Notification --}}
        @if (session('success'))
            <x-notification type="success" :message="session('success')" :duration="5000" />
        @endif

        <div class="flex flex-col md:flex-row gap-6 mt-6">

            {{-- Grading Form --}}
            <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 flex-1">
                <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-4">Grade Assignment</h3>

                <form action="{{ route('dashboard.assignments.update', $assignment->id) }}" method="POST"
                    class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Grade (0-100)</label>
                        <input type="number" name="grade" value="{{ old('grade', $assignment->grade) }}"
                            min="0" max="100" step="1" required
                            class="border rounded p-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('grade')
                            <span class="text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Feedback</label>
                        <textarea name="feedback" rows="6" placeholder="Provide constructive feedback"
                            class="border rounded p-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('feedback', $assignment->feedback) }}</textarea>
                        @error('feedback')
                            <span class="text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors duration-200 flex items-center">
                        Submit Grade
                    </button>
                </form>
            </div>

            {{-- Assignment Details --}}
            <div class="bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-6 flex-1">
                <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-4">Assignment Details</h3>

                <div class="space-y-2 mb-4">
                    <p><strong class="text-gray-700 dark:text-gray-300">Student:</strong> <span
                            class="text-gray-900 dark:text-gray-100">{{ $assignment->student->name }}</span></p>
                    <p><strong class="text-gray-700 dark:text-gray-300">Lesson:</strong> <span
                            class="text-gray-900 dark:text-gray-100">{{ $assignment->lesson->title }}</span></p>
                    <p><strong class="text-gray-700 dark:text-gray-300">Course:</strong> <span
                            class="text-gray-900 dark:text-gray-100">{{ $assignment->lesson->course->name }}</span></p>
                </div>

                <div>
                    <h4 class="font-medium mb-2 text-gray-700 dark:text-gray-300">Assignment File</h4>
                    @if ($assignment->assignment_file)
                        <a href="{{ asset('storage/' . $assignment->assignment_file) }}" download
                            class="text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
                            Download File
                        </a>
                    @else
                        <span class="text-red-600 dark:text-red-400 font-semibold">No file submitted</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
