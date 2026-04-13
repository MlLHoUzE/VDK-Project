<script setup>
/**
 * Profile Information Component
 *
 * Facilitates updates to the user's core identity data.
 * Features:
 * - Global State Integration: Hydrates form directly from Inertia's shared auth props.
 * - Verification Logic: Conditionally displays email re-verification triggers.
 * - Reactive UX: Provides immediate feedback via Inertia's processing and success states.
 */
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
  // Determines if the application requires email verification for profile updates
  mustVerifyEmail: {
    type: Boolean
  },
  // Status message for successful email re-dispatch
  status: {
    type: String
  }
});

// Accessing the globally shared authenticated user data provided by Inertia middleware
const user = usePage().props.auth.user;

/**
 * Initialize form with existing user data.
 * This ensures the form is 'hydrated' immediately on component mount.
 */
const form = useForm({
  name: user.name,
  email: user.email
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Profile Information
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Update your account's profile information and email address.
      </p>
    </header>

    <form
      @submit.prevent="form.patch(route('profile.update'))"
      class="mt-6 space-y-6"
    >
      <div>
        <InputLabel for="name" value="Name" />

        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <!-- 
                 Verification Workflow: 
                 Displayed only if the system requires verification and the user 
                 has not yet completed the handshake. 
            -->
      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
          >
            Click here to re-send the verification email.
          </Link>
        </p>
        <!-- Feedback specific to the re-verification link trigger -->
        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        <!-- 
                    Visual Confirmation: 
                    Uses a standard fade transition to provide non-intrusive 
                    feedback that changes have been persisted. 
                -->
        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600 dark:text-gray-400"
          >
            Saved.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
