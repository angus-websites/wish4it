<template>
  <div class="w-full p-3 max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-dark-dark2 dark:border-gray-700">
      <div class="flex justify-end px-4 pt-4">
          <Menu as="div" class="relative ml-auto">
            <MenuButton class="-m-2.5 block p-2.5 text-gray-400 hover:text-gray-500">
              <span class="sr-only">Open options</span>
              <EllipsisHorizontalIcon class="h-5 w-5" aria-hidden="true" />
            </MenuButton>
            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
              <MenuItems class="absolute right-0 z-10 mt-0.5 w-32 origin-top-right rounded-md bg-light-light py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                <MenuItem v-slot="{ active }">
                  <button type="button" class="block w-full px-3 py-1 text-sm leading-6 text-red-500 hover:text-red-700"
                    >Remove friend</button
                  >
                </MenuItem>
              </MenuItems>
            </transition>
          </Menu>
      </div>
      <div class="flex flex-col items-center pb-3">
          <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ friend.name }}</h5>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{friend.wishlists.length }} wishlists</span>

          <!-- Dropdown -->
          <Disclosure v-slot="{ open }">
            <DisclosureButton class="flex items-center justify-between w-full py-3 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
              <span>Wishlists</span>
              <ChevronRightIcon :class="open ? 'rotate-90 transform' : ''"
                class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
            </DisclosureButton>

            <!-- Disclosure panel -->
            <DisclosurePanel class="w-full">
              <div class="py-5 border-b border-gray-200 dark:border-gray-700">
              
                <ul class="space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                    <li v-for="wishlist in friend.wishlists">
                        <Link :href="route('wishlists.show',wishlist.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{wishlist.title}}</Link>
                    </li>
                </ul>
              </div>
            </DisclosurePanel>
          </Disclosure>
      </div>
  </div>

</template>

<script setup>
import DangerButton from '@/Components/buttons/DangerButton.vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
} from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import { EllipsisHorizontalIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    friend: Object,
  })
</script>


