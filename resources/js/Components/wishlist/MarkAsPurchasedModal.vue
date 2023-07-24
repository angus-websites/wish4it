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
                  <p class="text-lg text-gray-700 dark:text-gray-200 font-bold ">Mark item as purchased</p>
                  <p class="text-sm  text-gray-600 dark:text-gray-300">{{ itemToMark.name }}</p>
                </div>
              </DialogTitle>

              <div class="relative w-full max-w-md max-h-full">
                  <div class="p-6 text-center">
  
                      <div class="my-3 text-gray-400 dark:text-gray-300">
                        <p>Quantity to reserve ({{itemToMark.needs}} wanted) </p>
                      </div>

                      <!-- Quantity to reserve -->
                      <TextInput type="number" />

                  </div>

                  <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                    <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                    <PrimaryButton @click="markItemAsPurchased">Update</PrimaryButton>
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
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import { router } from '@inertiajs/vue3'
import TextInput from "@/Components/form/TextInput.vue"

const props = defineProps({
    wishlistId: [String, Number],
    open: Boolean,
    itemToMark: {
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

function markItemAsPurchased()
{
  console.log("Marking");
}


</script>
