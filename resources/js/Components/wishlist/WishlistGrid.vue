<template>

  <EmptyState v-if="displayedItems.length < 1">
      <p>No items</p>
  </EmptyState>

  <div v-else>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        <template v-for="item in displayedItems" :key="item.id">
          <WishlistItem :item="item" @edit="editItem" @delete="deleteItem" @mark="markItem"/>
        </template>
    </ul>
  </div>
</template>

<script setup>
import WishlistItem from '@/Components/wishlist/WishlistItem.vue'
import { defineEmits, computed } from 'vue';
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
    items: Object,
    showPurchased: {
      type: Boolean,
      default: false
    },
})

const emit = defineEmits(['edit', "delete", "mark"]);

// Computed property to filter items
const displayedItems = computed(() => {
  // Show all
  if (props.showPurchased) {
    return props.items;
  }
  // Only show items that are still available to buy
  return props.items.filter(item => item.needs > item.has);
});

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
