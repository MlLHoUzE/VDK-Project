<script setup>
/**
 * Email Verification View
 *
 * An interstitial state for newly registered users.
 * Prevents access to protected routes until the email address is confirmed,
 * while allowing a safe 'Log Out' path to exit the flow.
 */
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  // Backend status message returned after a successful email dispatch
  status: {
    type: String
  }
});

const form = useForm({});

/**
 * Request a fresh verification link.
 * Leverages Laravel's built-in Email Verification notification system.
 */
const submit = () => {
  form.post(route('verification.send'));
};

/**
 * Computed check for specific success state.
 * Ensures the 'Link Sent' message only appears after an explicit request.
 */
const verificationLinkSent = computed(
  () => props.status === 'verification-link-sent'
);
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />

    <!-- Informational text to guide the user through the onboarding funnel -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      Thanks for signing up! Before getting started, could you verify your email
      address by clicking on the link we just emailed to you? If you didn't
      receive the email, we will gladly send you another.
    </div>

    <!-- 
        Success Feedback:
        Uses the computed 'verificationLinkSent' to provide reactive 
        confirmation once a new link is dispatched.
    -->
    <div
      class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
      v-if="verificationLinkSent"
    >
      A new verification link has been sent to the email address you provided
      during registration.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <!-- 
            Processing state prevents spamming the mail server 
            with multiple concurrent requests.
        -->
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Resend Verification Email
        </PrimaryButton>

        <!-- 
            UX Escape Hatch: 
            Allows users to log out if they used the wrong email 
            or wish to finish verification later.
        -->
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
          >Log Out</Link
        >
      </div>
    </form>
  </GuestLayout>
</template>
