<script setup>
import { Link } from '@inertiajs/vue3';

/**
 * Unified Vehicle Card
 *
 * Supports two modes:
 * 1. Public Mode: The whole card is clickable.
 * 2. Admin Mode: Provides visible status badges and action buttons.
 */
const props = defineProps({
  vehicle: { type: Object, required: true },
  isAdmin: { type: Boolean, default: false },
  viewMode: { type: String, default: 'list' }
});

const emit = defineEmits(['toggle-published', 'delete']);

const formatPrice = (value) =>
  new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: 'CAD',
    maximumFractionDigits: 0
  }).format(value ?? 0);
</script>

<template>
  <article
    class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-800"
  >
    <!-- Public "Big Click" Link (Only if NOT admin) -->
    <Link
      v-if="!isAdmin"
      :href="route('vehicles.show', vehicle.id)"
      class="absolute inset-0 z-10"
      :aria-label="`View details for ${vehicle.title}`"
    />

    <Link
      v-else
      :href="
        route('admin.vehicles.show', { vehicle: vehicle.id, view: viewMode })
      "
      class="absolute inset-0 z-10"
    />

    <!-- Image Container -->
    <div class="relative h-52 w-full overflow-hidden">
      <img
        :src="
          vehicle.image_path ||
          'https://placehold.co/800x500?text=Vehicle+Photo'
        "
        :alt="vehicle.title"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
      />
    </div>

    <div class="space-y-3 p-4">
      <!-- Header: Title & Price -->
      <div class="flex items-start justify-between gap-3">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
          {{ vehicle.title }}
        </h3>
        <span
          class="text-lg font-semibold text-indigo-600 dark:text-indigo-400"
        >
          {{ formatPrice(vehicle.price) }}
        </span>
      </div>

      <p class="text-sm text-gray-600 dark:text-gray-300">
        {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
      </p>

      <!-- Tags / Specs -->
      <div
        class="flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-300"
      >
        <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80">
          {{
            typeof vehicle.mileage === 'number'
              ? `${vehicle.mileage.toLocaleString()} mi`
              : 'N/A'
          }}
        </span>
        <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80">
          {{ vehicle.transmission || 'N/A' }}
        </span>
        <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-700/80">
          {{ vehicle.fuel_type || 'N/A' }}
        </span>
      </div>

      <!-- View Details Button -->
      <Link
        :href="
          isAdmin
            ? route('admin.vehicles.show', {
                vehicle: vehicle.id,
                view: viewMode
              })
            : route('vehicles.show', vehicle.id)
        "
        class="relative z-20 inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
      >
        View Details
      </Link>

      <!-- Admin Controls Section -->
      <div
        v-if="isAdmin"
        class="relative z-20 border-t border-gray-200 pt-3 dark:border-gray-700"
      >
        <p
          class="mb-2 text-[11px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400"
        >
          Admin Controls
        </p>
        <div class="flex items-center justify-between gap-3">
          <span
            :class="
              vehicle.is_published
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200'
            "
            class="inline-flex rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-wider"
          >
            {{ vehicle.is_published ? 'Published' : 'Draft' }}
          </span>

          <div class="flex items-center gap-3 text-xs">
            <Link
              :href="route('admin.vehicles.edit', vehicle.id)"
              class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
            >
              Edit
            </Link>
            <button
              type="button"
              @click.stop="$emit('toggle-published', vehicle)"
              class="text-sky-600 hover:text-sky-500 dark:text-sky-400"
            >
              {{ vehicle.is_published ? 'Unpublish' : 'Publish' }}
            </button>
            <button
              type="button"
              @click.stop="$emit('delete', vehicle)"
              class="text-rose-600 hover:text-rose-500 dark:text-rose-400"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </article>
</template>
