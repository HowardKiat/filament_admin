import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',          // Your main app CSS file
                'resources/js/app.js',            // Your main JS file
                'resources/css/filament.css',    // Your Filament-specific CSS
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources', // Alias to resolve resources folder if needed
        },
    },
});
