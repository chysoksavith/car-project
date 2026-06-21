import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Toast, { PluginOptions, POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/app.css';
import { route } from 'ziggy-js';
import { can as canDirective } from './Directives/can';

// Make route() available globally (used by composables without explicit import)
(window as any).route = route;

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const toastOptions: PluginOptions = {
    position: POSITION.TOP_RIGHT,
    timeout: 3000,
    closeOnClick: true,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Global can() method for v-if="can('...')" or script usage
        app.config.globalProperties.can = function (permission: string | string[]) {
            const user = this.$page?.props?.auth?.user;
            if (!user || !user.permissions) return false;
            if (user.roles?.includes('super-admin')) return true;
            
            if (Array.isArray(permission)) {
                return permission.some(p => user.permissions.includes(p));
            }
            return user.permissions.includes(permission);
        };

        // Global route() method
        app.config.globalProperties.route = route;

        // Global v-can directive 
        app.directive('can', canDirective);

        app.use(plugin)
            .use(Toast, toastOptions)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
