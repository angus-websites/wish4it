<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    lists: Object,
})

</script>

<template>
    <AppLayout title="My Lists">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Lists
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <p v-if="lists.length < 1">No wishlists</p>
                    <ul v-else v-for="l in lists.data">
                        <li>{{l.title}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
