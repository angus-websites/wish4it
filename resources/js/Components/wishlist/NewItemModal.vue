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

              <!-- Start screen -->
              <div v-if="!showDetails">

                <!-- URL link -->
                <div class="mb-6">
                    <InputLabel for="url" value="Enter a product url" />
                    <TextInput
                        id="url"
                        type="url"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                </div>

                <!-- Manual button -->
                <div class="mb-6 text-center">
                  <p class="text-gray-200"> Or </p>
                  <button @click="showDetails = true" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Enter manually</button>
                </div>

                <div class="mt-5 text-center">
                  <SecondaryButton class="w-full" @click="closeModal">Cancel</SecondaryButton>
                </div>

              </div>

              <!-- Details screen -->
              <div v-else>
                
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
                            placeholder="5"
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
import { ref, watchEffect, onBeforeUpdate } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import InputLabel from "@/Components/form/InputLabel.vue"
import TextInput from "@/Components/form/TextInput.vue"
import TextArea from "@/Components/form/TextArea.vue"
import InputError from "@/Components/form/InputError.vue"

import {useForm} from '@inertiajs/vue3';

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
  comments: null,
  needs: 1
})

let isOpen = ref(props.open)
let showDetails = ref(false)


onBeforeUpdate(() => {
  if (props.itemToEdit) {
    Object.assign(form, props.itemToEdit);
    showDetails.value=true;
  } else {
    form.reset();
  }
})


const emit = defineEmits(['update:open'])


watchEffect(() => {
    isOpen.value = props.open
})


function closeModal() {
  emit('update:open', false)
}

function reset(){
  form.reset();
  closeModal();
}

function submitForm()
{
  // Edit mode
  if (props.itemToEdit) {
    form.put(route('wishlists.items.update', [props.wishlistId, props.itemToEdit.id]), {
      preserveScroll: true,
      onSuccess: () => reset(),
    })
  } 

  // Create mode
  else {
    form.post(route('wishlists.items.store', props.wishlistId), {
      preserveScroll: true,
      onSuccess: () => reset(),
    })
  }
}

</script>