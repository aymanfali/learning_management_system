<x-main-layout>
    <div class="py-10">

        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-2xl text-center shadow-xl mb-16"
            style="background: linear-gradient(135deg, #2D82B7, #42E2B8);">
            <div class="py-20 px-6">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
                    Pick the course you want
                </h1>
                <p class="text-lg sm:text-xl mb-6 max-w-2xl mx-auto text-white/90">
                    Discover new skills, track your progress, and complete courses at your own pace.
                </p>
                <a href="#courses"
                    class="inline-block px-8 py-3 font-semibold rounded-xl bg-gradient-to-r from-[#42E2B8] to-[#F3DFBF] text-[#07004D] shadow-lg hover:scale-105 transform transition-all duration-300">
                    Start Now!
                </a>
            </div>
        </div>

        <!-- All Courses Section -->
        <h2 class="text-2xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">All Courses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 ">
            @forelse($courses as $course)
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
</x-main-layout>
