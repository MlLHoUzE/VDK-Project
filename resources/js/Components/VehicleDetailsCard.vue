<script setup>
import { router } from '@inertiajs/vue3';
import { formatPrice } from '@/Utils/formatters';

/**
 * Vehicle Details Card (Full Layout)
 *
 * Handles both the image and the specifications.
 * Supports Admin-only status badges and toggle controls.
 */
const props = defineProps({
  vehicle: { type: Object, required: true }
});
</script>

<template>
  <div class="grid gap-8 lg:grid-cols-5">
    <!-- Part 1: Vehicle Image (Takes up 3 columns on large screens) -->
    <div class="lg:col-span-3">
      <img
        :src="
          vehicle.image_path ||
          'https://placehold.co/1200x750?text=Vehicle+Photo'
        "
        :alt="vehicle.title"
        class="h-72 w-full rounded-xl object-cover lg:h-full shadow-sm border border-gray-100 dark:border-gray-800"
      />
    </div>

    <!-- Part 2: Specifications (Takes up 2 columns on large screens) -->
    <section
      class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-800 lg:col-span-2"
    >
      <!-- Title & Pricing -->
      <div>
        <h1
          class="text-2xl font-bold sm:text-3xl text-gray-900 dark:text-gray-100"
        >
          {{ vehicle.title }}
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
        </p>
        <p class="mt-4 text-3xl font-bold text-indigo-600 dark:text-indigo-400">
          {{ formatPrice(vehicle.price) }}
        </p>
      </div>

      <!-- Specs Definition List -->
      <dl class="space-y-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500 dark:text-gray-400 font-medium">Mileage</dt>
          <dd class="text-gray-900 dark:text-gray-100 font-semibold">
            {{
              typeof vehicle.mileage === 'number'
                ? `${vehicle.mileage.toLocaleString()} mi`
                : 'N/A'
            }}
          </dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500 dark:text-gray-400 font-medium">
            Transmission
          </dt>
          <dd class="text-gray-900 dark:text-gray-100 font-semibold">
            {{ vehicle.transmission || 'N/A' }}
          </dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500 dark:text-gray-400 font-medium">
            Fuel Type
          </dt>
          <dd class="text-gray-900 dark:text-gray-100 font-semibold">
            {{ vehicle.fuel_type || 'N/A' }}
          </dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500 dark:text-gray-400 font-medium">Color</dt>
          <dd class="text-gray-900 dark:text-gray-100 font-semibold">
            {{ vehicle.color || 'N/A' }}
          </dd>
        </div>
      </dl>

      <!-- Description -->
      <div class="pt-4">
        <h2
          class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400"
        >
          Description
        </h2>
        <p class="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-300">
          {{ vehicle.description || 'No additional description provided.' }}
        </p>
      </div>
    </section>
  </div>
</template>
