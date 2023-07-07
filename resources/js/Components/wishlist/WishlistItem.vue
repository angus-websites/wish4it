<template>
  <div class="border-b border-r border-gray-200 dark:border-[#1A202A] p-4 sm:p-6">
    <div class="aspect-h-1 aspect-w-1 h-32 w-75 overflow-hidden rounded-lg bg-gray-200">
      <img :src="item.imageSrc" :alt="item.imageAlt" class="h-full w-full object-cover object-center" />
    </div>
    <div class="pb-4 pt-10 text-center flex flex-col gap-y-5">

      <!-- Title -->
      <div>
        <p class="text-sm text-gray-500">Amazon</p>
        <h3 class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
          {{ item.title }}
        </h3>
      </div>

      <!-- Comment -->
      <div class="p-3 bg-gray-100 dark:bg-[#314053] rounded-lg">
        <p class="text-sm">Size: M, if in stock</p>
      </div>

      <!-- Price -->
      <div class="p-3">
        <p class="text-base font-medium text-gray-900 dark:text-slate-200">{{ item.price }}</p>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col gap-y-3">
        <SecondaryButton size="s">Visit</SecondaryButton>

        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Mark as purchased</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"

const props = defineProps({
    item: Object,
})

</script>