<template>
    <AppLayout title="My Lists">
        <template #header>
            <div class="flex flex-row justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ list.title }} 
                    </h2>
                    <small v-if="$page.props.auth.user.id !== list.owner.id" class="text-sm">{{list.owner.name}}</small>
                </div>

                <PrimaryOutlineButton @click="editList" size="s">Edit List</PrimaryOutlineButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FlashMessages class="mb-5" :hideErrors="true" />

                <!-- Button row-->
                <div class="flex flex-row justify-between items-center mx-3 sm:mx-0 my-5">
                    <!-- Breadcrumb -->
                    <nav class="flex" aria-label="Breadcrumb">
                      <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                          <div class="flex items-center">
                            <Link :href="route('wishlists.index')" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Wishlists</Link>
                          </div>
                        </li>
                        <li aria-current="page">
                          <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ list.title }}</span>
                          </div>
                        </li>
                      </ol>
                    </nav>
                    <PrimaryButton @click="createNewItem">New item</PrimaryButton>
                </div>
                <WishlistGrid :items="list.items" @edit="editItem" @delete="deleteItem"/>
            </div>
        </div>

        <NewItemModal :wishlistId="list.id" :open="newModalOpen" :itemToEdit="itemToEdit" @update:open="handleModal" />
        <DeleteModal :wishlistId="list.id" :open="deleteModalOpen" :itemToDelete="itemToDelete" @update:open="handleDeleteModal" />
        <EditWishlistModal :wishlist="list" :open="editListModalOpen" @update:open="handleEditListModal"  />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue'
import PrimaryOutlineButton from '@/Components/buttons/PrimaryOutlineButton.vue'

import NewItemModal from '@/Components/wishlist/NewItemModal.vue'
import DeleteModal from '@/Components/wishlist/DeleteModal.vue'
import EditWishlistModal from '@/Components/wishlist/EditWishlistModal.vue'

import FlashMessages from '@/Components/FlashMessages.vue'

import { ref } from 'vue'

let newModalOpen = ref(false)
let deleteModalOpen = ref(false)
let editListModalOpen = ref(false)

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

function handleEditListModal(value){
    editListModalOpen.value = value
}

function editItem(item) {
  itemToEdit.value = item;
  newModalOpen.value = true;
}

function deleteItem(item) {
    itemToDelete.value = item;
    deleteModalOpen.value = true;
}

function editList()
{
    editListModalOpen.value = true;
}


</script>
