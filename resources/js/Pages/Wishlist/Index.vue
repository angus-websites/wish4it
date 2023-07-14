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

                    <!-- Button row -->
                    <div class="flex flex-row justify-end items-center mx-3 sm:mx-0 my-5">
                        <PrimaryButton @click="createNewList">New List</PrimaryButton>
                    </div>
                    <h2 v-if="lists.length < 1">No wishlists</h2>

                    <WishlistList v-else :lists="lists"/>

                    <EditWishlistModal :open="newListModalOpen" @update:open="handleNewListModal" />
                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlashMessages from '@/Components/FlashMessages.vue'
import WishlistList from '@/Components/wishlist/WishlistList.vue'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import EditWishlistModal from '@/Components/wishlist/EditWishlistModal.vue'
import { ref } from 'vue'

const props = defineProps({
    lists: Object,
})

let newListModalOpen = ref(false)

function handleNewListModal(value){
    newListModalOpen.value = value
}

function createNewList()
{
    newListModalOpen.value = true;
}

</script>

