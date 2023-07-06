<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    list: Object,
    can: Object
})

</script>

<template>
    <AppLayout title="My Lists">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ list.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <p>Content here </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
