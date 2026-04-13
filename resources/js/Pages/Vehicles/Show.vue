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
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  vehicle: {
    type: Object,
    required: true
  }
});

/**
 * Utility: Formats price to CAD standards.
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

    <div class="grid gap-8 lg:grid-cols-5">
      <!-- 
          Main Media: 
          Uses 'lg:h-full' to ensure the image balances visually 
          with the height of the details card on desktop. 
      -->
      <img
        :src="
          props.vehicle.image_path ||
          'https://placehold.co/1200x750?text=Vehicle+Photo'
        "
        :alt="props.vehicle.title"
        class="h-72 w-full rounded-xl object-cover lg:col-span-3 lg:h-full"
      />

      <section
        class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-800 lg:col-span-2"
      >
        <div>
          <h1 class="text-2xl font-semibold sm:text-3xl">
            {{ props.vehicle.title }}
          </h1>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            {{ props.vehicle.year }} {{ props.vehicle.make }}
            {{ props.vehicle.model }}
          </p>
        </div>
        <p class="text-3xl font-semibold text-indigo-600 dark:text-indigo-400">
          {{ formatPrice(props.vehicle.price) }}
        </p>
        <!-- 
          Technical Specs: 
          Uses <dl> for semantic correctness. 
          Strict number checking prevents '0' from being hidden as 'N/A'.
        -->
        <dl class="space-y-2 text-sm">
          <div class="flex justify-between gap-4">
            <dt class="text-gray-900 dark:text-gray-100 font-medium">
              Mileage
            </dt>
            <dd class="text-gray-900 dark:text-gray-100 font-medium">
              {{
                typeof props.vehicle.mileage === 'number'
                  ? `${props.vehicle.mileage.toLocaleString()} mi`
                  : 'N/A'
              }}
            </dd>
          </div>
          <div class="flex justify-between gap-4">
            <dt class="text-gray-900 dark:text-gray-100 font-medium">
              Transmission
            </dt>
            <dd class="text-gray-900 dark:text-gray-100 font-medium">
              {{ props.vehicle.transmission || 'N/A' }}
            </dd>
          </div>
          <div class="flex justify-between gap-4">
            <dt class="text-gray-900 dark:text-gray-100 font-medium">
              Fuel Type
            </dt>
            <dd class="text-gray-900 dark:text-gray-100 font-medium">
              {{ props.vehicle.fuel_type || 'N/A' }}
            </dd>
          </div>
          <div class="flex justify-between gap-4">
            <dt class="text-gray-900 dark:text-gray-100 font-medium">Color</dt>
            <dd class="text-gray-900 dark:text-gray-100 font-medium">
              {{ props.vehicle.color || 'N/A' }}
            </dd>
          </div>
        </dl>
        <div>
          <h2
            class="text-sm font-semibold uppercase tracking-wide text-gray-900 dark:text-gray-100 font-medium"
          >
            Description
          </h2>
          <p
            class="mt-2 text-sm leading-6 text-gray-900 dark:text-gray-100 font-medium"
          >
            {{
              props.vehicle.description || 'No additional description provided.'
            }}
          </p>
        </div>
      </section>
    </div>
  </GuestLayout>
</template>
