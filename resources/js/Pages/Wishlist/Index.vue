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
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 v-if="lists.length < 1">No wishlists</h2>

                    <div v-else class="flex flex-col gap-y-5">
                        <Link :href="route('wishlists.show', list.id)" v-for="list in lists" class="h2 text-blue-600 dark:text-blue-500 hover:underline">{{list.title}}</Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
