<template>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <div v-for="list in lists" :key="list.id" class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white dark:bg-dark-dark2 dark:border-gray-700 px-6 py-5 shadow-sm hover:border-primary-dark2 dark:hover:border-accent-dark2">
        <div class="min-w-0 flex-1">
          <Link :href="route('wishlists.show', list.id)" class="focus:outline-none">
            <span class="absolute inset-0" aria-hidden="true" />
            <div class="flex flex-row items-center justify-between">
              <div>
                <p class="font-medium text-gray-900 dark:text-gray-300">{{ list.title }}</p>
                <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ list.itemCount}} items</p>
              </div>
              <DefaultBadge><span v-if="list.public">Public</span><span v-else>Private</span></DefaultBadge>
            </div>
          </Link>
        </div>
      </div>
    </div>
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