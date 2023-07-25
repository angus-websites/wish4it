<template>
  <footer class="bg-slate-50 dark:bg-[#141A22]">
    <div class="mx-auto max-w-7xl overflow-hidden px-6 py-10 sm:py-14 lg:px-8">
      <p class="text-lg text-center leading-5">wish4it.net</p>
    </div>
  </footer>
</template>

<script setup>
import { defineComponent, h } from 'vue'
import {usePage} from '@inertiajs/vue3';

const showLogin = usePage().props.showLogin

let navigation = [
  
]

navigation = [
    ...navigation,
    ...(!showLogin ? [{
            name: 'Login',
            href: route('login'),
        }]
        : []),
]
</script>
