<script setup>
/**
 * Guest/Public Layout
 *
 * Provides the main shell for public-facing pages, including:
 * - Shared navigation header with conditional Auth links.
 * - Consistent max-width container and padding.
 * - Dark mode support with smooth background transitions.
 */
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div
    class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100"
  >
    <!-- Main Navigation: Glassmorphism effect used for a modern UI feel -->
    <header
      class="border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90"
    >
      <div
        class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
      >
        <!-- Branding -->
        <Link href="/" class="flex items-center gap-3">
          <ApplicationLogo
            class="h-10 w-10 fill-current text-indigo-600 dark:text-indigo-400"
          />
          <span class="text-lg font-semibold">Hammond Motors</span>
        </Link>

        <!-- Navigation: Conditional rendering based on Authentication state -->
        <nav class="flex items-center gap-2 sm:gap-3">
          <Link
            v-if="$page.props.auth.user"
            :href="route('vehicles.index')"
            class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
          >
            Gallery
          </Link>
          <!-- Admin Link: Visible only to logged-in users (Dashboard Entry) -->
          <Link
            v-if="$page.props.auth.user"
            :href="route('admin.vehicles.index')"
            class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
          >
            Admin
          </Link>
          <!-- Guest Actions: Shown only when user is not authenticated -->
          <template v-else>
            <Link
              :href="route('login')"
              class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
            >
              Login
            </Link>
            <Link
              :href="route('register')"
              class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500"
            >
              Register
            </Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Page Content Injection Point -->
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <slot />
    </div>
  </div>
</template>
