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
                      <svg class="mx-auto mb-4 text-gray-500 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                      </svg>

                      
                      <p class="mb-2 text-lg font-normal text-gray-600 dark:text-gray-200">Are you sure you want to mark this product as purchased?</p>

                     <dl class="divide-y divide-gray-100 dark:divide-dark-light">
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-700 dark:text-gray-400">Item to mark</dt>
                          <dd class="mt-1 text-sm font-bold leading-6 text-gray-500 dark:text-light-dark sm:col-span-2 sm:mt-0">{{itemToMark.name}}</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-blue-500 dark:text-accent-light">Quantity chosen to reserve</dt>
                          <dd class="mt-1 text-sm font-bold leading-6 text-blue-500 dark:text-accent-light sm:col-span-2 sm:mt-0">{{form.quantity}}</dd>
                        </div>
                        <div v-if="itemToMark.has" class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-700 dark:text-gray-400">Quantity already purchased by others</dt>
                          <dd class="mt-1 text-sm font-bold leading-6 text-gray-500 dark:text-light-dark sm:col-span-2 sm:mt-0">{{itemToMark.has}}</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                          <dt class="text-sm font-medium text-gray-700 dark:text-gray-400">Quantity desired by list creator</dt>
                          <dd class="mt-1 text-sm font-bold leading-6 text-gray-500 dark:text-light-dark sm:col-span-2 sm:mt-0">{{itemToMark.needs}}</dd>
                        </div>
                        
                      </dl> 

                      <div class="my-8">

                        <div v-if="isQuantityReached" class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 border border-blue-800 dark:border-0" role="alert">
                          <span>This item will be removed from the wishlist as the desired quantity will be reached</span>
                        </div>

                        <div v-else-if="form.quantity < quantityNeeded" class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 border-yellow-800 border dark:border-0" role="alert">
                          <span>This item <b>will</b> remain visible on this wishlist as the desired quanity has not been reached</span>
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

                      
                        <div class="my-3 text-gray-500 dark:text-gray-300">
                          <p><b>{{itemToMark.needs}}</b> wanted<span v-if="itemToMark.has">, <b>{{itemToMark.has}}</b> already purchased</span></p>
                        </div>

                        <!-- Quantity to reserve -->
                        <div class="flex flex-col">
                          <InputLabel for="markQuantity" value="Quantity to reserve" />
                          <TextInput id="markQuantity" v-model="form.quantity" type="number" class="mt-1" />
                          <InputError v-if="form.errors.quantity" :message="form.errors.quantity" class="mt-1 text-left"/>
                        </div>



                    </div>

                
                    <div class="mt-5 flex flex-col gap-y-3 sm:flex-row gap-x-3 justify-center">
                      <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                      <PrimaryButton @click="clickUpdateButton">Continue</PrimaryButton>
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
import { ref, watchEffect, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import { router } from '@inertiajs/vue3'
import TextInput from "@/Components/form/TextInput.vue"
import InputLabel from "@/Components/form/InputLabel.vue"
import InputError from "@/Components/form/InputError.vue"

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


const quantityNeeded = computed(() => {
  return props.itemToMark.needs - props.itemToMark.has
})

const isQuantityReached = computed(() => {
  return form.quantity >=  (props.itemToMark.needs - props.itemToMark.has)
})


function closeModal() {
  emit('update:open', false)
  setTimeout(reset, 500);
}

function reset(){
  form.errors = {};

  form.reset();
  showConfirmation.value=false;
}

function clickUpdateButton()
{
  if (form.quantity <= 0 || !Number.isInteger(Number(form.quantity))) {
    form.setError('quantity', 'Quantity must be a positive integer');
  } else {
    form.clearErrors('quantity')
    showConfirmation.value = true;
  }
}

function markItemAsPurchased()
{
  console.log("Marking");
}


</script>
