<template>
    <AppLayout title="My Friends">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Friends
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden mx-5">
                    <FlashMessages class="mb-5" />

                    <!-- Button row -->
                    <div class="flex flex-row justify-center items-center my-5">
                        <PrimaryButton @click="launchAddFriendModal">Add friend</PrimaryButton>
                    </div>
                    <EmptyState v-if="friends.length < 1">
                        <p>You have no friends</p>
                    </EmptyState>
                    
                    <div v-else class="mt-10 grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 md:grid-cols-3 items-start">
                        <Friend v-for="friend in friends" :key="friend.id" :friend="friend" />
                    </div>
                </div>
            </div>
        </div>

        <AddFriendModal :open="addFriendModalOpen" @update:open="handleAddFriendModal" />

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import DangerButton from '@/Components/buttons/DangerButton.vue';
import FlashMessages from '@/Components/FlashMessages.vue'
import EmptyState from '@/Components/EmptyState.vue'

import AddFriendModal from "@/Components/friends/AddFriendModal.vue";
import Friend from "@/Components/friends/Friend.vue";

import { ref } from 'vue'

const props = defineProps({
    friends: Object,
})

let addFriendModalOpen = ref(false)


function handleAddFriendModal(value){
    addFriendModalOpen.value = value
}

function launchAddFriendModal(){
    addFriendModalOpen.value=true;
}

</script>

