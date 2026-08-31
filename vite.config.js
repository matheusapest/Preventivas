import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'node:fs';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],

    server: {
    host: '0.0.0.0',

    port: 5173,

    https: {
        key: fs.readFileSync(
            '/var/www/html/docker/nginx/ssl/preventivas.key'
        ),
        cert: fs.readFileSync(
            '/var/www/html/docker/nginx/ssl/preventivas.crt'
        ),
    },

    hmr: {
        host: '192.168.1.110',
        port: 5173,
        protocol: 'wss',
    },

    cors: true,

    watch: {
        usePolling: true,
        interval: 100,
        ignored: [
            '**/storage/framework/views/**',
        ],
    },
},
});
