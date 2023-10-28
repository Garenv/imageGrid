import {defineConfig} from "vite";
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import copy from 'rollup-plugin-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/sass/loginPage.scss',
                'resources/sass/support/support.scss',
                'resources/css/style.css',
                'resources/js/main.js'
            ],
            refresh: true,
        }),
        react(),
        copy({
            targets: [
                { src: 'resources/assets/vendor/*', dest: 'public/build/assets/vendor' }
            ],
            hook: 'writeBundle' // ensure the copy is done after writing the bundle
        })
    ],
});
