<template>
  <!-- 
        Danger Button Component
        
        A specialized button designed for high-risk or destructive actions 
        (e.g., Delete, Terminate). 
        
        Styling:
        - Uses 'red-600' to visually communicate risk and urgency.
        - 'tracking-widest' and 'uppercase' provide a distinct, formal appearance.
        - 'active:bg-red-700' provides tactile feedback during a user's click.
    -->
  <button
    class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-700 dark:focus:ring-offset-gray-800"
  >
    <!-- Allows for dynamic content, such as text or icons, while maintaining consistent styles -->
    <slot />
  </button>
</template>
