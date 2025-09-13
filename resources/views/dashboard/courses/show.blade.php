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

                    <div class=" space-y-6">
                        <div class="w-full md:flex gap-8">
                            <div class="w-full">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Course Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                        :value="old('name', $course->name)" readonly required autofocus autocomplete="name" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea name="description" id="description" rows="11" maxlength="2000" readonly
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  dark:focus:border-indigo-600 focus:ring-indigo-500   dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $course->description) }} </textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>
                            <div class="">
                                <div class="mt-4 w-60">
                                    
                                    @if ($course->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $course->image) }}" alt="Profile Image"
                                                class="w-24 h-24 rounded-full object-cover">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')
