<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PageContainer from "@/Components/_util/PageContainer.vue";
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue";

import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'
import Pagination from "@/Components/Pagination.vue";

defineProps({
    list: Object,
    items: Object,
});
</script>

<template>
    <GuestLayout :title="list.title">
        <PageContainer>

            <div class="flex flex-row flex-wrap justify-between items-end">
                <div class="overflow-hidden">
                    <h1 class="h1 break-words">{{list.title}}</h1>
                    <p class="h4 mt-2">{{list.owner.name}}</p>
                </div>
                <div>
                    <p>{{items.data.length}} items</p>
                </div>
            </div>

            <div class="my-5 flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
              <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
              </svg>
              <span class="sr-only">Info</span>
              <div>
                In order to mark items as purchased, please login or register for an account
              </div>
            </div>

            <WishlistGrid class="mt-10" :items="items.data"/>

            <div class="py-8">
              <Pagination :model="items" />
            </div>

        </PageContainer>
    </GuestLayout>
</template>

