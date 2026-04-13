<template>
  <!-- 
        Primary Action Button
        
        The main call-to-action (CTA) component for the application.
        Features:
        - Consistent Branding: Uses 'indigo-600' as the primary action color.
        - Accessibility: Includes focus rings and offsets for clear keyboard navigation.
        - Full State Feedback: Defines distinct styles for hover, focus, and active (click) states.
        - Dark Mode optimized: Switches background and ring colors for high contrast.
    -->
  <button
    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-indigo-600 dark:text-white dark:hover:bg-indigo-500 dark:focus:bg-indigo-500 dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
  >
    <!-- Slot allows for flexible content like labels, icons, or loading spinners -->
    <slot />
  </button>
</template>
