<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { PlusIcon, SquareArrowRightExit } from 'lucide-vue-next';
import { watch } from 'vue';
import { useBreakpoints, breakpointsTailwind } from '@vueuse/core';
import VehicleCard from '@/Components/VehicleCard.vue';
import { formatPrice } from '@/Utils/formatters';

/**
 * Admin Vehicle Management Dashboard
 *
 * Handles CRUD operations, status toggling, and layout switching (List/Gallery).
 * Uses Inertia.js for state preservation and VueUse for responsive behaviors.
 */

defineProps({
  // Main collection of vehicle objects from the backend
  vehicles: {
    type: Array,
    default: () => []
  },
  // Default view mode passed from the controller via query params
  initialView: {
    type: String,
    default: 'list'
  }
});

const page = usePage();
const viewMode = ref(page.props.initialView === 'gallery' ? 'gallery' : 'list');
const confirmModalOpen = ref(false);
const confirmAction = ref(null);
const selectedVehicle = ref(null);

const breakpoints = useBreakpoints(breakpointsTailwind);
const isMobile = breakpoints.smaller('md');

// UX: Force Gallery view on mobile as the table layout (List)
// becomes cramped and difficult to navigate on small screens.
watch(
  isMobile,
  (isNowMobile) => {
    if (isNowMobile) {
      viewMode.value = 'gallery';
    }
  },
  { immediate: true }
);

// Synchronization: Keep the URL query params in sync with the local viewMode state.
// This allows users to share specific views or use the browser's back button.
watch(viewMode, (newMode) => {
  router.get(
    route(route().current()),
    { view: newMode },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true
    }
  );
});

/**
 * Generic Modal Logic
 * Instead of multiple modals, we use a single 'confirmAction' state
 * to handle both 'delete' and 'toggle-published' workflows.
 */
const openConfirmModal = (action, vehicle) => {
  confirmAction.value = action;
  selectedVehicle.value = vehicle;
  confirmModalOpen.value = true;
};

const closeConfirmModal = () => {
  confirmModalOpen.value = false;
  confirmAction.value = null;
  selectedVehicle.value = null;
};

const destroyVehicle = (vehicle) => {
  openConfirmModal('delete', vehicle);
};

const togglePublished = (vehicle) => {
  openConfirmModal('toggle-published', vehicle);
};

// Computed-like helpers to dynamically generate Modal content based on the active action
const confirmTitle = () => {
  if (!selectedVehicle.value) return '';
  return confirmAction.value === 'delete'
    ? 'Delete listing'
    : 'Update publish status';
};

const confirmMessage = () => {
  if (!selectedVehicle.value) return '';

  if (confirmAction.value === 'delete') {
    return `Delete "${selectedVehicle.value.title}"? This action cannot be undone.`;
  }

  if (selectedVehicle.value.is_published) {
    return 'Move this listing to draft? It will be hidden from the public gallery.';
  }

  return 'Publish this listing? It will be visible in the public gallery.';
};

const confirmButtonLabel = () => {
  if (!selectedVehicle.value) return 'Confirm';
  if (confirmAction.value === 'delete') return 'Delete';
  return selectedVehicle.value.is_published ? 'Move to Draft' : 'Publish';
};

const isDestructiveConfirm = () => confirmAction.value === 'delete';

const submitConfirmAction = () => {
  if (!selectedVehicle.value) {
    closeConfirmModal();
    return;
  }

  if (confirmAction.value === 'delete') {
    router.delete(route('admin.vehicles.destroy', selectedVehicle.value.id), {
      onFinish: closeConfirmModal
    });
    return;
  }

  router.patch(
    route('admin.vehicles.toggle-published', selectedVehicle.value.id),
    {},
    {
      onFinish: closeConfirmModal
    }
  );
};
</script>

<template>
  <Head title="Manage Vehicles" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <h2
          class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
        >
          Manage Vehicles
        </h2>
        <div class="flex items-center gap-3">
          <div
            class="inline-flex rounded-md border border-gray-300 bg-white p-1 dark:border-gray-600 dark:bg-gray-800"
            :class="{ 'opacity-80': isMobile }"
          >
            <!-- List Button -->
            <button
              type="button"
              @click="!isMobile && (viewMode = 'list')"
              class="rounded px-3 py-1.5 text-xs font-semibold uppercase tracking-wide transition"
              :class="[
                viewMode === 'list'
                  ? 'bg-indigo-600 text-white'
                  : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
                isMobile
                  ? 'pointer-events-none cursor-default'
                  : 'cursor-pointer'
              ]"
            >
              List
            </button>

            <!-- Gallery Button -->
            <button
              type="button"
              @click="!isMobile && (viewMode = 'gallery')"
              class="rounded px-3 py-1.5 text-xs font-semibold uppercase tracking-wide transition"
              :class="[
                viewMode === 'gallery'
                  ? 'bg-indigo-600 text-white'
                  : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
                isMobile
                  ? 'pointer-events-none cursor-default'
                  : 'cursor-pointer'
              ]"
            >
              Gallery
            </button>
          </div>
          <!-- Responsive Action Buttons:
            Morphs from a fixed-size circular FAB (Floating Action Button) on mobile 
            to a standard rectangular button on desktop. -->
          <PrimaryButton
            @click="$inertia.visit(route('admin.vehicles.create'))"
            class="flex items-center justify-center !rounded-full w-12 h-12 md:w-auto md:h-auto md:rounded-md md:px-4 md:py-2"
          >
            <!-- "+" Icon (Mobile Only) -->
            <PlusIcon class="text-xl md:hidden" />

            <!-- "Add Vehicle" Text (Medium Screens+) -->
            <span class="hidden md:inline">Add Vehicle</span>
          </PrimaryButton>
          <SecondaryButton
            @click="$inertia.visit(route('vehicles.index'))"
            class="flex items-center justify-center !rounded-full w-12 h-12 md:w-auto md:h-auto md:rounded-md md:px-4 md:py-2"
          >
            <!-- "+" Icon (Mobile Only) -->
            <SquareArrowRightExit class="text-xl md:hidden" />

            <!-- "Add Vehicle" Text (Medium Screens+) -->
            <span class="hidden md:inline">Exit Admin</span>
          </SecondaryButton>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
          v-if="page.props.flash.success"
          class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
        >
          {{ page.props.flash.success }}
        </div>

        <div
          v-if="viewMode === 'list'"
          class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow dark:border-gray-800 dark:bg-gray-800"
        >
          <table
            class="min-w-full divide-y divide-gray-200 text-gray-900 dark:divide-gray-700 dark:text-gray-100"
          >
            <thead class="bg-gray-50 dark:bg-gray-900">
              <tr>
                <th
                  class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300"
                >
                  Vehicle
                </th>
                <th
                  class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300"
                >
                  Price
                </th>
                <th
                  class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300"
                >
                  Published
                </th>
                <th
                  class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr v-for="vehicle in vehicles" :key="vehicle.id">
                <td class="px-4 py-4 text-sm">
                  <p class="font-medium">{{ vehicle.title }}</p>
                  <p class="text-gray-500 dark:text-gray-400">
                    {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
                  </p>
                </td>
                <td class="px-4 py-4 text-sm">
                  {{ formatPrice(vehicle.price) }}
                </td>
                <td class="px-4 py-4 text-sm">
                  <span
                    :class="
                      vehicle.is_published
                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                        : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200'
                    "
                    class="rounded-full px-2 py-1 text-xs font-medium"
                  >
                    {{ vehicle.is_published ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td class="px-4 py-4 text-right text-sm">
                  <div class="inline-flex items-center gap-3">
                    <Link
                      :href="
                        route('admin.vehicles.show', {
                          vehicle: vehicle.id,
                          view: viewMode
                        })
                      "
                      class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    >
                      View
                    </Link>
                    <Link
                      :href="
                        route('admin.vehicles.edit', {
                          vehicle: vehicle.id,
                          view: viewMode
                        })
                      "
                      class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                      Edit
                    </Link>
                    <button
                      type="button"
                      @click="togglePublished(vehicle)"
                      class="text-sky-600 hover:text-sky-500 dark:text-sky-400 dark:hover:text-sky-300"
                    >
                      {{ vehicle.is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                    <button
                      @click="destroyVehicle(vehicle)"
                      type="button"
                      class="text-rose-600 hover:text-rose-500 dark:text-rose-400 dark:hover:text-rose-300"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!vehicles.length">
                <td
                  colspan="4"
                  class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-300"
                >
                  No vehicles yet. Add your first listing.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div
          v-else-if="vehicles.length"
          class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3"
        >
          <VehicleCard
            v-for="vehicle in vehicles"
            :key="vehicle.id"
            :vehicle="vehicle"
            :is-admin="true"
            :view-mode="viewMode"
            @toggle-published="togglePublished"
            @delete="destroyVehicle"
          />
        </div>
        <div
          v-else
          class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
        >
          No vehicles yet. Add your first listing.
        </div>
      </div>
    </div>

    <Modal :show="confirmModalOpen" max-width="md" @close="closeConfirmModal">
      <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
          {{ confirmTitle() }}
        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
          {{ confirmMessage() }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
          <SecondaryButton @click="closeConfirmModal">Cancel</SecondaryButton>
          <DangerButton
            v-if="isDestructiveConfirm()"
            @click="submitConfirmAction"
          >
            {{ confirmButtonLabel() }}
          </DangerButton>
          <PrimaryButton v-else @click="submitConfirmAction">
            {{ confirmButtonLabel() }}
          </PrimaryButton>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
