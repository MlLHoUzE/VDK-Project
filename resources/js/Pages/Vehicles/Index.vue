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
      <article
        v-for="vehicle in vehicles"
        :key="vehicle.id"
        class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-800"
      >
        <!-- 
        The 'Big Click' Pattern: 
        The absolute Link covers the entire card for better mobile UX, 
        while aria-label ensures screen readers understand the target.
      -->
        <Link
          :href="route('vehicles.show', vehicle.id)"
          class="absolute inset-0 z-10 rounded-xl"
          :aria-label="`View details for ${vehicle.title}`"
        />
        <!-- Image with fallback -->
        <img
          :src="
            vehicle.image_path ||
            'https://placehold.co/800x500?text=Vehicle+Photo'
          "
          :alt="vehicle.title"
          class="h-52 w-full object-cover"
        />
        <div class="space-y-3 p-4">
          <div class="flex items-start justify-between gap-3">
            <h2 class="text-lg font-semibold">{{ vehicle.title }}</h2>
            <span
              class="text-lg font-semibold text-indigo-600 dark:text-indigo-400"
            >
              {{ formatPrice(vehicle.price) }}
            </span>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
          </p>
          <!-- Tag Cloud: Technical specs presented as quick-read badges -->
          <div
            class="flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-300"
          >
            <span
              class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80"
            >
              {{
                typeof vehicle.mileage === 'number'
                  ? `${vehicle.mileage.toLocaleString()} mi`
                  : 'N/A'
              }}
            </span>
            <span
              class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80"
            >
              {{ vehicle.transmission || 'Transmission N/A' }}
            </span>
            <span
              class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80"
            >
              {{ vehicle.fuel_type || 'Fuel N/A' }}
            </span>
          </div>
          <!-- Secondary Link: Provides a clear visual call-to-action (CTA) -->
          <Link
            :href="route('vehicles.show', vehicle.id)"
            class="relative z-20 inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
          >
            View Details
          </Link>
        </div>
      </article>
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
