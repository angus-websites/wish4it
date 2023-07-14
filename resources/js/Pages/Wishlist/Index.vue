<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlashMessages from '@/Components/FlashMessages.vue'
import WishlistList from '@/Components/wishlist/WishlistList.vue'

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
                <div class="overflow-hidden sm:rounded-lg">

                    <FlashMessages class="mb-5" />

                    <h2 v-if="lists.length < 1">No wishlists</h2>

                    <WishlistList v-else :lists="lists"/>
                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>
