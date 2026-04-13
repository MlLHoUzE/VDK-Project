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
        <div class="grid gap-8 lg:grid-cols-5">
          <!-- Vehicle Image with dynamic placeholder fallback -->
          <img
            :src="
              props.vehicle.image_path ||
              'https://placehold.co/1200x750?text=Vehicle+Photo'
            "
            :alt="props.vehicle.title"
            class="h-72 w-full rounded-xl object-cover lg:col-span-3 lg:h-full"
          />

          <!-- Specifications Card -->
          <section
            class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-800 lg:col-span-2"
          >
            <div>
              <h1 class="text-2xl font-semibold sm:text-3xl dark:text-gray-300">
                {{ props.vehicle.title }}
              </h1>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                {{ props.vehicle.year }} {{ props.vehicle.make }}
                {{ props.vehicle.model }}
              </p>
            </div>
            <p
              class="text-3xl font-semibold text-indigo-600 dark:text-indigo-400"
            >
              {{ formatPrice(props.vehicle.price) }}
            </p>
            <!-- Data List: Key/Value pairs for vehicle technical specs -->
            <dl class="space-y-2 text-sm">
              <!-- 
                    Logic: Uses strict 'number' check to ensure '0' mileage 
                    is rendered correctly instead of falling back to 'N/A'.
               -->
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
                <dt class="text-gray-900 dark:text-gray-100 font-medium">
                  Color
                </dt>
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
                  props.vehicle.description ||
                  'No additional description provided.'
                }}
              </p>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
