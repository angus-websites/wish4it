<template>
    <AppLayout title="My Lists">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ list.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <PrimaryButton @click="createNewItem" class="mb-5">New item</PrimaryButton>
                <WishlistGrid :items="list.items" />
            </div>
        </div>

        <NewItemModal :open="open" @update:open="handleModal" />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue'
import NewItemModal from '@/Components/wishlist/NewItemModal.vue'
import { ref } from 'vue'

let open = ref(false)

const props = defineProps({
    list: Object,
    can: Object
})

function createNewItem()
{
    open.value = true;
}

function handleModal(value) {
    open.value = value;
}


</script>
