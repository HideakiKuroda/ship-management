import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Vuetify from 'vuetify'


export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Vuetify({ autoImport: true }),
    ],
    //これを入れないとviteを起動したとき画面が真っ白！！
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    }, 
});
