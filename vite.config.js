import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        cors: {
            origin: [
                'http://accounting-client.local',
                'http://127.0.0.1:5174',
                'http://localhost:5174',
            ],
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/layout.css',
                'resources/js/app.js',
                'resources/js/layout.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
                'resources/css/log-add.css',
                'resources/js/log-add.js',
                'resources/css/log-show.css',
            ],
            refresh: true,
        }),
    ],
});
