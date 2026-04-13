<script setup>
/**
 * Vehicle Resource Form
 *
 * A reusable form component used for both creating and updating vehicles.
 * Implements the Inertia useForm helper for reactive validation and
 * simplified submission handling.
 */
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  // Optional vehicle object used to hydrate the form in 'Edit' mode
  vehicle: {
    type: Object,
    default: null
  },
  // Route for persistence (e.g., admin.vehicles.store or update)
  submitRoute: {
    type: String,
    required: true
  },
  // REST method (POST for create, PUT for updates)
  submitMethod: {
    type: String,
    default: 'post'
  },
  submitLabel: {
    type: String,
    default: 'Save Vehicle'
  }
});
/**
 * Inertia Form Initialization
 * Uses nullish coalescing to safely hydrate from props or default to empty strings.
 */
const form = useForm({
  title: props.vehicle?.title ?? '',
  make: props.vehicle?.make ?? '',
  model: props.vehicle?.model ?? '',
  year: props.vehicle?.year ?? '',
  price: props.vehicle?.price ?? '',
  mileage: props.vehicle?.mileage ?? '',
  transmission: props.vehicle?.transmission ?? '',
  fuel_type: props.vehicle?.fuel_type ?? '',
  color: props.vehicle?.color ?? '',
  image_path: props.vehicle?.image_path ?? '',
  description: props.vehicle?.description ?? '',
  is_published: props.vehicle?.is_published ?? true
});

/**
 * Dynamic Submit Handler
 * Resolves the appropriate HTTP verb (POST vs PUT) based on component configuration.
 */
const submit = () => {
  if (props.submitMethod.toLowerCase() === 'put') {
    form.put(props.submitRoute);
    return;
  }

  form.post(props.submitRoute);
};
</script>

<template>
  <!-- .prevent modifier handles the event.preventDefault() automatically in Vue -->
  <form
    @submit.prevent="submit"
    class="space-y-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800"
  >
    <!-- Grid Layout: Responsive columns (1 on mobile, 2 on desktop) -->
    <div class="grid gap-4 md:grid-cols-2">
      <!-- Shared Input Pattern: Label -> Component -> Validation Error -->
      <div class="md:col-span-2">
        <InputLabel for="title" value="Listing Title" />
        <TextInput
          id="title"
          v-model="form.title"
          type="text"
          class="mt-1 block w-full"
          required
        />
        <!-- Dynamic server-side validation error display -->
        <InputError :message="form.errors.title" class="mt-2" />
      </div>

      <div>
        <InputLabel for="make" value="Make" />
        <TextInput
          id="make"
          v-model="form.make"
          type="text"
          class="mt-1 block w-full"
          required
        />
        <InputError :message="form.errors.make" class="mt-2" />
      </div>
      <div>
        <InputLabel for="model" value="Model" />
        <TextInput
          id="model"
          v-model="form.model"
          type="text"
          class="mt-1 block w-full"
          required
        />
        <InputError :message="form.errors.model" class="mt-2" />
      </div>
      <div>
        <InputLabel for="year" value="Year" />
        <TextInput
          id="year"
          v-model="form.year"
          type="number"
          class="mt-1 block w-full"
          required
        />
        <InputError :message="form.errors.year" class="mt-2" />
      </div>
      <div>
        <InputLabel for="price" value="Price (USD)" />
        <TextInput
          id="price"
          v-model="form.price"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-full"
          required
        />
        <InputError :message="form.errors.price" class="mt-2" />
      </div>
      <div>
        <InputLabel for="mileage" value="Mileage" />
        <TextInput
          id="mileage"
          v-model="form.mileage"
          type="number"
          min="0"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.mileage" class="mt-2" />
      </div>
      <div>
        <InputLabel for="fuel_type" value="Fuel Type" />
        <TextInput
          id="fuel_type"
          v-model="form.fuel_type"
          type="text"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.fuel_type" class="mt-2" />
      </div>
      <div>
        <InputLabel for="transmission" value="Transmission" />
        <TextInput
          id="transmission"
          v-model="form.transmission"
          type="text"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.transmission" class="mt-2" />
      </div>
      <div>
        <InputLabel for="color" value="Color" />
        <TextInput
          id="color"
          v-model="form.color"
          type="text"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.color" class="mt-2" />
      </div>

      <div class="md:col-span-2">
        <InputLabel for="image_path" value="Image URL" />
        <TextInput
          id="image_path"
          v-model="form.image_path"
          type="url"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.image_path" class="mt-2" />
      </div>
      <div class="md:col-span-2">
        <InputLabel for="description" value="Description" />
        <!-- Standard textarea styled to match the dark/light theme of custom components -->
        <textarea
          id="description"
          v-model="form.description"
          rows="4"
          class="mt-1 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
        />
        <InputError :message="form.errors.description" class="mt-2" />
      </div>
      <label class="md:col-span-2 inline-flex items-center gap-2 text-sm">
        <input
          v-model="form.is_published"
          type="checkbox"
          class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        />
        <span class="text-gray-700 dark:text-gray-300">
          Published (visible on public homepage)
        </span>
      </label>
    </div>

    <div class="flex items-center gap-3">
      <!-- form.processing prevents double-submits during the request lifecycle -->
      <PrimaryButton :disabled="form.processing">{{
        submitLabel
      }}</PrimaryButton>
      <!-- UX: Brief feedback message on successful background persistence -->
      <span v-if="form.recentlySuccessful" class="text-sm text-emerald-600"
        >Saved.</span
      >
    </div>
  </form>
</template>
