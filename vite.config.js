import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { visualizer } from 'rollup-plugin-visualizer';
import { splitVendorChunkPlugin } from 'vite'

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
        // visualizer({
        //     open: true,  // 分析結果をビルド後に自動的に開くかどうか
        //     gzipSize: true,  // gzip圧縮後のサイズも表示
        //     brotliSize: true, // Brotli圧縮後のサイズも表示
        //   }),
        splitVendorChunkPlugin(),
    ],
    //これを入れないとviteを起動したとき画面が真っ白！！
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    },
    build: {
    rollupOptions: {
      output: {
        manualChunks: undefined
      }
    }
  },
});
