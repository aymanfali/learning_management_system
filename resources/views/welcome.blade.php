<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden flex flex-col">

                        <!-- Course Image -->
                        <img src="{{ $course->image_url ?? asset('images/default-course.jpg') }}"
                            alt="{{ $course->name }}" class="w-full h-40 object-cover">

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

                            <!-- Author and Date -->
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $course->user->image ?? asset('images/default-avatar.png') }}"
                                        class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $course->user->name }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $course->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Enroll Button -->
                            <a href="{{ route('course.show', $course->id) }}"
                                class="mt-4 inline-block px-4 py-2 text-center bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                Show
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300 col-span-full text-center">No courses available
                        yet.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $courses->links() }}
            </div>

        </div>
    </div>

</x-main-layout>
