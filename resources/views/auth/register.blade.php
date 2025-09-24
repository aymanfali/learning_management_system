<x-guest-layout>
    <div class="flex flex-col items-center">
        <!-- Register Card -->
        <div class="w-full max-w-md m-4 px-6">
            <div class="w-full rounded-3xl shadow-xl p-8">

                <!-- Logo / Header -->
                <div class="text-center mb-6">
                    <h1 class="font-bold text-3xl mx-auto text-[#2D82B7] dark:text-[#42E2B8]">Register</h1>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-xl bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 shadow">
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" required
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" v-model="role"
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-[#07004D] dark:text-[#F3DFBF]
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40">
                            <option value="student" @selected(old('role') === 'student')>Student</option>
                            <option value="instructor" @selected(old('role') === 'instructor')>Instructor</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Extra Fields (Instructor) -->
                    <div v-if="role === 'instructor'" class="space-y-4 mt-4">
                        <!-- Bio -->
                        <div>
                            <x-input-label for="bio" :value="__('Bio')" />
                            <textarea name="bio" id="bio" rows="6"
                                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-[#07004D] dark:text-[#F3DFBF]
                                       focus:border-[#42E2B8] focus:ring-[#42E2B8]/40">{{ old('bio') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- CV -->
                        <div>
                            <x-input-label for="cv" :value="__('Your Resume')" />
                            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx"
                                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-[#07004D] dark:text-[#F3DFBF]
                                       focus:border-[#42E2B8] focus:ring-[#42E2B8]/40" />
                            <x-input-error :messages="$errors->get('cv')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col md:flex-row items-center justify-between mt-4 gap-3">
                        <a href="{{ route('login') }}"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-[#42E2B8] dark:hover:text-[#F3DFBF] underline">
                            {{ __('Already registered? Login') }}
                        </a>
                        <x-primary-button
                            class="w-full md:w-auto py-3 px-6 text-lg font-semibold rounded-xl
                                   bg-gradient-to-r from-[#2D82B7] to-[#42E2B8]
                                   hover:from-[#42E2B8] hover:to-[#2D82B7] text-white shadow-md transition-all duration-300">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
