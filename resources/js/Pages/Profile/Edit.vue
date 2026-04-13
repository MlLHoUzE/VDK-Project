<script setup>
/**
 * User Settings View (Orchestrator)
 *
 * Aggregates specialized partials for profile, security, and account management.
 * By decoupling these forms into partials, we ensure high maintainability
 * and isolate the validation logic for each specific domain.
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  // Props forwarded to the UpdateProfileInformationForm for email logic
  mustVerifyEmail: {
    type: Boolean
  },
  status: {
    type: String
  }
});
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Profile
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <!-- 
            Section Wrapper:
            Uses consistent padding, shadows, and dark-mode support 
            to house each specialized setting form. 
        -->
        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </div>
        <!-- Part 2: Security Settings (Password Rotation) -->
        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
          <UpdatePasswordForm class="max-w-xl" />
        </div>
        <!-- Part 3: Danger Zone (Account Deletion) -->
        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
