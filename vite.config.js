import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                'resources/css/login.css',
                'resources/css/user-dash.css',
                'resources/css/user-report.css',
                'resources/css/user-my-reports.css',
                'resources/css/user-collection-schedule.css',
                'resources/css/user-tickets.css',
                'resources/js/user-tickets.js',
                'resources/css/user-cleanup-drives.css',
                'resources/css/volunteer.css',
                'resources/css/custom.css',
                'resources/css/purok-dash.css',
                'resources/css/user-profile.css',
                'resources/css/user-tickets.css',
                'resources/js/user-tickets.js',
                'resources/css/purok-pages.css',
                'resources/css/purok-activities-db.css',
                'resources/css/staff-dashboards.css',
                'resources/js/staff-dashboards.js',
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
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
