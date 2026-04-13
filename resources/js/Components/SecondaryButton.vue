<script setup>
/**
 * Secondary Action Button
 *
 * Used for neutral or alternative actions that should not
 * distract from the page's Primary call-to-action.
 */
defineProps({
  // Standard HTML button types (button, submit, reset)
  type: {
    type: String,
    default: 'button'
  }
});
</script>

<template>
  <!-- 
        Neutral Styling:
        - Uses a bordered, white/gray design to signify a secondary priority.
        - 'shadow-sm' provides a subtle elevation, maintaining a modern UI feel.
        - 'disabled:opacity-25' ensures clear visual feedback when actions are locked.
    -->
  <button
    :type="type"
    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
  >
    <slot />
  </button>
</template>
