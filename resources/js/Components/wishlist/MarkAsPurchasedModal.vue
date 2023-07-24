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

              <div v-if="showConfirmation" class="relative w-full max-w-md max-h-full">
                  <div class="p-6 text-center">
                      <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                      </svg>

                      
                      <p class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-200">Are you sure you want to mark this product as purchased?</p>

                      <div class="my-8">

                        <div v-if="form.quantity >= itemToMark.needs" class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                          <span>This item will be removed from the wishlist as the quantity desired will be reached </span>
                        </div>

                        <div v-else-if="form.quantity < itemToMark.needs" class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                          <span>This item <b>will</b> remain visible on this wishlist as the quantity you selected to reserve is less than the desired amount</span>
                        </div>

                      </div>

                      <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                        <SecondaryButton @click="showConfirmation = false" type="button">Back</SecondaryButton>
                        <PrimaryButton @click="markItemAsPurchased">Update</PrimaryButton>

                      </div>

                  </div>
              </div>

              <template v-else>
                <DialogTitle class="text-center">
                  <div>
                    <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">Mark item as purchased</p>
                    <p class="text-sm  text-gray-600 dark:text-gray-300">{{ itemToMark.name }}</p>
                  </div>
                </DialogTitle>
                <div class="relative w-full max-w-md max-h-full">

                    <div class="p-6 text-center">

                        <!-- <div v-if="form.quantity >= itemToMark.needs" class="p-4 text-sm text-center text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                          This item will be removed from the list as the desired quantity will been reached
                        </div> -->
    
                        <div class="my-3 text-gray-400 dark:text-gray-300">
                          <p>Quantity to reserve ({{itemToMark.needs}} wanted) </p>
                        </div>

                        <!-- Quantity to reserve -->
                        <TextInput v-model="form.quantity" type="number" />


                    </div>

                
                    <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                      <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                      <PrimaryButton @click="clickUpdateButton">Update</PrimaryButton>
                    </div>
                </div>
              </template>


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
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import { router } from '@inertiajs/vue3'
import TextInput from "@/Components/form/TextInput.vue"
import {useForm} from '@inertiajs/vue3';

const props = defineProps({
    wishlistId: [String, Number],
    open: Boolean,
    itemToMark: {
      type: Object,
    },

})

const form = useForm({
  quantity: 1,
})

let isOpen = ref(props.open)
const emit = defineEmits(['update:open'])
let showConfirmation = ref(false)


watchEffect(() => {
    isOpen.value = props.open
})


function closeModal() {
  emit('update:open', false)
}

function clickUpdateButton()
{
  showConfirmation.value=true;
}

function markItemAsPurchased()
{
  console.log("Marking");
}


</script>
