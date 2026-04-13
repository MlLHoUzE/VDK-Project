<script setup>
/**
 * Primary Navigation Link
 *
 * A specialized link for the main navigation bar.
 * Features:
 * - Reactive Active States: Dynamically changes styling based on the current route.
 * - Inertia Integration: Enables smooth, SPA-style navigation without page refreshes.
 * - Dark Mode Support: Adaptive text and border colors for all themes.
 */
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  // The target URL or route name
  href: {
    type: String,
    required: true
  },
  // Boolean flag typically derived from route().current() in the parent layout
  active: {
    type: Boolean
  }
});

/**
 * Dynamic Class Computation
 * Separates 'Active' vs 'Inactive' styles to keep the template clean.
 * Uses 'border-b-2' to create the standard 'tab' underline effect.
 */
const classes = computed(() =>
  props.active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out'
);
</script>

<template>
  <Link :href="href" :class="classes">
    <!-- Injects the link text or icon -->
    <slot />
  </Link>
</template>
