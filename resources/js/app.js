// import './bootstrap';
// import '../css/app.css';

// import { createApp, h } from 'vue';
// import { createInertiaApp } from '@inertiajs/vue3';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
// import { createI18n } from 'vue-i18n';

// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
//     setup({ el, App, props, plugin }) {
//         return createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .use(ZiggyVue, Ziggy)
//             .mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });

// const messages = {
//     ja: {
//         // 日本語のメッセージをここに定義
//     }
// };

// const i18n = createI18n({
//     locale: 'ja', // 言語設定
//     messages,
// });

// const app = createApp(App);
// app.use(i18n);
// app.mount('#app');

// Vue.component('test-component', require('./components/TestComponent.vue').default);
// app.use(RolesPermissionsToVue);

import './bootstrap';
import './micromodal';
import '../css/app.css';
import '../css/micromodal.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createI18n } from 'vue-i18n';
import ganttastic from '@infectoone/vue-ganttastic';
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'
import PrimeVue from 'primevue/config';
import VSwatches from 'vue3-swatches'
import 'vue3-swatches/dist/style.css'

import "vuetify/dist/vuetify.min.css";
// import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import { VBtn,VTooltip} from "vuetify/components";
// import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components:{
        VBtn,
        VTooltip
    }

});

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        app.use(ganttastic);
        app.use(VSwatches);
        app.use(vuetify);
          // LaravelPermissionToVueJS プラグインを追加
        app.use(LaravelPermissionToVueJS);
        // ここで i18n の設定も含めてあげる
        const messages = {
            ja: {
                // 日本語のメッセージをここに定義
            }
        };

        const i18n = createI18n({
            locale: 'ja', // 言語設定
            messages,
        });
        app.use(PrimeVue);
        app.use(i18n);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// 以下のコードは必要に応じて適切な場所に移動させてください
// Vue.component('test-component', require('./components/TestComponent.vue').default);
// app.use(RolesPermissionsToVue);

