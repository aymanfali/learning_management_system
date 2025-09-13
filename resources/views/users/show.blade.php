<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} / {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div id="app">
                        <div class="container">
                            <h1>User Details</h1>
                            <!-- User Details (View Only) -->
                                <div class="md:flex w-full gap-8">
                                    <div class="w-full">
                                         <div class="mt-4">
                                            @if ($user->image)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $user->image) }}"
                                                        alt="Profile Image" class="w-24 h-24 rounded-full object-cover">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label for="name" :value="__('Name')" />
                                            <x-text-input id="name" name="name" type="text"
                                                class="mt-1 block w-full" :value="old('name', $user->name)" readonly autocomplete="name" />
                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                        </div>

                                        <div class="mt-4">
                                            <x-input-label for="email" :value="__('Email')" />
                                            <x-text-input id="email" name="email" type="email"
                                                class="mt-1 block w-full" :value="old('email', $user->email)" readonly autocomplete="username" />
                                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                                <div>
                                                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                                        {{ __('Your email address is unverified.') }}

                                                        <button form="send-verification"
                                                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                                            {{ __('Click here to re-send the verification email.') }}
                                                        </button>
                                                    </p>

                                                    @if (session('status') === 'verification-link-sent')
                                                        <p
                                                            class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                                            {{ __('A new verification link has been sent to your email address.') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4">
                                            <x-input-label for="birthdate" :value="__('Birthdate')" />
                                            <x-text-input id="birthdate" name="birthdate" type="date"
                                                class="mt-1 block w-full" :value="old('birthdate', $user->birthdate)" readonly autocomplete="birthdate" />
                                            <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
                                        </div>

                                       
                                    </div>
                                    <div class="w-full">
                                        <!-- Extra Fields (only for Instructor) -->
                                        @if ($user->role === 'instructor')
                                            <div class="m-2 w-full">

                                                <div>
                                                    <x-input-label for="major" :value="__('major')" />
                                                    <x-text-input id="major" name="major" type="text"
                                                        class="mt-1 block w-full" :value="old('major', $user->major)" readonly autocomplete="major" />
                                                    <x-input-error class="mt-2" :messages="$errors->get('major')" />
                                                </div>

                                                <!-- Bio -->
                                                <div class="mt-4">
                                                    <x-input-label for="bio" :value="__('Bio')" />
                                                    <textarea name="bio" id="bio" rows="11" maxlength="2000"
                                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 
                         dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 
                         dark:focus:border-indigo-600 focus:ring-indigo-500 
                         dark:focus:ring-indigo-600 rounded-md shadow-sm" readonly>{{ old('bio', $user->bio) }}</textarea>
                                                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                                                </div>

                                                <!-- CV -->
                                                <div class="mt-4">
                                                   @if ($user->cv)
                                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                            {{ __('Current Resume:') }}
                                                            <a href="{{ asset('storage/' . $user->cv) }}"
                                                                target="_blank"
                                                                class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">
                                                                {{ __('Download CV') }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            <!-- End User Details -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')
