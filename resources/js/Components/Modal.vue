<script setup>
/**
 * Accessible Modal Component
 *
 * A high-performance wrapper around the native HTML5 <dialog> element.
 * Features:
 * - Scroll Locking: Prevents body scrolling while the modal is active.
 * - Focus Management: Integrated Escape key handling for accessibility (a11y).
 * - Smooth Transitions: Orchestrates backdrop and content animations.
 */
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  maxWidth: {
    type: String,
    default: '2xl'
  },
  closeable: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

const openModal = () => {
  document.body.style.overflow = 'hidden';
  showSlot.value = true;
  dialog.value?.showModal();
};

const closeModal = () => {
  document.body.style.overflow = '';
  setTimeout(() => {
    dialog.value?.close();
    showSlot.value = false;
  }, 200);
};

// Handle subsequent prop changes
watch(
  () => props.show,
  (show) => {
    if (show) openModal();
    else closeModal();
  }
);

// Handle initial state on mount
onMounted(() => {
  if (props.show) openModal();

  // Accessibility: Existing keydown listener
  document.addEventListener('keydown', closeOnEscape);
});

/**
 * Sync logic between Vue state and native <dialog> state.
 * Manages body overflow to provide a seamless 'locked' experience.
 */
watch(
  () => props.show,
  () => {
    if (props.show) {
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
      showSlot.value = true;

      dialog.value?.showModal(); // Use native browser modal rendering
    } else {
      document.body.style.overflow = '';
      // Delay closing to allow the leave transitions to complete
      setTimeout(() => {
        dialog.value?.close();
        showSlot.value = false;
      }, 200);
    }
  }
);

const close = () => {
  if (props.closeable) {
    emit('close');
  }
};

/**
 * Handle Escape key specifically for modal context.
 * Prevents the default browser behavior to ensure our 'close' emit logic runs.
 */
const closeOnEscape = (e) => {
  if (e.key === 'Escape') {
    e.preventDefault();

    if (props.show) {
      close();
    }
  }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape);

  document.body.style.overflow = ''; // Ensure scroll is restored if component unmounts
});

const maxWidthClass = computed(() => {
  return {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl'
  }[props.maxWidth];
});
</script>

<template>
  <dialog
    class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
    ref="dialog"
  >
    <!-- Scroll region allows for internal modal scrolling on small devices -->
    <div
      class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
      scroll-region
    >
      <!-- Backdrop: Fades in/out -->
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="show"
          class="fixed inset-0 transform transition-all"
          @click="close"
        >
          <div
            class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"
          />
        </div>
      </Transition>
      <!-- Modal Content: Scales and slides in/out -->
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          v-show="show"
          class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full dark:bg-gray-800"
          :class="maxWidthClass"
        >
          <slot v-if="showSlot" />
        </div>
      </Transition>
    </div>
  </dialog>
</template>
