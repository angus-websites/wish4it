<template>
  <div class="group relative border-b border-r border-gray-200 p-4 sm:p-6">
    <div class="aspect-h-1 aspect-w-1 h-48 overflow-hidden rounded-lg bg-gray-200 group-hover:opacity-75">
      <img :src="item.imageSrc" :alt="item.imageAlt" class="h-full w-full object-cover object-center" />
    </div>
    <div class="pb-4 pt-10 text-center">
      <h3 class="text-sm font-medium text-gray-900">
        <a :href="item.href">
          <span aria-hidden="true" class="absolute inset-0" />
          {{ item.title }}
        </a>
      </h3>
      <p class="mt-4 text-base font-medium text-gray-900">{{ item.price }}</p>
    </div>
  </div>
</template>

<script setup>

const props = defineProps({
    item: Object,
})

</script>