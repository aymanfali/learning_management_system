<script setup>
import CancelBtn from './Buttons/CancelBtn.vue';
import DangerBtn from './Buttons/DangerBtn.vue';

defineProps({
    show: { type: Boolean, required: true },
    title: { type: String, required: true },
    message: { type: String, required: true },
    confirmText: { type: String, default: 'Confirm' },
    cancelText: { type: String, default: 'Cancel' },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">
        <div style="padding: 24px;" class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-8 relative border border-gray-200 dark:border-gray-800">
            <div class="flex flex-col items-center">
                <div class="bg-red-100 dark:bg-red-900 rounded-full p-3 mb-4">
                    <span class="text-4xl px-4 py-2">warning</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">{{ title }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6 text-center">{{ message }}</p>
            </div>
            <div class="flex justify-end space-x-3 mt-2">
                <CancelBtn @click="$emit('cancel')" :name="cancelText" class="px-4 py-2 rounded-md" />
                <DangerBtn @click="$emit('confirm')" class="px-4 py-2 rounded-md">{{ confirmText }}</DangerBtn>
            </div>
        </div>
    </div>
</template>
