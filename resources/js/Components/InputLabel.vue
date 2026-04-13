<script setup>
/**
 * Accessible Input Label Component
 *
 * Provides a consistent style for form labels across the application.
 * Supports both a 'value' prop for simple text and a default slot
 * for more complex label content (like icons or HTML).
 */
defineProps({
  // The text content of the label
  value: {
    type: String
  }
});
</script>

<template>
  <!-- 
        Semantic Label: 
        Uses standard 'font-medium' and 'text-sm' for professional form readability. 
    -->
  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
    <!-- Renders the 'value' prop if provided -->
    <span v-if="value">{{ value }}</span>
    <!-- Falls back to the slot if no 'value' prop is present -->
    <span v-else><slot /></span>
  </label>
</template>
