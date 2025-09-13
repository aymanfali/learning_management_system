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

                    <form action="{{route('courses.store')}}" method="post" class=" space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        
                        <div class="w-full md:flex gap-8">
                            <div class="w-full">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Course Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                        required autofocus autocomplete="name" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea name="description" id="description" rows="11" maxlength="2000"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  dark:focus:border-indigo-600 focus:ring-indigo-500   dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                                <div class="flex justify-end gap-4 my-5">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                                </div>
                            </div>
                            <div class="">
                                <div class="mt-4">
                                    <x-input-label for="image" :value="__('image')" />
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700  dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500  dark:focus:border-indigo-600 focus:ring-indigo-500  dark:focus:ring-indigo-600 rounded-md shadow-sm">

                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')
