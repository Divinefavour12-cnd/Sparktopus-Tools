import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/themes/canvas/assets/sass/app.scss',
                'resources/themes/canvas/assets/js/app.js',
                'resources/themes/admin/assets/sass/app.scss',
                'resources/themes/admin/assets/js/app.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/bootstrap-icons/font/fonts/*',
                    dest: 'fonts'
                }
            ]
        }),
    ],
    resolve: {
        alias: {
            'simplebar-core': path.resolve(__dirname, 'node_modules/simplebar-core'),
        },
    },
});
