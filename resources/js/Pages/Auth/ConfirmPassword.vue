<script setup>
/**
 * Password Confirmation View
 *
 * Acts as a security 'gate' for sensitive actions (e.g., profile deletion or
 * accessing high-level admin settings).
 * Leverages Laravel's 'password.confirm' middleware logic.
 */
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  password: ''
});

/**
 * Submit the confirmation request.
 * Resets the password field on finish regardless of success/failure
 * for improved security and UX.
 */
const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset()
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      This is a secure area of the application. Please confirm your password
      before continuing.
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="password" value="Password" />
        <!-- 
          TextInput Configuration:
          - autofocus: Improves UX by readying the field immediately.
          - autocomplete: Helps password managers identify the field correctly.
        -->
        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="current-password"
          autofocus
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 flex justify-end">
        <!-- 
          State-Aware Button: 
          Visual feedback (opacity) and disabled state prevent 
          unnecessary multiple POST requests.
        -->
        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Confirm
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
