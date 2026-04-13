/**
 * Frontend Bootstrapper
 *
 * Configures global libraries and defaults for the application's
 * networking layer.
 */
import axios from 'axios';
window.axios = axios;

/**
 * Configure Axios to identify all requests as AJAX.
 * This ensures Laravel's middleware correctly identifies Inertia requests
 * and handles CSRF/Session logic automatically.
 */
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
