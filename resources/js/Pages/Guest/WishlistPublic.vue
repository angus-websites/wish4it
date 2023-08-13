<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PageContainer from "@/Components/_util/PageContainer.vue";
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue";

import WishlistGrid from '@/Components/wishlist/WishlistGrid.vue'

defineProps({
    list: Object,
});
</script>

<template>
    <GuestLayout title="Home">
        <PageContainer>

            <div class="flex flex-row justify-between items-end">
                <div>
                    <h1 class="h1">{{list.title}}</h1>
                    <p class="h4 mt-2">{{list.owner.name}}</p>
                </div>
                <div>
                    <p>{{list.itemCount}} items</p>
                </div>
            </div>

            <WishlistGrid class="my-10" :items="list.items"/>
        </PageContainer>
    </GuestLayout>
</template>

