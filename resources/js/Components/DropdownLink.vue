<script setup>
/**
 * Dropdown Link Component
 *
 * Specialized link component for use within the Dropdown menu.
 * Utilizes the Inertia Link component to perform 'client-side'
 * navigation, preventing full page reloads.
 */
import { Link } from '@inertiajs/vue3';

defineProps({
  // The target URL or route name (via Ziggy)
  href: {
    type: String,
    required: true
  }
});
</script>

<template>
  <!--
        Styling:
        - 'text-start': Ensures alignment is consistent with dropdown triggers.
        - 'transition': Provides smooth visual feedback on hover/focus.
        - 'dark:text-gray-300': Fully optimized for system-level dark mode.
    -->
  <Link
    :href="href"
    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
  >
    <!-- Injects the link text or nested icons -->
    <slot />
  </Link>
</template>
