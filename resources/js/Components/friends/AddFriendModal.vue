<template>
  <TransitionRoot as="template" :show="isOpen">
    <Dialog as="div" class="relative z-10" @close="closeModal">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white dark:bg-dark px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <DialogTitle class="text-center mb-2">
              <div>
                <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">Add friends</p>
                <p class="text-sm  text-gray-600 dark:text-gray-300">Enter their username below to add friends</p>
              </div>
            </DialogTitle>

            <!-- Form -->
            <form @submit.prevent="submitForm">
              <!-- Form elements -->
              <div class="flex flex-col gap-y-5">

                <!-- Wishlist name -->
                <div>
                    <InputLabel for="username" value="Username" />
                    <TextInput
                        v-model="form.username"
                        @input="onInput"
                        id="username"
                        type="text"
                        class="w-full mt-1"
                        required
                        autocomplete="off"
                        autofocus
                    />
                    <InputError v-if="form.errors.username" :message="form.errors.username" class="mt-1"/>
                </div>

                <!-- Spinner -->
                <Spinner v-if="isLoading" role="status" class="mx-auto" />

                <!-- General error-->
                <div v-if="generalError" class="flex flex-col gap-y-3">
                  <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-bold">Error</span> An error occoured on the server
                  </div>
                </div>

                <!-- Success -->
                <div v-if="isSuccess === true" class="flex flex-col gap-y-3">
                  

                  <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-bold">User found!</span> Click the button below to add them to your friends
                  </div>

                  <!-- Error message adding friend -->
                  <div v-if="addSuccess === false">
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                      <span class="font-bold">Error</span> A problem occoured when adding friend
                    </div>
                  </div>

                  
                  <button @click="addFriend" type="button" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-bold rounded-lg text-sm px-5 py-2.5 text-center mr-2">

                    <span v-if="addLoading">
                      <svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                          <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                          </svg>
                          Adding...
                    </span>
                    <span v-else>Add "{{friendToAdd.username}}" as a friend</span>

                    </button>
                </div>

                <!-- Unsuccessful -->
                <div v-if="isSuccess === false" class="flex flex-col gap-y-3">
                  <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-bold">{{searchErrorTitle}}:</span> {{ searchErrorMessage }}
                  </div>
                </div>



              <!-- Buttons -->
              <div class="flex flex-col gap-y-3 sm:flex-row gap-x-3">
                <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
              </div>
            </div>
            </form>

            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watchEffect, watch, onBeforeUnmount} from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import Spinner from "@/Components/Spinner.vue"

import InputLabel from "@/Components/form/InputLabel.vue"
import TextInput from "@/Components/form/TextInput.vue"
import InputError from "@/Components/form/InputError.vue"

import {useForm, router} from '@inertiajs/vue3';
import { debounce } from 'lodash';
import axios from 'axios';

const props = defineProps({
    open: Boolean,
})


let isOpen = ref(props.open)

// Loading indicators
let isLoading = ref(false)
let searchLoadingTimeoutId = ref(null);

let addLoadingTimeoutId = ref(null);
let addLoading = ref(null);

// Error metadata
let searchErrorTitle = ref(null);
let searchErrorMessage = ref(null);

let addErrorMessage = ref(null);

// Status
let isSuccess = ref(null)
let generalError = ref(null)
let addSuccess = ref(null)

// Friend to add
let friendToAdd = ref(null)

const emit = defineEmits(['update:open'])

const form = useForm({
  username: null,
})


watchEffect(() => {
    isOpen.value = props.open
})

function reset(){
  /**
   * When adding is complete, reset everything
   */
  searchErrorTitle.value = null;
  searchErrorMessage.value = null;
  addErrorMessage.value = null;
  isSuccess.value = null;
  generalError.value = null;
  addSuccess.value = null;
  form.username = "";
}


function closeModal() {
  emit('update:open', false)
  setTimeout(reset, 500);
}


function addFriend(){

  // Reset
  addSuccess.value = null;

  // Set a timeout to change addLoading after 1 second
  addLoadingTimeoutId.value = setTimeout(() => {
      addLoading.value = true;
  }, 250);  

  // Add using axios
  axios.post(route('friends.add', [friendToAdd.value.username] ))
      .then(response => {

          let data = response.data
          addSuccess.value = data.success;

          // Reset (refresh data)
          router.reload();
          closeModal();
          

      })
      .catch(error => {
        console.error(error);
          addSuccess.value = false;
      }).finally(() => {
        clearTimeout(addLoadingTimeoutId.value);
        addLoading.value=false;
      });
}


const onInput = debounce(async (event) => {

    // Reset (always run)
    isSuccess.value = null;
    generalError.value = null;

    // Reset add vars
    addSuccess.value = null;

    // Set a timeout to change isLoading after 1 second
    searchLoadingTimeoutId.value = setTimeout(() => {

        isLoading.value = true;

    }, 1000);  

    axios.post(route('friends.search'), { query: form.username })
        .then((response) => {
          //console.log(response.data); // this will be your user data

          let data = response.data
          isSuccess.value = data["success"]

          // If username not found
          if (!isSuccess.value){
            searchErrorTitle.value = data["errorTitle"]
            searchErrorMessage.value = data["message"]
          }
          // Username is found
          else{
            friendToAdd.value = data["friend"];
            console.log(data["friend"])
          }

        })
        .catch((error) => {
          console.error(error);
          generalError.value=true;
        })
        .finally(() => {
          // Clear the timeout and set isLoading back to false
          clearTimeout(searchLoadingTimeoutId.value);
          isLoading.value = false;
        });
    
  }, 500); 


</script>
