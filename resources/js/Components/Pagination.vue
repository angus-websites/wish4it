<template>
  <!-- Pagination -->
  <nav v-if="model.data.length > 0" class="flex items-center justify-between" aria-label="Pagination">
    <div class="hidden sm:block">
      <p class="text-sm text-gray-700 dark:text-gray-400">
        Page
        {{ ' ' }}
        <span class="font-medium">{{model.meta.current_page}}</span>
        {{ ' ' }}
        of
        {{ ' ' }}
        <span class="font-medium">{{model.meta.last_page}}</span>
      </p>
    </div>
    <div class="flex flex-1 justify-between sm:justify-end">
      <Link preserveScroll v-if="model.links.prev" :href="model.links.prev" class="px-3 py-2 text-sm rounded-md font-semibold text-dark-light2 shadow-sm bg-light-dark hover:bg-light-dark2 dark:bg-dark-light dark:text-light dark:hover:bg-dark-light2">Previous</Link>
      <Link preserveScroll v-if="model.links.next"  :href="model.links.next" class="px-3 py-2 text-sm rounded-md font-semibold text-dark-light2 shadow-sm bg-light-dark hover:bg-light-dark2 dark:bg-dark-light dark:text-light dark:hover:bg-dark-light2">Next</Link>
    </div>
  </nav>
</template>

<script setup>
defineProps({
  model: Object
})
</script>
