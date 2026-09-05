import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/js/preventive-profiles/edit.js',
                'resources/js/preventive-profiles/create.js',
                'resources/js/preventive-profiles/rules/edit.js',

                'resources/js/preventive/index.js',
                'resources/js/preventive/continuation.js',
                'resources/js/preventive/create.js',

                'resources/js/components/photo-modal.js',

                'resources/js/operational-unit/operationalUnit.js',
                'resources/js/operational-unit/operationalMultipleUnit.js',
                'resources/js/operational-unit/operationalUnitMode.js',

                'resources/js/operational-profile/operationalProfile.js',

                'resources/js/transfers/create.js',
                'resources/js/transfers/search.js',

                'resources/js/maintenance/order/index.js',
                'resources/js/maintenance/receipts/multiple.js',
                'resources/js/maintenance/shipments/create.js',

                'resources/js/preventive-execution/activity.js',
                'resources/js/preventive-execution/show.js',
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
        cors: true,

        origin: 'https://preventivas.test:5173',

        https: {
            key: fs.readFileSync('./docker/nginx/ssl/preventivas.key'),
            cert: fs.readFileSync('./docker/nginx/ssl/preventivas.crt'),
        },

        hmr: {
            host: 'preventivas.test',
            protocol: 'wss',
        },

        watch: {
            usePolling: true,
            interval: 100,
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
