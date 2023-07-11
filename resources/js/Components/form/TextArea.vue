<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    label: String,
    id: String,
    name: String
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <div>
    <label :for="id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{label}}</label>
    <div class="mt-2">
      <textarea 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :id="id"
      ref="input"
      rows="4" 
      :name="name" 
      class="block p-2.5 w-full border-light-dark2 dark:border-dark-light2 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-dark dark:focus:border-primary-light focus:ring-primary-dark dark:focus:ring-primary-light2 rounded-md shadow-sm" />
    </div>
  </div>
</template>

