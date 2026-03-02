import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import mdiVue from 'mdi-vue/v3';
import * as mdijs from '@mdi/js';
const appName = import.meta.env.VITE_APP_NAME;
import gsap from 'gsap';
import 'viewerjs/dist/viewer.css';
import VueViewer from 'v-viewer';

// Vue I18n
import { createI18n } from 'vue-i18n';

// GSAP plugins
import ScrollTrigger from 'gsap/ScrollTrigger';
import ScrollToPlugin from 'gsap/ScrollToPlugin';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Dapatkan terjemahan dan locale dari props Inertia
        const translations = props?.initialPage?.props?.translations || {};
        const locale = props?.initialPage?.props?.locale || localStorage.getItem('locale') || 'en';

        // Buat instance i18n dengan data yang diambil dari props
        const i18n = createI18n({
            legacy: false,
            locale: locale,
            fallbackLocale: 'en',
            messages: translations // Gunakan translations langsung dari props
        });

        const app = createApp({ render: () => h(App, props) });
        
        // Add GSAP to global properties
        app.config.globalProperties.$gsap = gsap;
        app.config.globalProperties.$ScrollTrigger = ScrollTrigger;
        
        // Register plugins
        app.use(mdiVue, { icons: mdijs });
        app.use(ZiggyVue);
        app.use(VueViewer);
        app.use(plugin);
        app.use(i18n); // Register i18n
        
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
