<template>
  <ul role="list" class="divide-y divide-gray-600/5 bg-white dark:bg-dark rounded-lg p-3">
    <li v-for="list in lists" :key="lists.id" class="relative flex items-center space-x-4 py-4">
      <div class="min-w-0 flex-auto">
        <div class="flex items-center gap-x-3">
          <h2 class="min-w-0 text-sm font-semibold leading-6 text-gray-800 dark:text-white hover:text-accent dark:hover:text-accent">
            <Link :href="route('wishlists.show', list.id)" class="flex gap-x-2">
              <span class="whitespace-nowrap text-lg">{{ list.title }}</span>
              <span class="absolute inset-0" />
            </Link>
          </h2>
        </div>
        <div class="mt-3 flex items-center gap-x-2.5 text-xs leading-5 text-gray-500 dark:text-gray-400">
          <p class="whitespace-nowrap">{{ list.itemCount }} items</p>
        </div>
      </div>
      
      <DefaultBadge><span v-if="list.public">Public</span><span v-else>Private</span></DefaultBadge>
      <ChevronRightIcon class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
    </li>
  </ul>
</template>

<script setup>
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import DefaultBadge from "@/Components/badges/DefaultBadge.vue"

const props = defineProps({
    lists: Object,
})

const visibilities = {
  true: 'text-gray-400 bg-gray-400/10 ring-gray-400/20',
  false: 'text-indigo-400 bg-indigo-400/10 ring-indigo-400/30',
}

</script>