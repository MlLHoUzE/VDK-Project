<script setup>
/**
 * Public Vehicle Detail View
 *
 * The final destination for potential buyers. Focuses on high-impact
 * imagery and clear technical specifications.
 *
 * Features:
 * - Localized Canadian English (CAD) formatting.
 * - Responsive split-panel design for Specs vs. Media.
 * - Robust fallback logic for incomplete data.
 */
import VehicleDetailsCard from '@/Components/VehicleDetailsCard.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  vehicle: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <Head :title="props.vehicle.title" />

  <GuestLayout>
    <!-- Navigation: Simple, high-contrast back link for easy catalog return -->
    <div class="mb-6">
      <Link
        :href="route('vehicles.index')"
        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
      >
        ← Back to inventory
      </Link>
    </div>

    <VehicleDetailsCard :vehicle="vehicle" />
  </GuestLayout>
</template>
