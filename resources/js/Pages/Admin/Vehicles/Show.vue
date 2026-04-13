<script setup>
/**
 * Vehicle Detail View (Admin)
 *
 * Provides a read-only deep dive into vehicle specifications.
 * Features:
 * - Responsive split-layout (Image vs. Specs).
 * - State-aware 'Back' navigation to preserve user's previous view mode.
 * - Robust data formatting for currency and numeric values.
 */
import VehicleDetailsCard from '@/Components/VehicleDetailsCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  vehicle: {
    type: Object,
    required: true
  },
  // Preserves 'list' vs 'gallery' layout when returning to index
  returnView: {
    type: String,
    default: 'list'
  }
});
/**
 * Utility: Formats price to USD.
 * Defaults to $0 if no price is present to prevent layout shift.
 */
const formatPrice = (value) =>
  new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: 'CAD',
    maximumFractionDigits: 0
  }).format(value ?? 0);
</script>

<template>
  <Head :title="props.vehicle.title" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <h2
          class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
        >
          Vehicle Details
        </h2>
        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.vehicles.index', { view: props.returnView })"
            class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
          >
            Back
          </Link>
          <Link
            :href="route('admin.vehicles.edit', props.vehicle.id)"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500"
          >
            Edit
          </Link>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- 
             Responsive Grid: 
             5-column span allows for a 60/40 visual split on large screens 
             (3 cols for image, 2 cols for specs). 
        -->
        <VehicleDetailsCard :vehicle="vehicle" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
