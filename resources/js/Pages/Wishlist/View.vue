<template>
    <AppLayout title="My Lists">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ list.title }}
            </h2>
        </template>

        <div class="py-12">


            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FlashMessages class="mb-5" />
                <PrimaryButton @click="createNewItem" class="mb-5">New item</PrimaryButton>
                <WishlistGrid :items="list.items" @edit="editItem" @delete="deleteItem"/>
            </div>
        </div>

        <NewItemModal :wishlistId="list.id" :open="newModalOpen" :itemToEdit="itemToEdit" @update:open="handleModal" />
        <DeleteModal :wishlistId="list.id" :open="deleteModalOpen" :itemToDelete="itemToDelete" @update:open="handleDeleteModal" />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue'
import NewItemModal from '@/Components/wishlist/NewItemModal.vue'
import DeleteModal from '@/Components/wishlist/DeleteModal.vue'
import FlashMessages from '@/Components/FlashMessages.vue'

import { ref } from 'vue'

let newModalOpen = ref(false)
let deleteModalOpen = ref(false)
let itemToEdit = ref(null);
let itemToDelete = ref(null);

const props = defineProps({
    list: Object,
    can: Object
})

function createNewItem()
{
    // Ensure we dont keep the current edit item
    itemToEdit.value = null;
    newModalOpen.value = true;
}

function handleModal(value) {
    newModalOpen.value = value;
}

function handleDeleteModal(value) {
    deleteModalOpen.value = value;
}

function editItem(item) {
  itemToEdit.value = item;
  newModalOpen.value = true;
}

function deleteItem(item) {
    itemToDelete.value = item;
    deleteModalOpen.value = true;
}


</script>
