@props([
    'type' => 'success', // success, error, warning, info
    'message' => '',
    'duration' => 4000,
])

@php
    switch ($type) {
        case 'success':
            $classes =
                'absolute bottom-6 right-6 z-50 flex items-center max-w-xs w-full px-5 py-3 rounded-xl shadow-xl space-x-3 bg-green-50 dark:bg-green-900 text-green-900 dark:text-green-100 border border-green-200 dark:border-green-700 hover:shadow-2xl transition-all duration-300';
            break;
        case 'error':
            $classes =
                'absolute bottom-6 right-6 z-50 flex items-center max-w-xs w-full px-5 py-3 rounded-xl shadow-xl space-x-3 bg-red-50 dark:bg-red-900 text-red-900 dark:text-red-100 border border-red-200 dark:border-red-700 hover:shadow-2xl transition-all duration-300';
            break;
        case 'warning':
            $classes =
                'absolute bottom-6 right-6 z-50 flex items-center max-w-xs w-full px-5 py-3 rounded-xl shadow-xl space-x-3 bg-yellow-50 dark:bg-yellow-900 text-yellow-900 dark:text-yellow-100 border border-yellow-200 dark:border-yellow-700 hover:shadow-2xl transition-all duration-300';
            break;
        default:
            $classes =
                'absolute bottom-6 right-6 z-50 flex items-center max-w-xs w-full px-5 py-3 rounded-xl shadow-xl space-x-3 bg-blue-50 dark:bg-blue-900 text-blue-900 dark:text-blue-100 border border-blue-200 dark:border-blue-700 hover:shadow-2xl transition-all duration-300';
            break;
    }
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, {{ $duration }})" x-transition:enter="transform transition duration-300"
    x-transition:enter-start="translate-x-10 opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition duration-300" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-10 opacity-0" {{ $attributes->merge(['class' => $classes]) }}>
    {{-- Icon --}}
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        @switch($type)
            @case('success')
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            @break

            @case('error')
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            @break

            @case('warning')
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
            @break

            @default
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
        @endswitch
    </svg>

    {{-- Message --}}
    <span class="flex-1 text-sm font-medium">{{ $message }}</span>

    {{-- Close Button --}}
    <button @click="show = false"
        class="ml-auto text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 font-bold">
        ✕
    </button>
</div>
