<script setup>
/**
 * Reset Password View
 *
 * The final step of the recovery flow. It uses a unique token provided
 * by the email link to authorize the password change for a specific user.
 */
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  // The pre-filled email from the reset link for user convenience
  email: {
    type: String,
    required: true
  },
  // The unique string required by Laravel to verify the reset request
  token: {
    type: String,
    required: true
  }
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: ''
});

/**
 * Handle password update.
 *
 * Security: Clears the reactive password state immediately after
 * the request to ensure no sensitive data remains in memory.
 */
const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation')
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

    <form @submit.prevent="submit">
      <!-- Email: Pre-filled but editable in case of typos in the reset link -->
      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>
      <!-- New Password Entry -->
      <div class="mt-4">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>
      <!-- Confirmation: Standard practice to prevent typos in new credentials -->
      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <!-- Submission state handling prevents concurrent reset attempts -->
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Reset Password
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
