<script setup>
/**
 * Custom Checkbox Component
 *
 * A wrapper around the native checkbox input that provides consistent
 * Tailwind styling and seamless v-model integration.
 */
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
  // Supports both single boolean toggles and array-based multiple selections
  checked: {
    type: [Array, Boolean],
    required: true
  },
  value: {
    default: null
  }
});

/**
 * Proxy Computed Property
 * Allows the component to use v-model internally while maintaining
 * 'one-way data flow' by emitting changes back to the parent.
 */
const proxyChecked = computed({
  get() {
    return props.checked;
  },

  set(val) {
    emit('update:checked', val);
  }
});
</script>

<template>
  <input
    type="checkbox"
    :value="value"
    v-model="proxyChecked"
    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
  />
</template>
