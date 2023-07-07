<template>
  <div class="bg-white dark:bg-[#2a3748]">
    <h2 class="sr-only">Products</h2>
    <div class="grid grid-cols-2 border-l border-gray-200 dark:border-[#2a3748] sm:mx-0 md:grid-cols-3 lg:grid-cols-4">
      <WishlistItem v-for="product in items" :item="product" />
    </div>


  </div>


</template>

<script setup>
import WishlistItem from '@/Components/wishlist/WishlistItem.vue'

const props = defineProps({
    items: Object,
})

const products = [
  {
    id: 1,
    title: 'Organize Basic Set (Walnut)',
    price: '$149',
    rating: 5,
    reviewCount: 38,
    imageSrc: 'https://i8.amplience.net/i/jpl/sz_156841_a?qlt=92&w=600&h=464&v=1&fmt=auto',
    imageAlt: 'TODO',
    href: '#',
  },
  {
    id: 2,
    title: 'Organize Pen Holder',
    price: '$15',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-05-image-card-02.jpg',
    imageAlt: 'TODO',
    href: '#',
  },
  {
    id: 3,
    title: 'Organize Sticky Note Holder',
    price: '$15',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-05-image-card-03.jpg',
    imageAlt: 'TODO',
    href: '#',
  },
  {
    id: 4,
    title: 'Organize Phone Holder',
    price: '$15',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-05-image-card-04.jpg',
    imageAlt: 'TODO',
    href: '#',
  },
  // More products...
]
</script>