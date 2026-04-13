<script setup>
/**
 * Public Vehicle Gallery
 *
 * The primary landing page for guest users to browse published inventory.
 * Features:
 * - Localized Canadian currency formatting (CAD).
 * - Responsive 'Article' grid with hover lift effects.
 * - Dynamic empty-state handling for new installations.
 */
import VehicleCard from '@/Components/VehicleCard.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  // Filtered collection of only 'published' vehicles from the backend
  vehicles: {
    type: Array,
    default: () => []
  }
});

// Localization: Formats to Canadian English standards (CAD $)
const formatPrice = (value) =>
  new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: 'CAD',
    maximumFractionDigits: 0
  }).format(value ?? 0);
</script>

<template>
  <Head title="Vehicle Gallery" />

  <GuestLayout>
    <div class="mb-8 flex items-end justify-between gap-6">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">
          Find Your Next Car
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
          Browse our published inventory and open any listing for detailed
          specs.
        </p>
      </div>
      <!-- UX Shortcut: Allows staff to jump straight to admin if they are logged in -->
      <Link
        v-if="$page.props.auth.user"
        :href="route('admin.vehicles.index')"
        class="rounded-md border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 dark:border-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300"
      >
        Manage Inventory
      </Link>
    </div>

    <div
      v-if="vehicles.length"
      class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3"
    >
      <VehicleCard
        v-for="vehicle in vehicles"
        :key="vehicle.id"
        :vehicle="vehicle"
        :is-admin="false"
      />
    </div>

    <div
      v-else
      class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
    >
      No vehicles are published yet. Add your first listing from the admin
      panel.
    </div>
  </GuestLayout>
</template>
