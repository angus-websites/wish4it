<template>
  <div class="overflow-hidden rounded-xl border border-gray-200">
    <div class="flex items-center gap-x-4 border-b border-gray-900/5 bg-gray-50 p-6">
      <div class="text-sm font-medium leading-6 text-gray-900">{{ friend.name }}</div>
      <Menu as="div" class="relative ml-auto">
        <MenuButton class="-m-2.5 block p-2.5 text-gray-400 hover:text-gray-500">
          <span class="sr-only">Open options</span>
          <EllipsisHorizontalIcon class="h-5 w-5" aria-hidden="true" />
        </MenuButton>
        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
          <MenuItems class="absolute right-0 z-10 mt-0.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
            <MenuItem v-slot="{ active }">
              <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                >View<span class="sr-only">, {{ friend.name }}</span></a
              >
            </MenuItem>
            <MenuItem v-slot="{ active }">
              <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                >Edit<span class="sr-only">, {{ friend.name }}</span></a
              >
            </MenuItem>
          </MenuItems>
        </transition>
      </Menu>
    </div>
    <Disclosure v-slot="{ open }">
      <DisclosureButton
        class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75"
      >
        <span>Wishlists</span>
        <ChevronUpIcon
          :class="open ? 'rotate-180 transform' : ''"
          class="h-5 w-5 text-purple-500"
        />
      </DisclosureButton>
      <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
        <dl class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm leading-6">
          <div v-for="wishlist in friend.wishlists" class="flex justify-between gap-x-4 py-3">
            <dt class="text-gray-500">{{ wishlist.title }}</dt>
            <dd class="text-gray-700">
              <Link class="text-blue-200" :href="route('wishlists.show',wishlist.id)">View</Link>
            </dd>
          </div>
        </dl>
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
import { EllipsisHorizontalIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    friend: Object,
  })
</script>