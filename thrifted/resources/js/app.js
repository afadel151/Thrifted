import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ToastService from 'primevue/toastservice';
import Aura from '@primevue/themes/aura';
import PrimeVue from 'primevue/config';
import 'primeicons/primeicons.css'
import "v3-infinite-loading/lib/style.css";
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    cssLayer: false
                }
            })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

