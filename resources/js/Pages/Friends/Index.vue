<template>
    <AppLayout title="My Friends">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Friends
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Button row -->
                    <div class="flex flex-row justify-center items-center my-5">
                        <PrimaryButton @click="launchAddFriendModal">Add friend</PrimaryButton>
                    </div>
                    <p>Friends: </p>

                    <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
                        <li v-for="friend in friends" :key="friend.id">
                          <Friend :friend="friend" />
                        </li>
                    </ul>
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

