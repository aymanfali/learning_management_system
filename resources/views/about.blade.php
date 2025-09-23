<x-main-layout>
    <div class="py-10 mx-auto">

        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-2xl text-center shadow-xl mb-16"
            style="background: linear-gradient(135deg, #2D82B7, #42E2B8);">
            <div class="py-20 px-6">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
                    About <span class="font-mono">Learn</span>
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

        <!-- Mission Section -->
        <section class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-[#07004D] dark:text-[#F3DFBF] mb-6">Our Mission</h2>
            <p class="max-w-3xl mx-auto text-lg text-[#07004D]/80 dark:text-[#F3DFBF]/80">
                At <strong>Learn</strong>, our mission is to empower learners worldwide by providing accessible,
                high-quality education. We believe everyone should have the opportunity to grow their skills and
                achieve their goals, regardless of background or location.
            </p>
        </section>

        <!-- Features Section -->
        <section class="mb-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-6 bg-white dark:bg-[#07004D] rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-[#07004D] dark:text-[#F3DFBF] mb-2">Flexible Learning</h3>
                <p class="text-[#07004D]/80 dark:text-[#F3DFBF]/80">
                    Learn at your own pace with structured lessons and progress tracking.
                </p>
            </div>
            <div class="p-6 bg-white dark:bg-[#07004D] rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-[#07004D] dark:text-[#F3DFBF] mb-2">Expert Instructors</h3>
                <p class="text-[#07004D]/80 dark:text-[#F3DFBF]/80">
                    Courses are created by experienced professionals and industry experts.
                </p>
            </div>
            <div class="p-6 bg-white dark:bg-[#07004D] rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-[#07004D] dark:text-[#F3DFBF] mb-2">Community Support</h3>
                <p class="text-[#07004D]/80 dark:text-[#F3DFBF]/80">
                    Join a community of learners to share knowledge, ask questions, and grow together.
                </p>
            </div>
        </section>

        <!-- Call to Action -->
        <div class="text-center rounded-2xl py-12 px-6 shadow-lg"
            style="background: linear-gradient(135deg, #2D82B7, #42E2B8);">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Start Learning?</h2>
            <p class="mb-6 text-white/90">Join thousands of students and start your journey today.</p>
            @if (!auth()->check())
                <a href="{{ route('register') }}"
                    class="inline-block px-8 py-3 font-semibold rounded-xl bg-[#07004D] text-[#F3DFBF] shadow hover:scale-105 transform transition-all duration-300">
                    Get Started
                </a>
            @endif
        </div>

    </div>
</x-main-layout>
