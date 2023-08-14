<template>
  <div class="p-4 border border-gray-300 rounded-lg bg-gray-50 dark:border-gray-600 dark:bg-gray-800" role="alert">
    <div class="flex items-center">
      <svg class="flex-shrink-0 w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <h3 class="text-lg font-medium text-gray-800 dark:text-gray-300">Add a new friend</h3>
    </div>
    <div class="mt-2 mb-4 text-sm text-gray-800 dark:text-gray-300">
      <p>Would you like to add <b>{{owner.name}} ({{owner.username}})</b> as a friend</p>
    </div>
    <div class="flex space-x-3">
      <GreenAddButton>Add as friend</GreenAddButton>
      <SecondaryButton>Dismiss</SecondaryButton>
    </div>
  </div>

</template>
<script setup>

  import GreenAddButton from '@/Components/buttons/GreenAddButton.vue'
  import SecondaryButton from '@/Components/buttons/SecondaryButton.vue'

  const props = defineProps({
      owner: Object
  })

</script>