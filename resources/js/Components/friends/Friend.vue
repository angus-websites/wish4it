<template>
  <div class="flex flex-col">

    <Menu as="div" class="relative ml-auto">
      <MenuButton class="-m-2.5 block p-2.5 text-gray-400 hover:text-gray-500">
        <span class="sr-only">Open options</span>
        <EllipsisHorizontalIcon class="h-5 w-5" aria-hidden="true" />
      </MenuButton>
      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItems class="absolute right-0 z-10 mt-0.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
          <MenuItem v-slot="{ active }">
            <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
              >Remove friend</a
            >
          </MenuItem>
        </MenuItems>
      </transition>
    </Menu>

    <Disclosure v-slot="{ open }">
      <DisclosureButton>
        <div class="relative flex items-center space-x-4 py-4">
          <div class="min-w-0 flex-auto">
            <div class="flex items-center gap-x-3">
              <h2 class="min-w-0 text-sm font-semibold leading-6 text-gray-800 dark:text-white hover:text-accent dark:hover:text-accent">
                <div class="flex gap-x-2">
                  <span class="whitespace-nowrap text-lg">{{ friend.name }}</span>
                  <span class="absolute inset-0"/>
                </div>
              </h2>
            </div>
            <div class="mt-3 flex items-center gap-x-2.5 text-xs leading-5 text-gray-500 dark:text-gray-400">
              <p class="whitespace-nowrap">{{ friend.wishlists.length }} wishlists</p>
            </div>
          </div>
          <ChevronRightIcon :class="open ? 'rotate-90 transform' : ''"
            class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
        </div>
      </DisclosureButton>
      <DisclosurePanel>
        <div v-for="wishlist in friend.wishlists" class="flex justify-between gap-x-4 py-3">
          
          <Link :href="route('wishlists.show',wishlist.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{wishlist.title}}</Link>

        </div>
      </DisclosurePanel>
    </Disclosure>
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


