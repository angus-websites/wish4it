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
                    
                    <p>Friends: </p>
                    <div class="prose text-gray-200">
                      <ul class="my-5">    
                        <li v-for="friend in friends">{{friend.name}}</li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    friends: Object,
})
</script>

