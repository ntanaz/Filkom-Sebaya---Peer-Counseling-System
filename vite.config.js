import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/global.css',
                'resources/css/landing.css',
                'resources/css/login.css',
                'resources/css/konseli.css',
                'resources/css/counselor.css',
                'resources/css/admin.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
