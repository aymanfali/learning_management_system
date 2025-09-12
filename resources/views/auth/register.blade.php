<x-guest-layout>
    <div id="register-app">
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="md:flex gap-8">
                <div class="m-2 w-full">
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" v-model="role"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700  dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  dark:focus:border-indigo-600 focus:ring-indigo-500  dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            name="role">
                            <option value="student" @selected(old('role') === 'student')>Student</option>
                            <option value="instructor" @selected(old('role') === 'instructor')>Instructor</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                </div>

                <!-- Extra Fields (only for Instructor) -->
                <div class="m-2 w-full" v-if="role === 'instructor'">
                    <!-- Bio -->
                    <div class="mt-4">
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea name="bio" id="bio" rows="11"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 
                                 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 
                                 dark:focus:border-indigo-600 focus:ring-indigo-500 
                                 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('bio') }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>

                    <!-- CV -->
                    <div class="mt-4">
                        <x-input-label for="cv" :value="__('Your Resume')" />
                        <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 
                              dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 
                              dark:focus:border-indigo-600 focus:ring-indigo-500 
                              dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('cv')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 
                  dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 
                  focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}">
                    {{ __('Already registered? Login') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
