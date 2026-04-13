<script setup>
/**
 * Reusable Dropdown Component
 *
 * A robust menu system that handles positioning, animations,
 * and accessibility triggers (Escape key, outside clicks).
 */
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
  align: {
    type: String,
    default: 'right'
  },
  width: {
    type: String,
    default: '48'
  },
  // Allows customization of the dropdown interior (e.g., adding padding or dark mode support)
  contentClasses: {
    type: String,
    default: 'py-1 bg-white dark:bg-gray-700'
  }
});

/**
 * Accessibility: Keyboard Support
 * Listens for the 'Escape' key to close the dropdown for a better UX.
 */
const closeOnEscape = (e) => {
  if (open.value && e.key === 'Escape') {
    open.value = false;
  }
};

// Lifecycle Management: Attaching/Removing global listeners to prevent memory leaks
onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
  return {
    48: 'w-48'
  }[props.width.toString()];
});

/**
 * RTL/LTR Positioning Logic
 * Dynamically adjusts the dropdown anchor point and origin based on
 * text direction and alignment props.
 */
const alignmentClasses = computed(() => {
  if (props.align === 'left') {
    return 'ltr:origin-top-left rtl:origin-top-right start-0';
  } else if (props.align === 'right') {
    return 'ltr:origin-top-right rtl:origin-top-left end-0';
  } else {
    return 'origin-top';
  }
});

const open = ref(false);
</script>

<template>
  <div class="relative">
    <div @click="open = !open">
      <slot name="trigger" />
    </div>

    <!-- 
            Full Screen Overlay: 
            Captures clicks outside the dropdown to close it, 
            providing a standard 'Click-away' behavior.
        -->
    <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

    <!-- Smooth Menu Transition (Fade & Scale) -->
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-show="open"
        class="absolute z-50 mt-2 rounded-md shadow-lg"
        :class="[widthClass, alignmentClasses]"
        style="display: none"
        @click="open = false"
      >
        <div
          class="rounded-md ring-1 ring-black ring-opacity-5"
          :class="contentClasses"
        >
          <slot name="content" />
        </div>
      </div>
    </Transition>
  </div>
</template>
