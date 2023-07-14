<script setup>
defineProps({
    value: String,
    rightText: {
        default: null
    }
});
</script>

<template>
    <div class="flex justify-between">
        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            <span v-if="value">{{ value }}</span>
            <span v-else><slot /></span>
        </label>
        <span v-if="rightText" class="text-sm leading-6 text-gray-500">{{rightText}}</span>
    </div>
</template>
