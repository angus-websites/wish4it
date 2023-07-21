<template>
  <div class="border-b border-r border-gray-200 dark:border-[#1A202A] p-4 sm:p-6 flex flex-col">

    <!-- Image -->
    <div v-if="item.image" class="aspect-h-1 aspect-w-1 h-32 w-75 overflow-hidden rounded-lg bg-gray-200 mb-10">
      <img :src="item.image" :alt="item.name" class="h-full w-full object-cover object-center" />
    </div>

    <!-- Content -->
    <div class="flex-1">
      <div class="pb-4 text-center flex flex-col gap-y-5 justify-center h-full">

        <!-- Title & Brand-->
        <div>
          <p v-if="item.brand" class="text-sm text-gray-500">{{item.brand}}</p>
          <h3 class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
            {{ item.name }}
          </h3>
        </div>

        <!-- Price -->
        <div v-if="item.price" class="p-3">
          <p class="text-base font-medium text-gray-900 dark:text-slate-200">£{{ item.price }}</p>
        </div>

        <!-- Comment -->
        <div v-if="item.comment" class="p-3 bg-gray-100 dark:bg-[#314053] rounded-lg">
          <p class="text-sm">{{item.comment}}</p>
        </div>

      </div>
    </div>

    <!-- Buttons -->
    <div class="mt-auto">
      <PrimaryButton v-if="item.can.update" @click="editItem" size="s">Edit</PrimaryButton>
      <SecondaryButton v-if="item.can.delete" @click="deleteItem" size="s">Delete</SecondaryButton>
    </div>

  </div>

</template>

<script setup>
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"

import { defineEmits } from 'vue';
const props = defineProps({
    item: Object,
})

const emit = defineEmits(['edit', "delete"]);

function editItem() {
  emit('edit', props.item);
}

function deleteItem() {
  emit('delete', props.item);
}


</script>