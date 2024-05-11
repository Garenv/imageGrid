import { defineConfig } from "vite";
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import copy from 'rollup-plugin-copy'
import { configDotenv } from "dotenv";

configDotenv();

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/sass/loginPage.scss',
                'resources/sass/support/support.scss',
                'resources/css/style.css',
                'resources/js/main.js',
                'resources/sass/imageBattles/hallOfFame.scss'
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
                'resources/js/main.js',
                'resources/sass/imageBattles/hallOfFame.scss'
            ]
        },
        base: process.env.VITE_APP_URL + '/build/'
    }

});
