/**
 * Application Entry Point
 *
 * Bootstraps the Inertia.js application with Vue 3.
 * Configures:
 * - Dynamic component resolution (code splitting).
 * - ZiggyVue for shared Laravel routing.
 * - Global progress indicators for navigation.
 */
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  // Dynamic page titles (e.g., "Add Vehicle - Hammond Motors")
  title: (title) => `${title} - ${appName}`,

  // Resolves Vue components from the /Pages directory automatically
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    // Initializes the Vue instance with Inertia and Ziggy (routing) plugins
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
  // Global loading bar configuration (visible during Inertia navigation)
  progress: {
    color: '#4B5563' // Matches gray-600 in Tailwind
  }
});
