<template>

  <EmptyState v-if="items.length < 1">
      <p>No items</p>
  </EmptyState>

  <div v-else class="bg-white dark:bg-[#2a3748]">
    <p class="sr-only">Products</p>
    <div class="grid grid-cols-2 border-l border-gray-200 dark:border-[#2a3748] sm:mx-0 md:grid-cols-3 lg:grid-cols-4">
      <WishlistItem v-for="item in items" :item="item" :key="item.id" @edit="editItem" @delete="deleteItem" @mark="markItem"/>
    </div>
  </div>
</template>

<script setup>
import WishlistItem from '@/Components/wishlist/WishlistItem.vue'
import { defineEmits } from 'vue';
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
    items: Object,
})

const emit = defineEmits(['edit', "delete", "mark"]);

function editItem(item) {
  emit('edit', item);
}

function deleteItem(item) {
  emit('delete', item);
}

function markItem(item) {
  emit('mark', item);
}
</script>