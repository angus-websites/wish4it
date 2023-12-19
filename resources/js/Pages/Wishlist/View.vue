<template>
    <AppLayout :title="list.title">
        <template #header>
            <div class="flex flex-col space-y-5 sm:flex-row sm:space-y-0 justify-between items-center overflow-hidden">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight break-words">
                        {{ list.title }}
                    </h2>
                    <small v-if="$page.props.auth.user.id !== list.owner.id" class="text-sm">{{list.owner.name}}</small>
                </div>

                <div class="flex flex-row items-center space-x-3">
                    <SecondaryOutlineButton @click="copyLinkUrl" size="s">Copy link</SecondaryOutlineButton>
                    <PrimaryOutlineButton v-if="can.editList" @click="editList" size="s">Edit List</PrimaryOutlineButton>
                </div>
            </div>
        </template>

        <!-- Clipboard message success -->
        <div v-if="showClipboardSuccessMessage" class="p-4 text-sm text-green-800 dark:text-green-400 text-center" role="alert">
          <span class="font-medium">Success!</span> link to list copied to clipboard
        </div>

        <!-- Clipboard message failure -->
        <div v-if="showClipboardErrorMessage" class="p-4 text-sm text-red-800 dark:text-red-400 text-center" role="alert">
          <span class="font-medium">Failure!</span> failed to copy link to clipboard
        </div>



        <div class="py-5 sm:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <TransitionRoot
                    :show="showAddFriendAlert"
                    enter="transition-opacity duration-75"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity duration-150"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                  >
                    <AddFriendAlert
                      :owner="list.owner"
                      class="mx-5 sm:mx-0"
                      @add-friend="handleAddFriend"
                      @dismissed="handleDismiss"
                    />
                  </TransitionRoot>

                <FlashMessages class="mb-5" :hideErrors="true" />

                <!-- Button row-->
                <div class="flex flex-col space-y-8 sm:flex-row sm:space-y-0 justify-between items-center mx-3 sm:mx-0 my-5">
                    <!-- Breadcrumb -->
                    <nav class="flex" aria-label="Breadcrumb">
                      <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                          <div class="fl#ex items-center">


                            <Link v-if="$page.props.auth.user.id === list.owner.id" :href="route('wishlists.index')" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Wishlists</Link>
                            <p v-else-if="canAddFriend" class="ml-1 text-sm font-medium text-gray-700 md:ml-2 dark:text-gray-400">{{list.owner.username}}</p>
                            <Link v-else :href="route('friends')" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Friends</Link>


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
                    <!-- Number of items (desktop) -->
                    <div class="hidden sm:block">
                      <p class="text-center text-sm"><span class="font-bold" v-if="viewPurchased">{{list.itemCount}}</span><span class="font-bold" v-else>{{ list.unpurchasedItemCount }}</span> items</p>
                    </div>

                    <div class="flex flex-row justify-between items-center space-x-8">
                        <div class="sm:hidden">
                          <p class="text-center text-sm"><span class="font-bold" v-if="viewPurchased">{{list.itemCount}}</span><span class="font-bold" v-else>{{ list.unpurchasedItemCount }}</span> items</p>
                        </div>
                        <!-- View purchased option -->
                        <div v-if="can.viewPurchased">
                            <label class="flex items-center">
                                <Checkbox v-model="viewPurchased" name="remember" />
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Show purchased items</span>
                            </label>
                        </div>

                        <!-- button -->
                        <PrimaryButton v-if="can.createItems" @click="createNewItem">New item</PrimaryButton>
                    </div>
                </div>


                <WishlistGrid :can="list.can" :itemCount="list.itemCount" :unpurchasedItemCount="list.unpurchasedItemCount" :items="items.data" :showPurchased="viewPurchased" @edit="editItem" @delete="deleteItem" @mark="markItem"/>
                <div class="py-8">
                    <Pagination :model="items" />
                </div>
            </div>
        </div>

        <NewItemModal :wishlistId="list.id" :open="newModalOpen" :itemToEdit="itemToEdit" @update:open="handleNewItemModal" />
        <DeleteModal :wishlistId="list.id" :open="deleteModalOpen" :itemToDelete="itemToDelete" @update:open="handleDeleteModal" />
        <EditWishlistModal :wishlist="list" :open="editListModalOpen" @update:open="handleEditListModal" :canDelete="can.deleteList"  />
        <MarkAsPurchasedModal :wishlistId="list.id" :itemToMark="itemToMark" :open="markPurchasedModalOpen" @update:open="handleMarkPurchaseModal"  />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue'
import PrimaryOutlineButton from '@/Components/buttons/PrimaryOutlineButton.vue'
import SecondaryOutlineButton from '@/Components/buttons/SecondaryOutlineButton.vue'

import NewItemModal from '@/Components/wishlist/NewItemModal.vue'
import DeleteModal from '@/Components/wishlist/DeleteModal.vue'
import EditWishlistModal from '@/Components/wishlist/EditWishlistModal.vue'
import MarkAsPurchasedModal from '@/Components/wishlist/MarkAsPurchasedModal.vue'
import AddFriendAlert from '@/Components/friends/AddFriendAlert.vue'

import Checkbox from '@/Components/form/Checkbox.vue'

import FlashMessages from '@/Components/FlashMessages.vue'
import { TransitionRoot } from '@headlessui/vue'
import { ref } from 'vue'
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    list: Object,
    items: Object,
    can: Object,
    canAddFriend: {
        type: Boolean,
        default: false
    }
})


let newModalOpen = ref(false)
let deleteModalOpen = ref(false)
let editListModalOpen = ref(false)
let markPurchasedModalOpen = ref(false)
let viewPurchased = ref(false);

let itemToEdit = ref(null);
let itemToDelete = ref(null);
let itemToMark = ref(null);
let showClipboardSuccessMessage = ref(false);
let showClipboardErrorMessage = ref(false);

let showAddFriendAlert = ref(props.canAddFriend);

function createNewItem()
{
    // Ensure we dont keep the current edit item
    itemToEdit.value = null;
    newModalOpen.value = true;
}

async function copyLinkUrl() {
    try {
        const listUrl = route('wishlists.show', props.list.id);
        await navigator.clipboard.writeText(listUrl);
        console.log("Success")
        showClipboardSuccessMessage.value = true;
        setTimeout(() => {
            showClipboardSuccessMessage.value = false;
        }, 3000);
    } catch (err) {
        console.error('Failed to copy link:', err);
        showClipboardErrorMessage.value = true;
        setTimeout(() => {
            showClipboardErrorMessage.value = false;
        }, 3000);
    }
}



// Modal handlers
function handleNewItemModal(value) {
    newModalOpen.value = value;
}

function handleDeleteModal(value) {
    deleteModalOpen.value = value;
}

function handleEditListModal(value){
    editListModalOpen.value = value
}

function handleMarkPurchaseModal(value){
    markPurchasedModalOpen.value = value
}


function handleAddFriend(owner) {
  // Logic to add the friend.
  // After successfully adding the friend or on error, you can optionally hide the AddFriendAlert.
  showAddFriendAlert.value = false;
}

function handleDismiss() {
  // Logic to dismiss the alert.
  showAddFriendAlert.value = false;
}


function editItem(item) {
  itemToEdit.value = item;
  newModalOpen.value = true;
}

function markItem(item) {
  itemToMark.value = item;
  markPurchasedModalOpen.value = true;
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
