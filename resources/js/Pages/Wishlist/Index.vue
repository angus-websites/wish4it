<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlashMessages from '@/Components/FlashMessages.vue'

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

                    <FlashMessages class="mb-5" />

                    <h2 v-if="lists.length < 1">No wishlists</h2>

                    <div v-else class="flex flex-col gap-y-5">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Wishlist name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Items
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Visibility
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="list in lists" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <Link :href="route('wishlists.show', list.id)">{{list.title}}</Link>
                                        </th>
                                        <td class="px-6 py-4">
                                            {{list.itemCount}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="list.public">Public</span><span v-else>Private</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
