<script setup>
/**
 * Validation Error Component
 *
 * Provides a standardized, accessible way to display server-side
 * and client-side validation failures.
 */
defineProps({
  // The error message string returned from the backend (Inertia/Laravel)
  message: {
    type: String
  }
});
</script>

<template>
  <!-- 
        Conditional Visibility:
        Only occupies space in the DOM if an active error message exists. 
    -->
  <div v-show="message">
    <p class="text-sm text-red-600 dark:text-red-400">
      {{ message }}
    </p>
  </div>
</template>
