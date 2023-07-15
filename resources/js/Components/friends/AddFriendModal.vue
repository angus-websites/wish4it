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
            <DialogTitle class="text-center">
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
                        id="username"
                        type="text"
                        class="w-full mt-1"
                        required
                        autocomplete="off"
                        autofocus
                    />
                    <InputError v-if="form.errors.username" :message="form.errors.username" class="mt-1"/>
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
import { ref, watchEffect, watch} from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import DangerButton from "@/Components/buttons/DangerButton.vue"
import DefaultBadge from "@/Components/badges/DefaultBadge.vue"

import InputLabel from "@/Components/form/InputLabel.vue"
import TextInput from "@/Components/form/TextInput.vue"
import InputError from "@/Components/form/InputError.vue"

import {useForm, router} from '@inertiajs/vue3';

const props = defineProps({
    open: Boolean,
})


let isOpen = ref(props.open)
const emit = defineEmits(['update:open'])

const form = useForm({
  username: null,
})


watchEffect(() => {
    isOpen.value = props.open
})

function closeModal() {
  emit('update:open', false)
}

function submitForm(){
  console.log("Hello");
}




</script>