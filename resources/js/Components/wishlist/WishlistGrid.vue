<template>
  <div class="bg-white dark:bg-[#2a3748]">
    <h2 class="sr-only">Products</h2>
    <div class="grid grid-cols-2 border-l border-gray-200 dark:border-[#2a3748] sm:mx-0 md:grid-cols-3 lg:grid-cols-4">
      <WishlistItem v-for="item in items" :item="item" :key="item.id" @edit="editItem" @delete="deleteItem"/>
    </div>
  </div>
</template>

<script setup>
import WishlistItem from '@/Components/wishlist/WishlistItem.vue'
import { defineEmits } from 'vue';
const props = defineProps({
    items: Object,
})

const emit = defineEmits(['edit', "delete"]);

function editItem(item) {
  emit('edit', item);
}

function deleteItem(item) {
  emit('delete', item);
}
</script>