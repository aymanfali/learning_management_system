<x-guest-layout>
    <div class="flex flex-col">
        <!-- Right Login Card -->
        <div class="flex w-full justify-center items-center my-4 px-6">
            <div class="w-full max-w-md rounded-3xl shadow-xl p-8">
                
                <!-- Logo -->
                <div class="text-center mb-6">
                    <h1 class="font-bold text-3xl mx-auto text-[#2D82B7] dark:text-[#42E2B8]" >Login</h1>
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
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email"
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]"
                            :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password"
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600
                                   focus:border-[#42E2B8] focus:ring-[#42E2B8]/40 dark:bg-[#07004D] dark:text-[#F3DFBF]"
                            required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 dark:border-gray-600 text-[#2D82B7] focus:ring-[#42E2B8]"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-[#2D82B7] hover:text-[#42E2B8] dark:text-[#42E2B8] dark:hover:text-[#F3DFBF]">
                                {{ __('Forgot?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <x-primary-button
                        class="w-full py-3 text-lg font-semibold rounded-xl text-center
                               bg-gradient-to-r from-[#2D82B7] to-[#42E2B8]
                               hover:from-[#42E2B8] hover:to-[#2D82B7] text-white shadow-md transition-all duration-300">
                        {{ __('Log in') }}
                    </x-primary-button>

                    <!-- Register -->
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}"
                           class="font-semibold text-[#2D82B7] hover:text-[#42E2B8] dark:text-[#42E2B8] dark:hover:text-[#F3DFBF]">
                            {{ __('Sign up here') }}
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
