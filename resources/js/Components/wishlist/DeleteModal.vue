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

              <div class="relative w-full max-w-md max-h-full">
                  <div class="p-6 text-center">
                      <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                      </svg>

                      
                      <p class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-200">Are you sure you want to delete this product?</p>

                      <div class="my-3 text-gray-400 dark:text-gray-300">
                        <p> {{ itemToDelete.name }} </p>
                      </div>

                      <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                        <DangerButton @click="deleteItem">Delete</DangerButton>
                        <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                      </div>


                  </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import DangerButton from "@/Components/buttons/DangerButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import { router } from '@inertiajs/vue3'

const props = defineProps({
    wishlistId: String,
    open: Boolean,
    itemToDelete: {
      type: Object,
    },

})


let isOpen = ref(props.open)
const emit = defineEmits(['update:open'])


watchEffect(() => {
    isOpen.value = props.open
})


function closeModal() {
  emit('update:open', false)
}

function deleteItem()
{
  console.log("deleting: "+props.itemToDelete.id+" Route: "+route("wishlists.items.destroy", [props.wishlistId, props.itemToDelete.id]))
  router.delete(route("wishlists.items.destroy", [props.wishlistId, props.itemToDelete.id]))
}


</script>
