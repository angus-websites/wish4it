<template>
  <div class="dark:bg-[#232D3B] dark:border-0 bg-white border rounded-none sm:rounded-xl flex flex-col">
    <!-- Banner -->
    <div v-if="itemPurchased" class="text-center bg-accent px-6 py-2 sm:px-3.5">
        <p class="text-sm text-dark font-bold">
          Purchased
        </p>
    </div>
    <!-- Rest -->
    <div class=" p-4 sm:p-6 flex flex-col flex-1">

      <!-- Dropdown menu -->
      <div v-if="item.can" class="flex items-center justify-end mb-3">
        <Menu as="div" class="relative ml-auto">
          <MenuButton class="-m-2.5 block p-2.5 text-gray-400 hover:text-gray-500">
            <span class="sr-only">Open options</span>
            <EllipsisHorizontalIcon class="h-6 w-6" aria-hidden="true" />
          </MenuButton>
          <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
            <MenuItems class="absolute right-0 z-10 mt-0.5 w-32 origin-top-right rounded-md bg-light-light py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">

              <template v-if="item.can.update || item.can.delete">
                <MenuItem v-if="item.can.update" v-slot="{ active }">
                  <button @click="editItem" type="button" class="block w-full px-3 py-1 text-sm leading-6 text-gray-500 hover:text-gray-700"
                    >Edit</button
                  >
                </MenuItem>
                <MenuItem v-if="item.can.delete" v-slot="{ active }">
                  <button @click="deleteItem" type="button" class="block w-full px-3 py-1 text-sm leading-6 text-red-500 hover:text-red-700"
                    >Delete</button
                  >
                </MenuItem>
              </template>
              <MenuItem v-else-if="item.can.mark" v-slot="{ active }">
                <button @click="markItem" type="button" class="block w-full px-3 py-1 text-xs leading-6 text-gray-500 hover:text-gray-700"
                  >Mark as purchased</button
                >
              </MenuItem>
              <MenuItem v-else>
                <p class="px-3 py-1 text-red-500">N/A</p>
              </MenuItem>


            </MenuItems>
          </transition>
        </Menu>
      </div>
      <!-- Image -->
      <div v-if="item.image" class="aspect-h-1 aspect-w-1 h-32 w-75 overflow-hidden rounded-lg bg-gray-200 mb-10">
        <img :src="item.image" :alt="item.name" class="h-full w-full object-contain object-center" />
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

      <!-- Buttons and quantity / needs -->
      <div class="mt-auto flex flex-col gap-y-3">
        <!-- Needs -->
        <div v-if="item.needs > 1" class="flex flex-row items-center justify-around">
          <p>Needs: {{item.needs}}</p>
          <p>Has: {{item.has}}</p>
        </div>

        <!-- Link -->
        <PrimaryButton v-if="item.url" :isAnchor="true" :href="item.url" target="_blank" >View</PrimaryButton>
      </div>


    </div>
    <small class="text-xs text-center pb-2 opacity-60">{{item.created_at}}</small>

  </div>
</template>

<script setup>
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue"
import SecondaryButton from "@/Components/buttons/SecondaryButton.vue"
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisHorizontalIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue';

import { defineEmits } from 'vue';
const props = defineProps({
    item: Object,
})

const emit = defineEmits(['edit', "delete", "mark"]);

const itemPurchased = computed(() => {
  return props.item.has >= props.item.needs;
});


function editItem() {
  emit('edit', props.item);
}

function deleteItem() {
  emit('delete', props.item);
}

function markItem() {
  emit('mark', props.item);
}


</script>
