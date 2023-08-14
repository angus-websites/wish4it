<template>
  <div>
    <div v-if="addSuccess" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
      <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <span class="sr-only">Info</span>
      <div class="ml-3 text-sm font-medium">
        Friend added succesfully, you can see their wishlists on the <Link :href="route('friends')" class="font-semibold underline hover:no-underline">friends</Link>. page
      </div>

      <button @click="dismiss" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
      </button>
    </div>
    <div v-else class="p-4 border border-gray-300 rounded-lg bg-gray-50 dark:border-gray-600 dark:bg-gray-800" role="alert">
      <div class="flex items-center">
        <svg class="flex-shrink-0 w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-300">Add a new friend</h3>
      </div>
      <div class="mt-2 mb-4 text-sm text-gray-800 dark:text-gray-300">
        <p>Would you like to add <b>{{owner.name}} ({{owner.username}})</b> as a friend</p>
      </div>
      <div class="flex space-x-3">
        <GreenAddButton @click="addFriend" :disabled="addLoading"  type="button">
          <span v-if="addLoading">
            <svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                </svg>
                Adding...
          </span>
          <span v-else>Add as a friend</span>

          </GreenAddButton>
        <SecondaryButton @click="dismiss">Dismiss</SecondaryButton>
      </div>
      <div v-if="addSuccess === false" class="flex items-center p-4 mt-5 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
        <span class="sr-only">Info</span>
        <div>
          <span>Error adding friend, please try again later</span>
        </div>
      </div>

    </div>
  </div>
</template>
<script setup>

import GreenAddButton from '@/Components/buttons/GreenAddButton.vue'
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue'
import axios from 'axios';
import { ref } from 'vue'
import { router} from '@inertiajs/vue3';

const props = defineProps({
    owner: Object
})

const emit = defineEmits(['add-friend', 'dismissed']);

let addLoading = ref(null);
let addLoadingTimeoutId = ref(null);
let addSuccess = ref(null)


// Add friend logic
function addFriend() {

  // Reset
  addSuccess.value = null;

  // Set a timeout to change addLoading after 1 second
  addLoadingTimeoutId.value = setTimeout(() => {
      addLoading.value = true;
  }, 250);  

  // Add using axios
  axios.post(route('friends.add', [props.owner.username] ))
      .then(response => {

          let data = response.data
          addSuccess.value = data.success;

          // Reset (refresh data)
          router.reload();          

      })
      .catch(error => {
        console.error(error);
        addSuccess.value = false;
      }).finally(() => {
        clearTimeout(addLoadingTimeoutId.value);
        addLoading.value=false;
      });

  //emit('add-friend', props.owner);
}

// Dismiss logic
function dismiss() {
  emit('dismissed');
}

</script>