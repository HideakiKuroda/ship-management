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
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createI18n } from 'vue-i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        
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

