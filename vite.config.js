import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Allow external connections
        port: 5173,
        cors: true,      // Enable CORS
        hmr: {
            host: 'localhost' // Use localhost for HMR
        }
    }
});