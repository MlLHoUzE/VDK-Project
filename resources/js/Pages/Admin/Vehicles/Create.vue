<script setup>
/**
 * Create Vehicle Page
 *
 * Acts as a container for the Vehicle Form in a 'Create' context.
 * By passing configuration props to the generic <Form /> component,
 * we keep the creation logic decoupled from the form UI.
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Form from './Form.vue';
</script>

<template>
  <Head title="Add Vehicle" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Add Vehicle
      </h2>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- 
                     The Form component is configured here for 'Creation' mode.
                     - submit-route: Uses the 'store' route for persistence.
                     - submit-method: Explicitly set to 'post' for resource creation.
                -->
        <Form
          :submit-route="route('admin.vehicles.store')"
          submit-method="post"
          submit-label="Create Vehicle"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
