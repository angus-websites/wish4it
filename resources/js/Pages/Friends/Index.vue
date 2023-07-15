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
                    <div class="prose text-gray-200">
                      <ul class="my-5">    
                        <li v-for="friend in friends">
                        {{friend.name}}
                            <ul>
                                <li v-for="wishlist in friend.wishlists">
                                    <Link class="text-blue-200" :href="route('wishlists.show',wishlist.id)">{{wishlist.title}}</Link>
                                </li>
                            </ul>
                        </li>
                      </ul>
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
import AddFriendModal from "@/Components/friends/AddFriendModal.vue";

import { ref } from 'vue'

const props = defineProps({
    friends: Object,
})

let addFriendModalOpen = ref(false)


function handleAddFriendModal(value){
    addFriendModalOpen.value = value
}

function launchAddFriendModal(){
    console.log("LAUNCH")
    addFriendModalOpen.value=true;
}

</script>

