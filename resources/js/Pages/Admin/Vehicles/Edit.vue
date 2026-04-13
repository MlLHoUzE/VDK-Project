<script setup>
/**
 * Edit Vehicle Page
 *
 * Injects existing vehicle data into the shared Form component.
 * Features 'returnView' logic to ensure the user returns to their
 * preferred layout (List/Gallery) after the update is complete.
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Form from './Form.vue';

const props = defineProps({
  // The specific vehicle record to be edited
  vehicle: {
    type: Object,
    required: true
  },
  /**
   * Captures the view mode (list/gallery) from the previous screen.
   * Passed back to the index route to preserve user preference.
   */
  returnView: {
    type: String,
    default: 'list'
  }
});
</script>

<template>
  <Head title="Edit Vehicle" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <h2
          class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
        >
          Edit Vehicle
        </h2>
        <div class="flex items-center gap-3">
          <!-- 
                         State-Aware Back Button: 
                         Appends the view preference to the URL to prevent UX 'reset'.
                    -->
          <Link
            :href="route('admin.vehicles.index', { view: props.returnView })"
            class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
          >
            Back
          </Link>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- 
                     The Form component is configured here for 'Update' mode.
                     - :vehicle: Passes existing data for form hydration.
                     - submit-method: Uses 'put' (RESTful standard for updates).
                -->
        <Form
          :vehicle="vehicle"
          :submit-route="route('admin.vehicles.update', vehicle.id)"
          submit-method="put"
          submit-label="Update Vehicle"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
