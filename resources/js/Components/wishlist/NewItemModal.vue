<template>
  <TransitionRoot as="template" :show="isOpen">
    <Dialog as="div" class="relative z-10" @close="closeModal">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative w-full transform overflow-hidden rounded-lg bg-white dark:bg-dark px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
              <DialogTitle class="text-center mb-5">
                <div v-if="props.itemToEdit">
                  <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">Edit item</p>
                  <p class="text-sm  text-gray-600 dark:text-gray-300">Update the details of this item</p>
                </div>

                <div v-else>
                  <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">New item</p>
                  <p class="text-sm  text-gray-600 dark:text-gray-300">Create a new item for this wishlist</p>
                </div>
              </DialogTitle>

              <!-- Start (url) screen -->
              <div v-if="!showDetails">

                <!-- URL link -->
                <div class="mb-6">
                    <InputLabel for="url" value="Enter a product url" />
                    <div class="flex flex-row items-center gap-x-3">
                      <div class="flex-1">
                        <TextInput
                            v-model.lazy="urlForm.url"
                            id="url"
                            type="url"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                        <InputError v-if="urlForm.errors.url" :message="urlForm.errors.url" class="mt-1"/>
                      </div>
                      <div>
                        <PrimaryButton @click="checkClipboardForUrl" class="mt-1">
                          <ClipboardIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                        </PrimaryButton>
                      </div>
                    </div>
                </div>

                <div v-if="loadUrlError" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                  <span class="font-medium">Error!</span> {{ loadUrlError }}
                </div>

                <Spinner v-if="showUrlLoadingSpinner" class="my-5 mx-auto text-center"/>

                <div v-else-if="showLoadUrlButton" class="text-center my-5">
                  <PrimaryButton @click="sendUrl" class="w-1/2">
                    <span>Load</span>
                    <span>
                      
                    </span>
                  </PrimaryButton>
                </div>

                <!-- Manual button -->
                <div class="mb-6 text-center">
                  <p class="dark:text-gray-200 text-gray-500"> Or </p>
                  <button @click="showDetails = true" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Enter manually</button>
                </div>

                <div class="mt-5 text-center">
                  <SecondaryButton class="w-full" @click="closeModal">Cancel</SecondaryButton>
                </div>
              </div>

              <!-- Details screen -->
              <div v-else class="flex-col gap-y-10">

                <!-- Missing fields -->
                <div v-if="hasMissedFields" class="flex my-3 items-center p-4 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300" role="alert">
                  <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                  </svg>
                  <span class="sr-only">Info</span>
                  <div>
                    <p>Some fields could not be retrieved automatically </p>
                  </div>
                </div>
                
                <!-- Image -->
                <div v-if="form.image" class="w-full mt-5">
                  <div class="w-24 h-24 rounded-md overflow-hidden mx-auto bg-white">
                    <img :src="form.image" alt="Your Image" class="w-full h-full object-contain">
                  </div>

                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm">
                  <!-- Form elements -->
                  <div class="flex flex-col gap-y-3">

                    <!-- Product name -->
                    <div>
                        <InputLabel for="name" value="Name of product" />
                        <TextInput
                            v-model="form.name"
                            id="name"
                            type="text"
                            class="w-full mt-1"
                            required
                            autocomplete="off"
                            autofocus
                        />
                        <InputError v-if="form.errors.name" :message="form.errors.name" class="mt-1"/>
                    </div>

                    <!-- Brand-->
                    <div>
                        <InputLabel for="brand" value="Brand" />
                        <TextInput
                            v-model="form.brand"
                            id="brand"
                            type="text"
                            class="w-full mt-1"
                        />
                        <InputError v-if="form.errors.brand" :message="form.errors.brand" class="mt-1"/>

                    </div>

                    <!-- Price-->
                    <div>
                        <InputLabel for="price" value="Price (£)" />
                        <TextInput
                            v-model="form.price"
                            id="price"
                            type="text"
                            class="w-full mt-1"
                        />
                        <InputError v-if="form.errors.price" class="mt-1" :message="form.errors.price" />
                    </div>

                    <!-- URL -->
                    <div>
                        <InputLabel for="url2" value="Url" />
                        <TextInput
                            v-model="form.url"
                            id="url2"
                            type="url"
                            class="w-full mt-1"
                        />
                        <InputError v-if="form.errors.url" class="mt-1" :message="form.errors.url" />
                    </div>

                    <!-- Comments -->
                    <div>
                      <TextArea v-model="form.comment" label="Comments" name="comment" id="comment" />
                      <InputError v-if="form.errors.comment" class="mt-1" :message="form.errors.comment" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <InputLabel for="needs" value="Quantity needed" />
                        <TextInput
                            v-model="form.needs"
                            id="needs"
                            type="number"
                            class="w-full mt-1"
                        />
                        <InputError v-if="form.errors.needs" class="mt-1" :message="form.errors.needs" />
                    </div>
                  </div>
            
                  <!-- Buttons -->
                  <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3">
                    <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                    <PrimaryButton :disabled="form.processing" type="submit">Save</PrimaryButton>
                  </div>
                </form>
              </div> 
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watchEffect, onBeforeUpdate, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import { ClipboardIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import InputLabel from "@/Components/form/InputLabel.vue"
import TextInput from "@/Components/form/TextInput.vue"
import TextArea from "@/Components/form/TextArea.vue"
import InputError from "@/Components/form/InputError.vue"
import Spinner from "@/Components/Spinner.vue"

import {useForm} from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    wishlistId: [String, Number],
    open: Boolean,
    itemToEdit: {
      type: Object,
      default: null
    },

})


const form = useForm({
  name: null,
  brand: null,
  price: null,
  url: null,
  comment: null,
  needs: 1,
  image: null
})

const urlForm = useForm({
  url: null
})


let isOpen = ref(props.open)
let showDetails = ref(false)
let hasMissedFields = ref(false)
let showLoadUrlButton = ref(false)
let loadUrlError = ref(false);

// Timeouts
let urlLoadingTimeoutId = ref(null);
let showUrlLoadingSpinner = ref(null);

onBeforeUpdate(() => {
  if (props.itemToEdit) {
    Object.assign(form, props.itemToEdit);
    showDetails.value=true;
  } else {
    form.reset();
  }
})

// Define the function to be called when urlForm.url changes
function onUrlChange() {
  const trimmedUrl = urlForm.url ? urlForm.url.trim() : '';
  showLoadUrlButton.value = trimmedUrl !== '';
}


// Watch for changes in urlForm.url
watch(() => urlForm.url, onUrlChange, { deep: true });


const emit = defineEmits(['update:open'])


watchEffect(() => {
    isOpen.value = props.open
})


function sendUrl(){
  /**
   * When the user clicks the load button
   * to load the url
   */
  
  // Set a timeout to change addLoading after 1 second
  urlLoadingTimeoutId.value = setTimeout(() => {
      showUrlLoadingSpinner.value = true;
  }, 250); 

  // Reset
  loadUrlError.value = null;

  axios.post(route('scrape', {"url": urlForm.url} ))
    .then(response => {

        let data = response.data

        // Check we get a product back
        let product = data.product ?? null;

        if(product){
          form.name = product.name
          form.brand = product.brand
          form.price = product.price
          form.url = urlForm.url
          form.image = product.image
          hasMissedFields.value = product.hasMissedFields
          showDetails.value = true;

        }
    
    })
    .catch(error => {
      if (error.response && error.response.data.errors && error.response.data.errors.url)
        {
          urlForm.setError('url', error.response.data.errors.url[0]);
        }
      if (error.response && error.response.data.error){
        loadUrlError.value = error.response.data.error;
        console.log(error.response.data.error)
      }

    }).finally(() => {
      clearTimeout(urlLoadingTimeoutId.value);
      showUrlLoadingSpinner.value=false;
    });  
}



async function checkClipboardForUrl() {

  if (navigator.clipboard && typeof navigator.clipboard.readText === "function") {
    try {
      const clipboardContent = await navigator.clipboard.readText();
      if (isValidUrl(clipboardContent)) {
        urlForm.url = clipboardContent;
      }
    } catch (err) {
      console.error('Failed to read clipboard:', err);
    }
  }else{
    console.log("Clipboard api not enabled")
  }
}

function isValidUrl(str) {
  try {
    new URL(str);
    return true;
  } catch (_) {
    return false;  
  }
}


function closeModal() {
  emit('update:open', false)
  setTimeout(reset, 500);
}

function reset(){

  form.errors = {};
  urlForm.errors = {};
  hasMissedFields.value = false;
  loadUrlError.value = false;

  form.reset();
  urlForm.reset();
  showDetails.value=false;
}

function submitForm()
{
  // Edit mode
  if (props.itemToEdit) {
    form.put(route('wishlists.items.update', [props.wishlistId, props.itemToEdit.id]), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    })
  } 

  // Create mode
  else {
    form.post(route('wishlists.items.store', props.wishlistId), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    })
  }
}

</script>