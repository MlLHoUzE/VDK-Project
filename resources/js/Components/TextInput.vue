<script setup>
/**
 * Custom Text Input Component
 *
 * A reusable, styled wrapper for standard text and number inputs.
 * Features:
 * - defineModel: Leverages Vue 3.4+ native two-way binding.
 * - Auto-Focus: Detects the 'autofocus' attribute and triggers focus on mount.
 * - Exposed Methods: Allows parent components to programmatically focus the element.
 */
import { onMounted, ref } from 'vue';

const model = defineModel({
  type: [String, Number],
  required: true
});

const input = ref(null);

/**
 * Focus Lifecycle
 * Improves UX by automatically readying the input if the developer
 * passes the 'autofocus' boolean attribute.
 */
onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

// Exposes internal DOM methods to parent components via Template Refs
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input
    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
    v-model="model"
    ref="input"
  />
</template>
