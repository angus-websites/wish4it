<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PageContainer from "@/Components/_util/PageContainer.vue";
import PrimaryButton from "@/Components/buttons/PrimaryButton.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <GuestLayout title="Home">
        <PageContainer>

            <div class="p-4 rounded-lg text-center">
              <h1 class="h1">A website for creating & sharing wishlists.</h1>
              <div class="py-10">
                  <PrimaryButton :isLink="true" :href="route('register')" size="l">Get started</PrimaryButton>
              </div>
            </div>


        </PageContainer>
    </GuestLayout>
</template>

