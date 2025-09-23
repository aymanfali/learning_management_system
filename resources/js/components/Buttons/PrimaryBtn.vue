<template>
    <button
        class="bg-blue-700 py-2 px-3 my-2 cursor-pointer rounded-md transition-colors">{{
        name }}</button>
</template>

<script setup>
const props = defineProps(['name'])
</script>
