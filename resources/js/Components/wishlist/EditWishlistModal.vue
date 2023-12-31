<template>
  <TransitionRoot as="template" :show="isOpen">
    <Dialog as="div" class="relative z-10" @close="closeModal">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative w-full  transform overflow-hidden rounded-lg bg-white dark:bg-dark px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div v-if="showDeleteConfirmation">
              <div class="relative w-full max-w-md max-h-full">
                  <div class="p-6 text-center">
                      <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                      </svg>

                      
                      <p class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-200">Are you sure you want to delete this wishlist?</p>

                      <div class="my-3 text-gray-400 dark:text-gray-300">
                        <p> {{ wishlist.title }} </p>
                      </div>

                      <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                        <DangerButton @click="deleteWishlist">Delete</DangerButton>
                        <SecondaryButton @click="cancelDelete" type="button">Cancel</SecondaryButton>
                      </div>


                  </div>
              </div>
            </div>
            <!-- Form -->
            <div v-else>
              <DialogTitle class="text-center mb-2">
                <div v-if="props.wishlist">
                  <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">Edit wishlist</p>
                  <p class="text-sm  text-gray-600 dark:text-gray-300">Update the details of this wishlist</p>
                </div>

                <div v-else>
                  <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">New wishlist</p>
                  <p class="text-sm  text-gray-600 dark:text-gray-300">Create a new wishlist</p>
                </div>
              </DialogTitle>
              <form @submit.prevent="submitForm">
                <!-- Form elements -->
                <div class="flex flex-col gap-y-5">

                  <!-- Wishlist name -->
                  <div>
                      <InputLabel for="title" value="Wishlist name" />
                      <TextInput
                          v-model="form.title"
                          id="title"
                          type="text"
                          class="w-full mt-1"
                          required
                          autocomplete="off"
                          autofocus
                      />
                      <InputError v-if="form.errors.title" :message="form.errors.title" class="mt-1"/>
                  </div>

                  <!-- Public-->
                  <div>
                    <ToggleSwitch v-model="form.public" title="Public list" description="Should this wishlist be visible to the public?"/>
                  </div>
          
                  <!-- Buttons -->
                  <div class="mt-5 flex flex-row justify-between items-center">
                    <div>
                      <DangerButton v-if="props.wishlist && canDelete" @click="deleteClicked" type="button">Delete list</DangerButton>
                    </div>
                    <div class="flex flex-col gap-y-3 xxs:flex-row gap-x-3">
                      <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                      <PrimaryButton :disabled="form.processing" type="submit"><span v-if="props.wishlist">Save</span><span v-else>Create</span></PrimaryButton>
                    </div>
                  </div>
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
import { ref, watchEffect, onMounted, watch, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import DangerButton from "@/Components/buttons/DangerButton.vue"
import DefaultBadge from "@/Components/badges/DefaultBadge.vue"

import InputLabel from "@/Components/form/InputLabel.vue"
import TextInput from "@/Components/form/TextInput.vue"
import InputError from "@/Components/form/InputError.vue"
import ToggleSwitch from "@/Components/form/ToggleSwitch.vue"

import {useForm, router} from '@inertiajs/vue3';

const props = defineProps({
    wishlist: {
      type: Object,
      default: null
    },
    canDelete: {
      type: Boolean,
      default: false
    },
    open: Boolean,
})

const form = useForm({
  title: null,
  public: false,
})

let isOpen = ref(props.open)
let showDeleteConfirmation = ref(false)

watch(isOpen, () => {

  // Only reset when the modal opens
  if (isOpen.value){
    // Reset form elements
    form.reset();
    form.clearErrors();
    // Ensure delete page is closed
    showDeleteConfirmation.value = false;

    // Populate with data (Edit mode only)
    if (props.wishlist){
      form.title = props.wishlist.title
      form.public = props.wishlist.public
    }
    
  }
});


const emit = defineEmits(['update:open'])

watchEffect(() => {
    isOpen.value = props.open
})


function closeModal() {
  emit('update:open', false)
}

function reset(){
  form.reset();
  form.clearErrors();
  closeModal();
}

function submitForm()
{

  // Edit mode
  if (props.wishlist) {
    form.put(route('wishlists.update', [props.wishlist.id]), {
      preserveScroll: true,
      onFinish: () => reset(),
    })
  }
  // Create mode
  else{
    form.post(route('wishlists.store'), {
      preserveScroll: true,
      onFinish: () => reset(),
    })
  }
}

function deleteClicked()
{
  /**
   * When the user clicks the delete button
   * show a confirmation page first
   */
  showDeleteConfirmation.value=true;
}

function cancelDelete()
{
  /**
   * When user clicks cancel on the
   * delete page
   */
  showDeleteConfirmation.value=false;
}

function deleteWishlist()
{
  /**
   * Delete the wishlist
   */
  router.delete(
    route("wishlists.destroy", [props.wishlist.id]),
    {onFinish: reset(), preserveScroll: true}
  )
  
}

</script>