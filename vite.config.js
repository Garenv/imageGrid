import { defineConfig } from "vite";
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import copy from 'rollup-plugin-copy'
import { configDotenv } from "dotenv";

configDotenv();

console.log("VITE_APP_ENV", process.env.VITE_APP_ENV);
console.log("VITE_APP_URL", process.env.VITE_APP_URL);

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
            // ensure the copy is done after writing the bundle
            // this line is crucial otherwise assets won't load properly
            hook: 'writeBundle'
        }),
    ],
    build: {
        rollupOptions: {
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/sass/loginPage.scss',
                'resources/sass/support/support.scss',
                'resources/css/style.css',
                'resources/js/main.js'
            ]
        },
        base: process.env.VITE_APP_ENV === 'stage' ? process.env.VITE_APP_URL + '/build/' : '/'
    }

});
