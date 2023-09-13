<template>
  <span
    :class="badgeClass"
    class="text-sm font-semibold mr-2 px-2.5 py-0.5 rounded border">
    <slot/>
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  public: Boolean,
})

const badgeClass = computed(() => {
  if (props.public) {
    return "bg-lime-100 text-accent-dark3 dark:bg-gray-800 dark:text-lime-400 border-accent-dark2";
  } else {
    return "bg-purple-100 text-primary dark:bg-gray-800 dark:text-purple-400 border-primary dark:border-primary-light2";
  }
});

</script>
