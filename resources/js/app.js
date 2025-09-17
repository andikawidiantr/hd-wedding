import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import mdiVue from 'mdi-vue/v3';
import * as mdijs from '@mdi/js';
const appName = import.meta.env.VITE_APP_NAME ;
import gsap from 'gsap'
import 'viewerjs/dist/viewer.css'
import VueViewer from 'v-viewer'


// Jika Anda menggunakan plugin GSAP tambahan
import ScrollTrigger from 'gsap/ScrollTrigger'
import ScrollToPlugin from 'gsap/ScrollToPlugin'

// Registrasi plugin GSAP
gsap.registerPlugin(ScrollTrigger, ScrollToPlugin)

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
      setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
    // Menambahkan GSAP ke global properties
    app.config.globalProperties.$gsap = gsap
    
    // Atau jika Anda ingin menggunakan GSAP sebagai plugin
    app.use({
      install: (app) => {
        app.config.globalProperties.$gsap = gsap
        app.config.globalProperties.$ScrollTrigger = ScrollTrigger
      }
    })
    
  
    app.use(mdiVue, { icons: mdijs })
    app.use(ZiggyVue)
    app.use(VueViewer)
    app.use(plugin)
    app.mount(el)
  },
   
    progress: {
        color: '#4B5563',
    },
});
