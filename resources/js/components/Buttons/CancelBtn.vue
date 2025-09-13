<template>
    <button
        class="py-2 px-3 m-2 cursor-pointer border border-text-main text-text-main rounded-md">{{
        name }}</button>
</template>

<script setup>
const props = defineProps(['name'])
</script>
